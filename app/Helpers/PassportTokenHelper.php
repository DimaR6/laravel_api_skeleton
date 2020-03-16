<?php


namespace App\Helpers;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;

class PassportTokenHelper
{
    /** @var  UserRepository */
    private $userRepository;
    /** @var DateHelper */
    private $dateHelper;
    /** @var  UserService */
    private $userService;

    /**
     * PassportTokenHelper constructor.
     * @param UserRepository $userRepository
     * @param DateHelper $dateHelper
     * @param UserService $userService
     */
    public function __construct(
        UserRepository $userRepository,
        DateHelper $dateHelper,
        UserService $userService
    ) {
        $this->userRepository = $userRepository;
        $this->dateHelper = $dateHelper;
        $this->userService = $userService;
    }

    /**
     * @return mixed
     */
    public static function parseToken()
    {
        $token = null;
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
        }

        return $token;
    }

    /**
     * @param $token
     * @return int
     */
    public static function refreshToken($token)
    {
        return DB::table('oauth_access_tokens')
            ->where('id', $token)
            ->update(['expires_at' => now()->addMonths(env('PERSONAL_TOKENS_EXPIRE_IN', 6))]);
    }

    /**
     * @return array
     */
    public static function getUserByBearerToken()
    {
        $user = [];
        $accessToken = PassportTokenHelper::parseToken();

        if (is_null($accessToken)) {
            return $user;
        }

        $client = new Client();

        try {
            $response = $client->request('GET', url('/api/auth/user'), [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);


        } catch (ClientException $e) {
            return $user;
        }

        $body = $response->getBody()->getContents();
        $body = json_decode($body);
        if (isset($body->data->id)) {
            $user = $body->data;
        }

        return $user;
    }

    /**
     * @param $user
     * @param $request
     * @return array
     */
    public function createBearerTokenForUser($user, $request)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        $data = [
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user' => $this->userService->getFullUserByUserIdAndRole($user->id, $user->role),
        ];

        //update date logged_at
        $this->userRepository->update(['logged_at' => $this->dateHelper->getNowTimestamp()], $user->id);

        return $data;
    }

}