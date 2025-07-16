<?php

namespace App\Livewire\Admin\Options;

use Livewire\Component;

class AddNewFeatures extends Component
{
    public $option;

    public $newFeature = [
        'value' => '',
        'description' => '',
    ];

    public function addFeature()
    {
        $this->validateData();

        $this->option->features()->create($this->newFeature);

        $this->dispatch('featureAdded');

        $this->reset('newFeature');
    }

    public function validateData()
    {
        $rules = [
            'newFeature.value' => 'required',
            'newFeature.description' => 'required|string|max:255'
        ];
        if ($this->option->type == 1) {
            $rules['newFeature.value'] = 'required|string|between:1,50|unique:features,value';
        } else {
            $rules['newFeature.value'] = 'required|hex_color|unique:features,value';
        }
        $this->validate(
            $rules,
            [],
            [
                'newFeature.value' => 'valor',
                'newFeature.description' => 'descripción'
            ]
        );
    }

    public function render()
    {
        return view('livewire.admin.options.add-new-features');
    }
}
