<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $search;
    public $selectBy = 1;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::selectRole($this->selectBy)
        ->search($this->search)
        ->paginate();

        return view('livewire.admin.users.user-index', compact('users'));
    }
}
