<?php

namespace App\Livewire\Shop;

use App\Models\Category;
use App\Models\Family;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Navigation extends Component
{
    public $families;
    public $family_id;

    public function mount()
    {
        $this->families = Family::all();

        // ✅ Corrección segura: evita acceder a null
        $first = $this->families->first();
        $this->family_id = $first ? $first->id : null;
    }

    #[Computed()]
    public function categories()
    {
        // ✅ Si no hay familia seleccionada, devolver colección vacía
        if (!$this->family_id) {
            return collect();
        }

        return Category::where('family_id', $this->family_id)
            ->with('subCategories')
            ->get();
    }

    #[Computed()]
    public function familyName()
    {
        $family = Family::find($this->family_id);
        return $family ? $family->name : 'Sin familia';
    }

    public function render()
    {
        return view('livewire.shop.navigation');
    }
}
