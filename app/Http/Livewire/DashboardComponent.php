<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $user;

    public function render()
    {
        $this->user = Auth::user();

        return view('livewire.dashboard-component');
    }
}
