<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\BaseRepository;

/**
 * Class ProductRepository
 * @package App\Repositories
 * @version March 10, 2020, 4:43 pm CET
*/

class ProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'vendor_id',
        'name',
        'title',
        'description',
        'price',
        'shipping',
        'minimum',
        'weight',
        'height',
        'length',
        'width',
        'approved',
        'active'
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
        return Product::class;
    }

    /**
     * @return mixed
     */
    public function getFullProducts()
    {
        return $this->model()::with([
            'productImages.image',
            'categories'
        ])->get();
    }
}
