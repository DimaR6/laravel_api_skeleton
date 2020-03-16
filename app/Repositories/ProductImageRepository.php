<?php

namespace App\Repositories;

use App\Models\ProductImage;
use App\Repositories\BaseRepository;

/**
 * Class ProductImageRepository
 * @package App\Repositories
 * @version March 11, 2020, 10:53 am CET
*/

class ProductImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'image_id',
        'sort_order',
        'main'
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
        return ProductImage::class;
    }
}
