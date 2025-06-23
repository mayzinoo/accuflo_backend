<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => [ 'required', 'max:64',
                Password::min(8)],
            'confirm_password' => ['required', 'same:password'],
            'phone_no' => ['required','string'],
            'role' => ['required','string']
        ];

        if(isset($this->role) && ($this->role == 'client')){
            $rules['company_id'] = ['required'];
            $rules['branch_id'] = ['required'];            
        }

        
        return $rules;
    }
}
