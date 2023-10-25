<?php

namespace App\Http\Livewire\App;

use App\Events\CategoryEvent;
use App\Http\Livewire\CrudComponent;
use App\Models\Category;

class CategoryComponent extends CrudComponent
{
    protected $listeners =  ["socket" => "socketHandler"];
    public function mount()
    {
        $this->setup(Category::class, CategoryEvent::class, ["name"], [
            "name" => "",
        ]);
        $this->items = $this->Model::all();
    }
}
