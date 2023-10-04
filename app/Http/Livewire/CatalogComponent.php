<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CatalogComponent extends Component
{

    protected $listeners = ["cart:update" => "render"];

    public $user;

    public $products, $tenants;
    public $orderedProducts = [];

    public $successModal = false;

    public function render()
    {
        $this->user = Auth::user();

        $this->tenants = Tenant::all();
        $this->products = Product::all();

        $this->orderedProducts = $this->products->groupBy("tenant_id")->toArray();

        return view('livewire.catalog-component');
    }

    public function addProduct($id)
    {
        $product = $this->products->where("id", $id)->first();
        if ($this->getStock($product) == 0) return;

        $cart = Cart::where("user_id", $this->user->id)
            ->where("product_id", $id)
            ->first() ?? Cart::create(
                [
                    "user_id" => $this->user->id,
                    "product_id" => $id,
                    "quantity" => 0
                ]
            );

        $cart->quantity++;
        $cart->save();

        $this->emit("cart:update", $id);

        $this->successModal = true;
    }

    public function getStock($product)
    {
        $cart = $this->user->user_carts->where("product_id", $product["id"])->first();
        if ($cart) {
            $stock = $product["stock"] - $cart->quantity;
            return $stock;
        }

        return $product["stock"];
    }

    public function closeSuccessModal()
    {
        $this->successModal = false;
    }
}
