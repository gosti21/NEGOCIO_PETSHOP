<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Rules\Admin\OptionProductFeatureExists;
use App\Traits\Admin\generateCombinations;
use Livewire\Component;

class ProductAddNewVariants extends Component
{
    use generateCombinations;

    public $option;
    public $product;

    public $variant = [
        'option_id' => '',
        'features' => [
            'id' => '',
            'value' => '',
            'description' => '',
        ]
    ];

    public function save()
    {
        $this->validateData();

        $this->addVariant();

        $this->updateOptionProductFeatures();

        $this->dispatch('variantOptionProductAdd');
    }

    public function addVariant()
    {
        $featureId = $this->variant['features']['id'];
        $feature = Feature::findOrFail($featureId);
        if ($feature) {
            $this->variant['option_id'] = $this->option->id;
            $this->variant['features']['value'] = $feature->value;
            $this->variant['features']['description'] = $feature->description;
        }
    }

    public function validateData()
    {
        $this->validate([
            'variant.features.id' => [
                'required',
                new OptionProductFeatureExists($this->option->id, $this->product->id),
            ],
        ],[],
        [
            'variant.features.id' => 'valor'
        ]
        );
    }

    public function updateOptionProductFeatures()
    {
        $currentFeatures = $this->product->options->find($this->option->id)->pivot->features;
        
        $updatedFeatures = array_merge($currentFeatures, [
            [
                'id' => $this->variant['features']['id'],
                'value' => $this->variant['features']['value'],
                'description' => $this->variant['features']['description'],
            ]
        ]);

        $this->product->options()->updateExistingPivot($this->option->id, [
            'features' => $updatedFeatures,
        ]);

        $this->reset('variant');
    }

    public function render()
    {
        return view('livewire.admin.products.product-add-new-variants');
    }
}
