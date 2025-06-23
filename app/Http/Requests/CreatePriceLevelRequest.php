<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePriceLevelRequest extends FormRequest
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
           'station_id_copy' => ['required','string', 'max:100'],
        ];
    }
    public function messages(){
        return [
            'station_id_copy.required' => 'Please create station in Location Setup menu first before setting price level.',
            
        ];
    }
}
