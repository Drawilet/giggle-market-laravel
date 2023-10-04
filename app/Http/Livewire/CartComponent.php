<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartComponent extends Component
{

    public $totalQuantity = 0,  $cart = [];

    protected $listeners = ["cart:update" => "render"];

    public function render()
    {
        $user = Auth::user();
        $this->cart = $user->user_carts;
        $this->totalQuantity = array_sum(array_column($this->cart->toArray(), "quantity"));

        return view('livewire.cart-component');
    }

    /**
     * Actions: increase | decrease | remove
     */
    public function handleItem($item, $action)
    {
        $cart = Cart::where("id", $item["id"])->first();
        if (!$cart) return;

        switch ($action) {
            case 'increase':
                if ($item["product"]["stock"] == $item["quantity"]) return;

                $cart->quantity++;
                break;

            case 'decrease':
                $cart->quantity--;

                if ($cart->quantity == 0) return $this->handleItem($item, "remove");
                break;


            case 'remove':
                return $cart->delete();

            default:
                # code...
                break;
        }

        $cart->save();
        $this->emit("cart:update");
    }

    public function buy($arg)
    {
        $this->redirect("/checkout?arg=$arg");
    }
}
