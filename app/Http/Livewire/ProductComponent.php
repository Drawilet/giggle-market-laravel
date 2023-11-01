<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductComponent extends Component
{

    public $user, $product, $stock;

    public $quantity = 1, $value;

    protected $listeners = ["cart:update" => "getStock"];

    public function mount($id)
    {
        $this->product = Product::where("id", $id)->where("status", "available")->first();
        $this->getStock();
    }

    public function render()
    {
        return view('livewire.product-component');
    }

    public function getStock()
    {
        $user = Auth::user();
        $cart = $user->user_carts->where("product_id", $this->product["id"])->first();
        if ($cart) {
            $this->stock = $this->product["stock"] - $cart->quantity;
            return;
        }

        $this->stock = $this->product["stock"];
    }

    public function updatedValue($value)
    {
        if ($value != "custom") $this->quantity = $value;
    }


    public function addProduct()
    {
        if ($this->stock == 0) return;

        $user = Auth::user();

        $cart = Cart::where("user_id", $user->id)
            ->where("product_id", $this->product->id)
            ->first() ?? Cart::create(
                [
                    "user_id" => $user->id,
                    "product_id" =>  $this->product->id,
                    "quantity" => 0
                ]
            );

        $cart->quantity += $this->quantity;
        $cart->save();

        $this->emit("cart:update", $this->product->id);

        $this->quantity = 1;
        $this->value = 1;
        $this->getStock();
    }
}
