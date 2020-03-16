<?php

namespace App\Models;

use Eloquent as Model;

/**
 * @SWG\Definition(
 *      definition="ProductImage",
 *      required={""},
 *      @SWG\Property(
 *          property="product_id",
 *          description="product_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="image_id",
 *          description="image_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="sort_order",
 *          description="sort_order",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="main",
 *          description="main",
 *          type="boolean"
 *      )
 * )
 */
class ProductImage extends Model
{
    public $table = 'product_images';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'product_id',
        'image_id',
        'sort_order',
        'main'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'image_id' => 'integer',
        'sort_order' => 'integer',
        'main' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'image_id' => 'required',
        'sort_order' => 'required',
        'main' => 'required'
    ];

    /**
     * @return mixed
     */
    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

}
