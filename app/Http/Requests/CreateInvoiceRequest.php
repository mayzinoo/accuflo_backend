<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $data = $this;
        $rules = [];
        $rules['vendor_id'] = [
            'required',
            Rule::unique('invoices')->where(function ($query) use($data) {
                return $query->where('vendor_id', $data->vendor_id)
                ->where('invoice_number', $data->invoice_number);
            })
        ];
        $rules['invoice_number'] = 'required';
        $rules['invoice_delivery_date'] = 'required|date_format:Y-m-d';
        $rules['invoice_due_date'] = 'required|date_format:Y-m-d';
        return $rules;
    }
    public function messages()
    {
        return [
            'vendor_id.unique' => 'Vendor and Invoice Number have been taken'
        ];
    }
}
