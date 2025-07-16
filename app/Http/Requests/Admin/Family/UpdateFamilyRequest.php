<?php

namespace App\Http\Requests\Admin\Family;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFamilyRequest extends FormRequest
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
        $familyId = $this->route('family');

        return [
            'name' => [
                'required', 
                'string', 
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/', 
                'between:3,60', 
                Rule::unique('families', 'name')->ignore($familyId),
            ]
        ];
    }
}
