<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Sale;
use App\Models\SaleDescription;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutComponent extends Component
{
    public $cart = [];

    public function mount(Request $request)
    {
        $items = Auth::user()->user_carts;

        $arg = $request->input("arg");
        if ($arg == "all") $this->cart = $items;
        else  $this->cart = $items->where("id", $arg);
    }

    public function render()
    {
        return view('livewire.checkout-component');
    }

    public function getPrice(Cart $item)
    {
        return  $item->quantity * $item->product->price;
    }

    public function getTax(Cart $item, Tax $tax)
    {
        return $this->getPrice($item) * ($tax->percentage / 100);
    }

    public function getAmount()
    {
        $amount = 0;
        foreach ($this->cart as $item) {
            $amount += $this->getPrice($item);

            foreach ($item->product->product_taxes as $product_tax) {
                $amount += $this->getTax($item, $product_tax->tax);
            }
        }

        return $amount;
    }

    public function checkout()
    {
        $sale = Sale::create(["user_id" => Auth::user()->id, "amount" => $this->getAmount()],);

        foreach ($this->cart as $item) {
            SaleDescription::create([
                "sale_id" => $sale->id,

                "tenant_id"=> $item->product->tenant->id,
                "tenant_name" => $item->product->tenant->name,

                "description" => $item->product->description,
                "quantity" => $item->quantity,
                "price" => $item->product->price,

                // TODO: Add taxes
                "amount" => $this->getPrice($item),
            ]);

            $item->product->stock -= $item->quantity;
            $item->product->save();

            $item->delete();
        }

        $this->redirect("/catalog");
    }
}
