<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeightRequest extends FormRequest
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
            'item_id' => ['required','numeric', 'max:100'],
            'station_id' => ['required','numeric', 'max:100'],
            'period_id' => ['numeric', 'min:1'],
            'section' => ['string', 'max:100'],
            'shelf' => ['numeric', 'max:100'],
            'last_peroid_weight' => ['required','numeric', 'min:1'],
            'last_peroid_weight_unit' => ['required','string', 'max:100'],
            'current_peroid_weight' => ['required','numeric', 'min:1'],
            'current_peroid_weight_unit' => ['required','string', 'max:100'],
            'volume_different' => ['required','numeric', 'min:0'],
            'volume_different_unit' => ['required','string', 'max:100'],
        ];
    }
}
