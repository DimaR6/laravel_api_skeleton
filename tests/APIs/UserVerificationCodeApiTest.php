<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeUserVerificationCodeTrait;
use Tests\ApiTestTrait;

class UserVerificationCodeApiTest extends TestCase
{
    use MakeUserVerificationCodeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_verification_code()
    {
        $userVerificationCode = $this->fakeUserVerificationCodeData();
        $this->response = $this->json('POST', '/api/userVerificationCodes', $userVerificationCode);

        $this->assertApiResponse($userVerificationCode);
    }

    /**
     * @test
     */
    public function test_read_user_verification_code()
    {
        $userVerificationCode = $this->makeUserVerificationCode();
        $this->response = $this->json('GET', '/api/userVerificationCodes/'.$userVerificationCode->id);

        $this->assertApiResponse($userVerificationCode->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_verification_code()
    {
        $userVerificationCode = $this->makeUserVerificationCode();
        $editedUserVerificationCode = $this->fakeUserVerificationCodeData();

        $this->response = $this->json('PUT', '/api/userVerificationCodes/'.$userVerificationCode->id, $editedUserVerificationCode);

        $this->assertApiResponse($editedUserVerificationCode);
    }

    /**
     * @test
     */
    public function test_delete_user_verification_code()
    {
        $userVerificationCode = $this->makeUserVerificationCode();
        $this->response = $this->json('DELETE', '/api/userVerificationCodes/'.$userVerificationCode->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/userVerificationCodes/'.$userVerificationCode->id);

        $this->response->assertStatus(404);
    }
}
