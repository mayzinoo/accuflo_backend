<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Session;

class CreateStationRequest extends FormRequest
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
        $data['branch_id'] = session()->get('branch_id'); 
        $data['period_id'] = session()->get('period_id'); 
        return [
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('stations','name')
                    ->using(function ($q) use($data) { $q->where('branch_id', $data['branch_id'])->where('period_id', $data['period_id']); })
            ],
        ];
    }
    public function withValidator($validator){
        if($validator->fails()){
            Session::flash('validation_error', 'create_station'); 
        }
    }
}
