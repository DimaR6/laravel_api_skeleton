<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\ApiException;
use App\Helpers\DateHelper;
use App\Helpers\HashGeneratorHelper;
use App\Helpers\MailHelper;
use App\Helpers\PassportTokenHelper;
use App\Helpers\PhoneHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\Auth\CheckCredentialsAPIRequest;
use App\Http\Requests\API\Auth\ConfirmUserEmailRequest;
use App\Http\Requests\API\Auth\PasswordResetAPIRequest;
use App\Http\Requests\API\Auth\ResendMailConfirmationAPIRequest;
use App\Http\Requests\API\Auth\SignInUserAPIRequest;
use App\Http\Requests\API\Auth\SignUpAPIRequest;
use App\Http\Requests\API\Auth\UpdatePasswordAPIRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserVerificationCodeRepository;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends AppBaseController
{

    /** @var  UserRepository */
    private $userRepository;
    /** @var DateHelper */
    private $dateHelper;
    /** @var UserVerificationCodeRepository */
    private $userVerificationCodeRepository;
    /** @var UserService */
    private $userService;
    /** @var PassportTokenHelper */
    private $passportTokenHelper;

    /**
     * AuthApiController constructor.
     * @param UserRepository $userRepository
     * @param DateHelper $dateHelper
     * @param UserVerificationCodeRepository $userVerificationCodeRepository
     * @param UserService $userService
     * @param PassportTokenHelper $passportTokenHelper
     */
    public function __construct(
        UserRepository $userRepository,
        DateHelper $dateHelper,
        UserVerificationCodeRepository $userVerificationCodeRepository,
        UserService $userService,
        PassportTokenHelper $passportTokenHelper
    ) {
        $this->userRepository = $userRepository;
        $this->dateHelper = $dateHelper;
        $this->userVerificationCodeRepository = $userVerificationCodeRepository;
        $this->userService = $userService;
        $this->passportTokenHelper = $passportTokenHelper;
    }


    /**
     * @param SignUpAPIRequest $request
     * @return mixed
     */
    public function signUp(SignUpAPIRequest $request)
    {
        $input = $request->all();

        $user = $this->userService->signUp($input, User::CUSTOMER);

        $this->userService->confirmation($user->id);

        return $this->sendResponse($user);
    }

    /**
     * @param ConfirmUserEmailRequest $request
     * @return mixed
     */
    public function mailConfirm(ConfirmUserEmailRequest $request)
    {
        $data = $request->all();

        $userVerifyCode = $data['user_verify_code'];

        $verificationCodeRecord = $this->userVerificationCodeRepository->getByCode($userVerifyCode);

        if (is_null($verificationCodeRecord)) {
            ApiException::throw(ApiException::VERIFY_CODE_NOT_FOUND);
        }

        $time = $this->dateHelper->getNowTimestamp();
        $userId = $verificationCodeRecord->user_id;
        // TODO delete used mail confirm tokens via cron or when used
        $this->userRepository->update(['email_verified_at' => $time], $userId);

        $user = $this->userRepository->getUserById($userId);

        $result = $this->passportTokenHelper->createBearerTokenForUser($user, $request);

        return $this->sendResponse($result);
    }

    /**
     * @param CheckCredentialsAPIRequest $request
     * @return mixed
     */
    public function checkCredentials(CheckCredentialsAPIRequest $request)
    {
        return $this->sendResponse();
    }

    /**
     * @param SignInUserAPIRequest $request
     * @return mixed
     */
    public function login(SignInUserAPIRequest $request)
    {

        $data = $request->all();

        $login = $data['login'];
        $fieldName = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if ($fieldName == 'phone') {
            $login = PhoneHelper::formatPhone($login);
        }

        $credentials = [
            $fieldName => $login,
            'password' => $data['password']
        ];

        if (!Auth::attempt($credentials)) {
            ApiException::throw(ApiException::NOT_CORRECT_CREDENTIALS);
        }

        $user = $request->user();

        if (is_null($user->email_verified_at)) {
            ApiException::throw(ApiException::NOT_VERIFIED_EMAIL);
        }

        $result = $this->passportTokenHelper->createBearerTokenForUser($user, $request);

        return $this->sendResponse($result);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function accessToken(Request $request)
    {
        $user = $request->user();

        $accessToken = PassportTokenHelper::parseToken();
        PassportTokenHelper::refreshToken($user->token()->id);
        
        $data = [
            'accessToken' => $accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => Carbon::parse($user->token()->expires_at)->toDateTimeString(),
            'user' => $this->userService->getFullUserByUserIdAndRole($user->id, $user->role)

        ];

        return $this->sendResponse($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->sendResponse();
    }

    /**
     * @param UpdatePasswordAPIRequest $request
     * @return mixed
     */
    public function changePassword(UpdatePasswordAPIRequest $request)
    {
        $user = $request->user();
        $data = $request->all();

        $params = array(
            'first_name' => $user->first_name,
            'url' => env('PATH_TO_FRONTEND').'login'
        );
        MailHelper::sendMail(
            $user->email,
            'user.password-changed-successfully',
            __('subjects.mail-subject-your-password-changed-successfully'),
            $params
        );

        $this->userRepository->update(['password' => $data['password']], $user->id);

        return $this->sendResponse();
    }

    /**
     * @param PasswordResetAPIRequest $request
     * @return mixed
     */
    public function resetPassword(PasswordResetAPIRequest $request)
    {
        $data = $request->all();

        $user = $this->userRepository->getUserByEmail($data['email']);

        if (is_null($user)) {
            ApiException::throw(ApiException::USER_NOT_FOUND);
        }

        $newUserPass = HashGeneratorHelper::generateStrRandom(8);
        $this->userRepository->update(['password' => $newUserPass], $user->id);
        // send mail
        $subject = __('subjects.mail-subject-you-new-password');
        $template = 'user.password-updated';
        $params = [
            'first_name' => $user->first_name,
            'password' => $newUserPass,
            'url' => env('PATH_TO_FRONTEND') . 'login'
        ];

        MailHelper::sendMail(
            $user->email,
            $template,
            $subject,
            $params
        );

        return $this->sendResponse();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function resendMailConfirmation(ResendMailConfirmationAPIRequest $request)
    {
        $data = $request->all();
        $user = $this->userRepository->getUserByEmailOrPhone($data['login']);

        if (is_null($user)) {
            ApiException::throw(ApiException::USER_NOT_FOUND);
        }

        $userId = $user->id;

        if (is_null($user->email_verified_at)) {
            $this->userService->confirmation($userId);
        }

        return $this->sendResponse();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getUser(Request $request)
    {
        $user = $request->user();

        return $this->sendResponse($user);
    }

}
