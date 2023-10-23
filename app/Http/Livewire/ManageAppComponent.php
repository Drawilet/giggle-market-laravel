<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class ManageAppComponent extends Component
{
    public function mount()
    {
        $this->users = User::all();
    }

    public function render()
    {

        return view('livewire.manage-app-component');
    }
}
