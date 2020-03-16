<?php

namespace App\Http\Requests\API\Auth;

use App\Helpers\ValidationHelper;
use InfyOm\Generator\Request\APIRequest;

class SignUpAPIRequest extends APIRequest
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
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6|max:191',
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
