<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Livewire\Component;

class HomeComponent extends Component
{
    public $products, $categories, $stores;

    public function mount()
    {
        $this->products = Product::where("status", "available")->get()->groupBy("category_id")->toArray();
        $this->categories = Category::all();
        $this->stores = Store::all();
    }
    public function render()
    {
        return view('livewire.home-component');
    }
}
