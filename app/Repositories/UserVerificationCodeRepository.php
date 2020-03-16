<?php

namespace App\Repositories;

use App\Helpers\DateHelper;
use App\Helpers\HashGeneratorHelper;
use App\Models\UserVerificationCode;
use App\Repositories\BaseRepository;

/**
 * Class UserVerificationCodeRepository
 * @package App\Repositories
 * @version April 24, 2019, 2:50 pm UTC
*/

class UserVerificationCodeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'user_id',
        'verify_code',
        'expires_in_verify'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UserVerificationCode::class;
    }

    /**
     * @param array $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($userId)
    {
        $input = [
            'user_id' => $userId,
            'verify_code' => HashGeneratorHelper::generateHash(),
            'expires_in_verify' => DateHelper::getTimestampByHours(env('EXPIRES_IN_EMAIL_VERIFY', 24)),
        ];
        return parent::create($input);
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getByCode($code)
    {
        return UserVerificationCode::query()
            ->where('verify_code', $code)
            ->first();
    }
}
