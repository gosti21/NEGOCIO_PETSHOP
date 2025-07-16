<?php

namespace App\Http\Requests\Admin\ShippingCompany;

use Illuminate\Foundation\Http\FormRequest;

class StoreShippingCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'between:3,80',
                'unique:shipping_companies,name'
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:shipping_companies,email'
            ],
            'phone' => [
                'required', 
                'integer', 
                'digits:9', 
                'unique:phones,number',
            ],
            'district' => [
                'required',
                'string',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'between:3,60'
            ],
            'street' => [
                'required',
                'string',
            ],
            'reference' => [
                'required',
                'string'
            ],
        ];
    }
}
