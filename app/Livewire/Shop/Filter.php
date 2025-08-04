<?php

namespace App\Livewire\Shop;

use App\Models\Option;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Filter extends Component
{
    use WithPagination;

    public $family_id;
    public $category_id;
    public $subcategory_id;

    public $options = [];
    public $select_features = [];
    public $orderBy = 1;
    public $search = '';

    public function mount()
    {
        $this->loadOptions();
    }

    protected function loadOptions()
    {
        $query = Option::query();

        if ($this->family_id) {
            $query->verifyFamily($this->family_id);
        }

        if ($this->category_id) {
            $query->verifyCategory($this->category_id);
        }

        $this->options = $query->get()->toArray();
    }

    #[On('search')]
    public function search($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    public function updatedOrderBy()
    {
        $this->resetPage();
    }

    public function updatedSelectFeatures()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->verifyProduct($this->family_id)
            ->verifyCategory($this->category_id)
            ->customOrder($this->orderBy)
            ->selectFeatures($this->select_features)
            ->search($this->search)
            ->with(['variants.images']) // Asegura que las imágenes estén cargadas
            ->paginate(12);

        return view('livewire.shop.filter', compact('products'));
    }
}
