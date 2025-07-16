<?php

namespace App\Http\Requests\Admin\Cover;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCoverRequest extends FormRequest
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
        $coverId = $this->route('cover');

        return [
            'title' => [
                'required',
                'string',
                'between:3,80',
                Rule::unique('covers', 'title')
                    ->where(fn($query) => $query->where('title', $this->title))
                    ->ignore($coverId)
            ],
            'start_at' => 'required|date_format:Y-m-d',
            'end_at' => 'nullable|date_format:Y-m-d|after_or_equal:start_at',
            'image' => 'nullable|image|max:1024',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * prepara los datos del request antes de validarlo
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('start_at')) { // Verifica que no sea null ni una cadena vacía
            $startAt = $this->input('start_at');

            if (Carbon::hasFormat($startAt, 'd-m-Y')) {
                $this->merge([
                    'start_at' => Carbon::createFromFormat('d-m-Y', $startAt)->format('Y-m-d'),
                ]);
            }
        }

        if ($this->filled('end_at')) { // Verifica que no sea null ni una cadena vacía
            $endAt = $this->input('end_at');

            if (Carbon::hasFormat($endAt, 'd-m-Y')) {
                $this->merge([
                    'end_at' => Carbon::createFromFormat('d-m-Y', $endAt)->format('Y-m-d'),
                ]);
            }
        } else {
            $this->merge([
                'end_at' => null, // Si está vacío, asegúrate de que se envía como null
            ]);
        }
    }
}
