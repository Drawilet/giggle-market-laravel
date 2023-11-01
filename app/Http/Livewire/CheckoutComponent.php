<?php

namespace App\Http\Livewire;

use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\PayPalController;
use App\Models\Cart;
use App\Models\Sale;
use App\Models\SaleDescription;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Utils\PaymentMethods;

class CheckoutComponent extends Component
{
    public $cart = [];

    public $methods;

    public function mount(Request $request)
    {
        $this->methods = PaymentMethods::get();

        $items = Auth::user()->user_carts;

        $item = $request->input("item");
        if ($item)  $this->cart = $items->where("id", $item);
        else  $this->cart = $items;

        foreach ($items as $item) {
            if ($item->product->unpublished) {
                $item->delete();
                continue;
            }

            $calc = $item->product->stock - $item->quantity;
            if ($calc < 0) {
                $item->quantity -= abs($calc);
                $item->save();
            }
        }
    }

    public function render()
    {
        if ($this->cart->isEmpty()) redirect("/dashboard");

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
    public function getTaxes(Cart $item)
    {
        $taxes = 0;

        foreach ($item->product->product_taxes as $product_tax) {
            $taxes += $this->getTax($item, $product_tax->tax);
        }

        return $taxes;
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

    /*<──  ───────    CHECKOUT   ───────  ──>*/
    public function checkout($payment_method)
    {
        $amount = $this->getAmount();

        $sale = Sale::create(
            [
                "user_id" => Auth::user()->id,
                "amount" => $amount,

                "payment_method" => $payment_method,
                "payment_status" => "pending",
            ],
        );

        foreach ($this->cart as $item) {
            SaleDescription::create([
                "sale_id" => $sale->id,
                "store_id" => $item->product->store->id,
                "product_id" => $item->product->id,

                "quantity" => $item->quantity,
                "price" => $item->product->price + $this->getTaxes($item),
            ]);

            $item->product->stock -= $item->quantity;
            $item->product->save();

            $item->delete();
        }

        $method = $this->methods[$payment_method];
        $controller = new $method["controller"];

        $controller->createPayment($sale);
    }
}
