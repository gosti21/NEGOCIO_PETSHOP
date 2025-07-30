<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Traits\Admin\skuGenerator;
use App\Traits\Admin\sweetAlerts;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;
    use skuGenerator;
    use sweetAlerts;

    public $families;
    public $family_id = '';
    public $category_id = '';
    public $name = '';
    public $description = '';

    public function mount()
    {
        $this->families = Family::all();
    }

    public function boot()
    {
        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $this->alertGenerate2([
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' => "El formulario contiene errores",
                ]);
            }
        });
    }

    public function updatedFamilyId()
    {
        $this->reset('category_id');
    }

    #[Computed()]
    public function categories()
    {
        return Category::where('family_id', $this->family_id)->get();
    }

    public function save()
    {
        $this->validateData();

        $sku = $this->generateSku($this->category_id, $this->name);

        $data = Product::create([
            'name' => $this->name,
            'sku' => $sku,
            'description' => $this->description,
            'category_id' => $this->category_id,
        ]);

        $this->alertGenerate1();

        return redirect()->route('admin.products.show', $data);
    }

    public function validateData()
    {
        $this->validate(
            [
                'family_id' => 'required|exists:families,id',
                'category_id' => 'required|exists:categories,id',
                'name' => [
                    'required',
                    'string',
                    'between:3,80',
                    Rule::unique('products', 'name')->where(
                        fn(Builder $query) => $query->where('category_id', $this->category_id)
                    )
                ],
                'description' => 'required|string',
            ],
            [
                'name.regex' => 'El campo nombre solo puede contener letras y espacios.',
                'name.unique' => 'El nombre ya está relacionado con esta categoría.',
            ],
            [
                'category_id' => 'categoría',
            ]
        );
    }

    public function render()
    {
        return view('livewire.admin.products.product-create');
    }
}
