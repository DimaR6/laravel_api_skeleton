<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\BaseRepository;

/**
 * Class CustomerRepository
 * @package App\Repositories
 * @version March 4, 2020, 2:26 pm UTC
*/

class CustomerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'active',
        'newsletter'
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
        return Customer::class;
    }

    /**
     * @param $input
     * @param $userId
     * @return mixed
     */
    public function updateByUserId($input, $userId)
    {
       return $this->model()::query()
            ->where('user_id', $userId)
            ->update($input);
    }
}
