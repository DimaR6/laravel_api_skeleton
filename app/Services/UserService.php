<?php

namespace App\Services;

use App\Helpers\MailHelper;
use App\Models\User;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserVerificationCodeRepository;

class UserService
{
    private $userRepository;
    private $userVerificationCodeRepository;
    private $customerRepository;

    /**
     * UserHelper constructor.
     * @param  UserVerificationCodeRepository  $userVerificationCodeRepository
     * @param  CustomerRepository  $customerRepository
     * @param  UserRepository  $userRepository
     */
    public function __construct(
        UserVerificationCodeRepository $userVerificationCodeRepository,
        CustomerRepository $customerRepository,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userVerificationCodeRepository = $userVerificationCodeRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param array $input
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function signUp(array $input, $roleId)
    {
        $input['self_registered'] = true;
        $user = $this->userRepository->create($input);
        $userId = $user->id;
        $input['user_id'] = $userId;
        $emailLink = env('PATH_TO_ADMIN');

        switch ($roleId) {
            case User::CUSTOMER:

                $input['active'] = true;
                $input['role'] = User::CUSTOMER;
                $customer = $this->customerRepository->create($input);
                $emailLink .=  'customers/'. $customer->id .'/edit';
                break;
        }

        $role = __('roles.'.$user->role);

        //email to admin
        $subject  = __('subjects.mail-subject-new-account-registered', ['role' => $role]);
        $template = 'admin.account-created';
        $params   = [
            'user' => $user,
            'role' => $role,
            'url'  => $emailLink
        ];

        MailHelper::sendMail(
            config('mail.admin_email'),
            $template,
            $subject,
            $params
        );

        return $this->getFullUserByUserIdAndRole($userId, $roleId);
    }

    /**
     * @param $userId
     * @param $role
     * @return mixed
     */
    public function getFullUserByUserIdAndRole($userId, $role)
    {
        $relations = User::$userRelation[$role] ?? User::$userRelation[User::CUSTOMER];
        return $this->userRepository->getFullUserByUserIdAndRelations($userId, $relations);
    }

    /**
     * @param  int  $userId
     */
    public function confirmation(int $userId)
    {
        $user = $this->userRepository->find($userId);

        $verificationCode = $this->userVerificationCodeRepository->create($userId);

        $subject  = __('subjects.mail-subject-email-confirm');
        $template = 'user.email-confirm';
        $params = [
            'first_name' => $user->first_name,
            'url' => env('PATH_TO_FRONTEND') . 'mail-confirm?verificationCode=' . $verificationCode->verify_code,
        ];

        MailHelper::sendMail(
            $user->email,
            $template,
            $subject,
            $params
        );
    }

    /**
     * @param $user
     * @param $input
     * @return mixed
     */
    public function update($user, $input)
    {
        $userId = $user->id;
        $userRole = $user->role;

        $this->userRepository->update($input, $userId);

        if ($userRole == User::CUSTOMER) {
            $customerId = $user->customer->id;
            $this->customerRepository->update($input, $customerId);
        }

        return $this->getFullUserByUserIdAndRole($userId, $userRole);
    }
}
