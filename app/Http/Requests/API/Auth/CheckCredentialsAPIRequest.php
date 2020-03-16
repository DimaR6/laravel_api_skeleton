<?php

namespace App\Http\Requests\API\Auth;

use App\Helpers\ValidationHelper;
use App\Models\User;
use App\Rules\CheckPhoneOnUpdate;
use Illuminate\Support\Facades\Request;
use InfyOm\Generator\Request\APIRequest;

class CheckCredentialsAPIRequest extends APIRequest
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
        $input = Request::all();

        $rules = [
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6|max:191',
        ];

        if ($input['phone'] != User::TEST_PHONE) {
            $rules['phone'] = ['required', 'phone:'.env('ISO_COUNTRY_CODES', 'NO'), 'unique:users'];
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return ValidationHelper::renderValidationMessages($this->rules());
    }
}
