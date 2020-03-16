<?php

namespace App\Http\Requests\API\Auth;

use App\Helpers\ValidationHelper;
use InfyOm\Generator\Request\APIRequest;

/**
 * Request contain data and their rules that passed with request.
 */
class ConfirmUserEmailRequest extends APIRequest
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
            'user_verify_code' => 'required'
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
