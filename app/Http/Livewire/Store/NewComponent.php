<?php

namespace App\Http\Livewire\Store;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewComponent extends Component
{
    public $store_name;

    public function render()
    {
        return view('livewire.store.new-component');
    }

    public function register()
    {
        $this->validate([
            'store_name' => ['required', 'string', 'max:255'],
        ]);

        $store = Store::create([
            "name" => $this->store_name,
        ]);

        $user = Auth::user();
        $user->store_id = $store->id;
        $user->store_role = "admin";

        $user->save();

        return redirect("/store/manage");
    }
}
