<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRecipeRequest extends FormRequest
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
        $rules = [];
        $rules['name'] = 'required|string|max:100';
        $rules['plu'] = 'required|string|max:100';
        $rules['tax'] = 'required|numeric|min:0';
        $rules['station_id'] = 'required|numeric|min:1';
        $rules['prices'] = 'required';
        return $rules;
    }
}
