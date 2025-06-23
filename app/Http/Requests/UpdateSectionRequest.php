<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class UpdateSectionRequest extends FormRequest
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
                Rule::unique('sections','name')->ignore($data['section_id'])
                    ->using(function ($q) use($data) { $q->where('station_id', $data['station_id']); })
            ],
            'station_id' => ['required']
        ];
    }
    public function withValidator($validator){
        if($validator->fails()){
            Session::flash('validation_error', 'edit_section');
            Session::flash('section_id', $this->section_id);
            Session::flash('station_id', $this->station_id);
            Session::flash('section_name', $this->section_name);
        }
    }
}
