<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="role",
 *          description="role",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email_verified_at",
 *          description="email_verified_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="admin_token",
 *          description="admin_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="self_registered",
 *          description="self_registered",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="logged_at",
 *          description="logged_at",
 *          type="string",
 *          format="date-time"
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
class User extends Authenticatable
{
    use SoftDeletes;
    use  Notifiable, HasApiTokens;

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password'
    ];

    public $fillable = [
        'email',
        'phone',
        'role',
        'email_verified_at',
        'password',
        'remember_token',
        'admin_token',
        'self_registered',
        'logged_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'phone' => 'string',
        'email' => 'string',
        'role' => 'string',
        'email_verified_at' => 'datetime',
        'logged_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'phone' => 'required|unique:users',
        'email' => 'required|unique:users|email',
        'password' => 'required|confirmed|min:6|max:191',
    ];
    /**
     * User Roles
     *
     * @var array
     */
    const CUSTOMER = 'CUSTOMER';
    const ADMIN = 'ADMIN';
    const VENDOR = 'VENDOR';
    const TEST_PHONE = '00000';

    public static $userRelation = [
        self::CUSTOMER => ['customer'],
        self::ADMIN => ['customer'],
        self::VENDOR => ['vendor'],
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
