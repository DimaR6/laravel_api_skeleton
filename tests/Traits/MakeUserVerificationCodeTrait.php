<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\UserVerificationCode;
use App\Repositories\UserVerificationCodeRepository;

trait MakeUserVerificationCodeTrait
{
    /**
     * Create fake instance of UserVerificationCode and save it in database
     *
     * @param array $userVerificationCodeFields
     * @return UserVerificationCode
     */
    public function makeUserVerificationCode($userVerificationCodeFields = [])
    {
        /** @var UserVerificationCodeRepository $userVerificationCodeRepo */
        $userVerificationCodeRepo = \App::make(UserVerificationCodeRepository::class);
        $theme = $this->fakeUserVerificationCodeData($userVerificationCodeFields);
        return $userVerificationCodeRepo->create($theme);
    }

    /**
     * Get fake instance of UserVerificationCode
     *
     * @param array $userVerificationCodeFields
     * @return UserVerificationCode
     */
    public function fakeUserVerificationCode($userVerificationCodeFields = [])
    {
        return new UserVerificationCode($this->fakeUserVerificationCodeData($userVerificationCodeFields));
    }

    /**
     * Get fake data of UserVerificationCode
     *
     * @param array $userVerificationCodeFields
     * @return array
     */
    public function fakeUserVerificationCodeData($userVerificationCodeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull,
            'verify_code' => $fake->word,
            'expires_in_verify' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $userVerificationCodeFields);
    }
}
