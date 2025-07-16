<?php

namespace App\Http\Requests\Admin\ShippingCompany;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippingCompanyRequest extends FormRequest
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
        $shippingCompanyId = $this->route('shipping_company');
        $phoneId = \App\Models\Phone::where('phoneable_id', $shippingCompanyId)
            ->where('phoneable_type', \App\Models\ShippingCompany::class)
            ->first();

        return [
            'name' => [
                'required',
                'string',
                'between:3,80',
                Rule::unique('shipping_companies', 'name')->ignore($shippingCompanyId),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('shipping_companies', 'email')->ignore($shippingCompanyId),
            ],
            'phone' => [
                'required',
                'integer',
                'digits:9',
                Rule::unique('phones', 'number')->ignore($phoneId)
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
