<?php

namespace App\Http\Livewire\App;

use App\Models\User;
use Livewire\Component;

class ManageComponent extends Component
{
    public function mount()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.app.manage-component');
    }
}
