<?php

namespace App\Http\Requests\API\User;

use App\Helpers\ValidationHelper;
use App\Models\User;
use App\Rules\CheckPhoneOnUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use InfyOm\Generator\Request\APIRequest;

class UpdateUserAPIRequest extends APIRequest
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
        $user = Auth::user();

        if ($user->role == User::CUSTOMER) {
            return $this->customerRules($user);
        }

    }

    /**
     * @param $user
     * @return array
     */
    public function customerRules($user) {

        $input = Request::all();

        $rules = [
            'name' => 'required|max:255',
        ];

        if ($input['phone'] != User::TEST_PHONE) {
            $rules['phone'] = ['required', 'phone:'.env('ISO_COUNTRY_CODES', 'NO'), new CheckPhoneOnUpdate($user)];
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
