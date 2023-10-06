<?php

namespace App\Http\Livewire;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewTenantComponent extends Component
{
    public $tenant_name;

    public function render()
    {
        return view('livewire.new-tenant-component');
    }

    public function register()
    {
        $this->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
        ]);

        $tenant = Tenant::create([
            "name" => $this->tenant_name,
        ]);

        $user = Auth::user();
        $user->tenant_id = $tenant->id;
        $user->tenant_role = "admin";

        $user->save();

        return redirect("/tenants/manage");
    }
}
