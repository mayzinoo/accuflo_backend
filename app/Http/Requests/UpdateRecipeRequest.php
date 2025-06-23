<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
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
           'plu' => ['required'],
           'tax' => [ 'required'],
           'station_id' => ['required'],
           'original_station_id' => ['required','same:station_id'],
           'price_level_id' => ['required'],
           'item_name'      => ['required'],
           'prices' => ['required']
        ];
    }
    public function messages(){
        return [
        'item_name.required' => 'Please specify the ingredients for this menu',
        'original_station_id.same' => 'ID mismatch.'
        ];
    }
}
