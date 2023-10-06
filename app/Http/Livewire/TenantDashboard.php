<?php

namespace App\Http\Livewire;

use App\Models\SaleDescription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TenantDashboard extends Component
{
    public $tenant, $sales;

    public function render()
    {
        $this->tenant = Auth::user()->tenant;
        $this->sales = SaleDescription::where("tenant_id", $this->tenant->id)->get();

        return view('livewire.tenant-dashboard');
    }
}
