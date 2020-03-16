<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckPhoneOnUpdate implements Rule
{

    private $user;

    /**
     * CheckPhoneOnUpdate constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $return = false;

        $userFromDb = User::query()
            ->where('phone', $value)
            ->first();

        if (is_null($userFromDb)) {
            return true;
        }

        if ($userFromDb->id === $this->user->id) {
            $return = true;
        }

        return $return;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'validation-phone-unique';
    }
}
