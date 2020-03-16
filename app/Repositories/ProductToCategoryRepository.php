<?php

namespace App\Repositories;

use App\Models\ProductToCategory;
use App\Repositories\BaseRepository;

/**
 * Class ProductToCategoryRepository
 * @package App\Repositories
 * @version March 11, 2020, 11:09 am CET
*/

class ProductToCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'category_id'
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
        return ProductToCategory::class;
    }
}
