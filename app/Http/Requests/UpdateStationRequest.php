<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Session;

class UpdateStationRequest extends FormRequest
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
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('stations','name')->ignore($data['station_id'])
                    ->using(function ($q) { $q->where('user_id', 1)->where('period_id', 1); })
            ],
        ];
    }
    public function withValidator($validator){
        if($validator->fails()){
            Session::flash('validation_error', 'edit_station');
            Session::flash('station_id', $this->station_id);
            Session::flash('station_name', $this->station_name);
        }
    }
}
