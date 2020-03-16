<?php

namespace App\Repositories;

use App\Helpers\PhoneHelper;
use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version April 19, 2019, 9:13 am UTC
 */
class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'remember_token'
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
        return User::class;
    }

    /**
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($input)
    {
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        if (isset($input['phone']) && $input['phone'] != User::TEST_PHONE) {
            $input['phone'] = PhoneHelper::formatPhone($input['phone']);
        }

        return parent::create($input);
    }

    /**
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getUserByEmail(string $email)
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * @param $login
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getUserByEmailOrPhone($login)
    {
        return User::query()
            ->where(function ($query) use ($login) {
                $query->where('email', $login)
                    ->orWhere('phone', $login);
            })
            ->first();
    }

    /**
     * @param array $input
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function update($input, $id)
    {
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        if (isset($input['phone']) && $input['phone'] != User::TEST_PHONE) {
            $input['phone'] = PhoneHelper::formatPhone($input['phone']);
        }

        return parent::update($input, $id);
    }

    /**
     * @param $userId
     * @param array $relations
     * @return mixed
     */
    public function getFullUserByUserIdAndRelations($userId, array $relations)
    {
        return $this->model()::with($relations)->find($userId);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return $this->model()::query()
            ->where('id', $id)
            ->first();
    }
}
