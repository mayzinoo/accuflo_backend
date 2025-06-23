<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'name' => ['required','string', 'max:100'],
            'class_id' => ['required','string', 'max:100'],
            'category_id' => ['numeric', 'min:0'],
            'quality_id' => ['numeric', 'min:0'],
            'barcode' => ['required','string', 'max:100'],
            'item_unit_id' => ['required','string', 'max:100'],
            'countable_unit' => ['required','numeric', 'min:1'],
            'countable_unit_id' => ['required','string', 'max:100'],
            'empty_weight' => ['required','numeric', 'min:1'],
            'empty_weight_id' => ['required','string', 'max:100'],
            'full_weight' => ['required','numeric', 'min:1'],
            'full_weight_id' => ['required','string', 'max:100'],
            'density' => ['required','numeric', 'min:0'],
            'density_weight_id' => ['required','string', 'max:100'],
            'density_unit_id' => ['required','string', 'max:100'],
            'package_status' => ['required','string', 'max:100'],
            'user_id' => ['required','numeric', 'max:100']
         ];
    }
}
