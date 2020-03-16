<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MailOrPhoneOnLogin implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $return = true;

        $login = $value;
        $fieldName = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $validationArray = [
            $fieldName => $login
        ];

        $rules = [
            'email' => 'sometimes|email',
        ];

        if ($fieldName == 'phone' && $login != User::TEST_PHONE) {
            $rules['phone'] = 'sometimes|phone:' . env('ISO_COUNTRY_CODES', 'NO');
        }

        $validator = Validator::make($validationArray, $rules);

        if ($validator->fails()) {
            $return = false;
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
        return 'validation-not-correct-login';
    }
}
