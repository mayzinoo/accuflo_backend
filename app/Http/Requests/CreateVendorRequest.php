<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVendorRequest extends FormRequest
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
           'name' => ['required', 'string', 'max:100'],
           'code' => [ 'required', 'string', 'max:100'],
           //'invoice_due_date' => [ 'numeric'],
           'invoice_due_date_unit' => [ 'numeric'],
           'status' => [ 'string'],
           //'address_line_1' => [ 'string'],
           //'address_line_2' => [ 'string'],
           //'city' => [ 'string'],
           //'state' => [ 'string'],
           //'country_code' => [ 'numeric'],
           //'postal_code' => [ 'numeric'],
           //'phone' => [ 'numeric'],
           //'cell' => [ 'string'],
           //'fax' => [ 'string'],
           //'email' => [ 'string'],
           //'notes' => [ 'string'],
        ];
    }
}
