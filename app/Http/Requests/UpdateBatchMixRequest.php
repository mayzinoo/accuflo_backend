<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatchMixRequest extends FormRequest
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
           'name' => ['required', 'string', 'max:100'],
           'barcode' => ['required', 'string', 'max:100'],
           'code' => [ 'required', 'string', 'max:100'],
           'unit_des' => [ 'string', 'max:20'],
           'inventory_status' => [ 'string', 'max:10'],
           'liquid_status' => [ 'string', 'max:10'],
           'user_id' => ['required','numeric', 'max:100'],
           'period_id' => ['required','numeric', 'max:100']
        ];
    }

    public function messages()
    {
        return [
            'ingredients.*.name.required' => 'Ingredient fields is required'
        ];
    }
}
