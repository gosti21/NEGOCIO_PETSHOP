<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Family;
use App\Traits\Admin\sweetAlerts;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SubcategoryEdit extends Component
{
    use sweetAlerts;

    public $data;
    public $families;

    public $family_id = '';
    public $category_id = '';
    public $name = '';

    protected $listeners = ['save' => 'save'];

    /**
     * Se ejectura ni bien se cargue el componente  
     */
    public function mount($data)
    {
        $this->families = Family::all();

        $this->family_id = $data->category->family_id;
        $this->category_id = $data->category_id;
        $this->name = $data->name;
    }

    /**
     * Ciclo de vida del componente
     */
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

        $this->data->update([
            'family_id'   => $this->family_id,
            'category_id' => $this->category_id,
            'name'        => $this->name,
        ]);

        $this->dispatch('subcategoryUpdated', $this->name);

        $this->alertGenerate2([
            'title' => '¡Registro actualizado!',
            'text' => "El registro ha sido actualizado correctamente",
        ]);
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
                    'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                    'between:3,60',
                    Rule::unique('sub_categories', 'name')
                        ->where(fn($query) => $query->where('category_id', $this->category_id))
                        ->ignore($this->data->id)
                ],
            ],
            [
                'name.regex' => 'El campo nombre solo puede contener letras y espacios.',
                'name.unique' => 'El nombre ya está relacionado con esta categoria.'
            ],
            [
                'family_id' => 'familia',
                'category_id' => 'categoria',
                'name' => 'nombre',
            ]
        );
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-edit');
    }
}
