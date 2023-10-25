<?php

namespace App\Http\Livewire\App;

use App\Events\TaxEvent;
use App\Http\Livewire\CrudComponent;

use App\Models\Tax;

class TaxComponent extends CrudComponent
{
    protected $listeners =  ["socket" => "socketHandler"];
    public function mount()
    {
        $this->setup(Tax::class, TaxEvent::class, ["name", "percentage"], [
            "name" => "",
            "percentage" => 0,
        ]);
        $this->items = $this->Model::all();
    }
}
