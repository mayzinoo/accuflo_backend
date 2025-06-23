<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFullCountRequest extends FormRequest
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
        $data = $this->all();
        return [
           'item_id' => [
                'required',
                Rule::unique('full_counts','item_id')
                    ->using(function ($q) use($data) { 
                        $q->where('period_id', $data['period_id'])
                          ->where('station_id', $data['station_id'])
                          ->where('package_id', $data['package_id']);
                    })
            ],
           'station_id' => ['required'],
           'period_count' => ['required'],
           'size' => ['required']
        ];
    }
}
