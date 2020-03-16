<?php namespace Tests\Repositories;

use App\Models\UserVerificationCode;
use App\Repositories\UserVerificationCodeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeUserVerificationCodeTrait;
use Tests\ApiTestTrait;

class UserVerificationCodeRepositoryTest extends TestCase
{
    use MakeUserVerificationCodeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var UserVerificationCodeRepository
     */
    protected $userVerificationCodeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userVerificationCodeRepo = \App::make(UserVerificationCodeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_verification_code()
    {
        $userVerificationCode = $this->fakeUserVerificationCodeData();
        $createdUserVerificationCode = $this->userVerificationCodeRepo->create($userVerificationCode);
        $createdUserVerificationCode = $createdUserVerificationCode->toArray();
        $this->assertArrayHasKey('id', $createdUserVerificationCode);
        $this->assertNotNull($createdUserVerificationCode['id'], 'Created UserVerificationCode must have id specified');
        $this->assertNotNull(UserVerificationCode::find($createdUserVerificationCode['id']), 'UserVerificationCode with given id must be in DB');
        $this->assertModelData($userVerificationCode, $createdUserVerificationCode);
    }

    /**
     * @test read
     */
    public function test_read_user_verification_code()
    {
        $userVerificationCode = $this->makeUserVerificationCode();
        $dbUserVerificationCode = $this->userVerificationCodeRepo->find($userVerificationCode->id);
        $dbUserVerificationCode = $dbUserVerificationCode->toArray();
        $this->assertModelData($userVerificationCode->toArray(), $dbUserVerificationCode);
    }

    /**
     * @test update
     */
    public function test_update_user_verification_code()
    {
        $userVerificationCode = $this->makeUserVerificationCode();
        $fakeUserVerificationCode = $this->fakeUserVerificationCodeData();
        $updatedUserVerificationCode = $this->userVerificationCodeRepo->update($fakeUserVerificationCode, $userVerificationCode->id);
        $this->assertModelData($fakeUserVerificationCode, $updatedUserVerificationCode->toArray());
        $dbUserVerificationCode = $this->userVerificationCodeRepo->find($userVerificationCode->id);
        $this->assertModelData($fakeUserVerificationCode, $dbUserVerificationCode->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_verification_code()
    {
        $userVerificationCode = $this->makeUserVerificationCode();
        $resp = $this->userVerificationCodeRepo->delete($userVerificationCode->id);
        $this->assertTrue($resp);
        $this->assertNull(UserVerificationCode::find($userVerificationCode->id), 'UserVerificationCode should not exist in DB');
    }
}
