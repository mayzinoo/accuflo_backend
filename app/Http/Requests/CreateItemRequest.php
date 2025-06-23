<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
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
           'name' => ['required'],
           'class_id' => ['required','integer'],
           'category_id' => ['integer'],
           'quality_id' => ['integer'],
           'barcode' => ['required','max:100'],
           'countable_unit' => ['nullable'],
           'countable_size' => ['nullable'],
           'empty_weight' => ['nullable'],
           'empty_weight_size' => ['nullable'],
           'full_weight' => ['nullable'],
           'full_weight_size' => ['nullable'],
           'density' => ['nullable'],
           'density_m_unit' => ['nullable'],
           'density_v_unit' => ['nullable'],
           'sizeoption' => ['nullable'],
           'quantification' => ['nullable'],
           'package_size' => ['nullable'],
           'unit_from' => ['nullable'],
           'package_name' => ['nullable'],
           'package_barcode' => ['nullable'],
        ];
    }
}
