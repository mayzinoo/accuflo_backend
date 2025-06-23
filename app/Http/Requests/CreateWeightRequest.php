<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class CreateWeightRequest extends FormRequest
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
                Rule::unique('weights','item_id')
                    ->using(function ($q) use($data) { 
                        $q->where('period_id', $data['period_id'])
                          ->where('station_id', $data['station_id'])
                          ->where('package_id', $data['package_id']);
                    })
            ],
           'station_id' => ['required'],
           'weight' => ['required'],
           'unit_id' => ['required'],
           'size' => ['required'],
        ];
    }
    public function withValidator($validator){
        if($validator->fails()){
            Session::flash('validation_error', 'create_weight');
        }
    }
}
