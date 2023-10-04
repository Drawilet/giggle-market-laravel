<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use App\Models\SaleDescription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SalesComponent extends Component
{
    public $user;
    public $sales;
    public function render()
    {
        $this->user = Auth::user();
        $this->sales = SaleDescription::where("tenant_id", $this->user->tenant_id)->get();

        return view('livewire.sales-component');
    }
}
