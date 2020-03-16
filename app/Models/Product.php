<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Product",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="vendor_id",
 *          description="vendor_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="price",
 *          description="price",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="shipping",
 *          description="shipping",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="minimum",
 *          description="minimum",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="weight",
 *          description="weight",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="height",
 *          description="height",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="length",
 *          description="length",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="width",
 *          description="width",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="approved",
 *          description="approved",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="active",
 *          description="active",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Product extends Model
{
    use SoftDeletes;

    public $table = 'products';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'vendor_id' => 'integer',
        'name' => 'string',
        'title' => 'string',
        'description' => 'string',
        'price' => 'float',
        'shipping' => 'float',
        'minimum' => 'integer',
        'weight' => 'float',
        'height' => 'float',
        'length' => 'float',
        'width' => 'float',
        'approved' => 'integer',
        'active' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'vendor_id' => 'required',
        'name' => 'required',
        'price' => 'required',
        'shipping' => 'required',
        'minimum' => 'required',
        'weight' => 'required',
        'height' => 'required',
        'length' => 'required',
        'width' => 'required',
        'approved' => 'required',
        'active' => 'required'
    ];

    /**
     * @return mixed
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * @return mixed
     */
    public function productToCategory()
    {
        return $this->hasMany(ProductToCategory::class);
    }

    /**
     * @return mixed
     */
    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            ProductToCategory::class,
            'product_id',
            'id',
            'id',
            'category_id'
        );
    }
}
