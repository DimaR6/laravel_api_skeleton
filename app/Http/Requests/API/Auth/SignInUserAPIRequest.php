<?php

namespace App\Http\Requests\API\Auth;

use App\Helpers\ValidationHelper;
use App\Models\User;
use App\Rules\MailOrPhoneOnLogin;
use InfyOm\Generator\Request\APIRequest;

class SignInUserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'login' => ['required', new MailOrPhoneOnLogin()],
            'password' => 'required|min:6|max:191',
            'remember_me' => 'boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return ValidationHelper::renderValidationMessages($this->rules());
    }
}
