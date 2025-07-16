<?php

namespace App\Livewire\Forms\Admin\Options;

use App\Models\Option;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateOptionForm extends Form
{
    
    public $openModal = false;
    
    public $name;
    public $type = '';
    public $features = [
        [
            'value' => '',
            'description' => ''
        ]
    ];

    public function updatedNewOptionType()
    {
        foreach ($this->features as $key => $feature) {
            $this->features[$key]['value'] = '';
        }
    }

    public function addFeature()
    {
        $this->features[] = [
            'value' => '',
            'description' => ''
        ];
    }

    public function removeFeature($index)
    {
        if ($index != 0) {
            unset($this->features[$index]);
            $this->features = array_values($this->features);
        }
    }

    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                'unique:options,name',
                'string',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'between:3,60',
            ],
            'type' => 'required|in:1,2',
            'features.*.value' => 'required',
        ];

        foreach ($this->features as $index => $feature) {
            if ($this->type == 1) {
                //referencia el texto
                $rules['features.' . $index . '.value'] = 'required|string|between:1,50|unique:features,value|distinct:strict';
            } else {
                //referencia el color
                $rules['features.' . $index . '.value'] = 'required|hex_color|unique:features,value|distinct:strict';
            }
            $rules['features.' . $index . '.description'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];
        foreach ($this->features as $index => $feature) {
            $messages['features.' . $index . '.value.required'] = "El valor del campo " . ($index + 1) . ", es obligatorio";
            $messages['features.' . $index . '.value.unique'] = "El valor del campo " . ($index + 1) . ", ya ha sido registrado";
            $messages['features.' . $index . '.description.required'] = "La descripción del campo " . ($index + 1) . ", es obligatorio";
            $messages['features.' . $index . '.value.distinct'] = "El valor del campo " . ($index + 1) . ", continene un valor duplicado";
        }

        return $messages;
    }

    public function save()
    {
        $this->validate();
        
        DB::beginTransaction();
        try {
            $option = Option::create([
                'name' => $this->name,
                'type' => $this->type,
            ]);

            foreach ($this->features as $feature) {
                $option->features()->create([
                    'value' => $feature['value'],
                    'description' => $feature['description'],
                ]);
            }

            DB::commit();

            $this->reset();

        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
