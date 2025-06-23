<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $user = $this->user;

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 'string', 'email', 'max:100',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => [ 'nullable', 'max:64',
                Password::min(8)
            ],
            'confirm_password' => [ 'required_with:password', 'same:password'],
            'role' => ['required','string']
        ];

        if(isset($this->role) && ($this->role == 'client')){
            $rules['company_id'] = ['required'];
            $rules['branch_id'] = ['required'];
            // $rules['period_end_date'] = ['required'];
        }

        return $rules;
        
    }
}
