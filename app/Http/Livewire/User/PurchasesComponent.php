<?php

namespace App\Http\Livewire\User;

use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Utils\PaymentMethods;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchasesComponent extends Component
{
    public $user;
    public $purchases;
    public $methods;


    public $initialData = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "category_id" => null,
        "stock" => null,

        "comment" => null,
        "rating" => null,
    ];
    public $data;

    public $modals = [
        "review" => false
    ];

    public function mount()
    {
        $this->methods = PaymentMethods::get();
        $this->data = $this->initialData;
    }

    public function render()
    {
        $this->user = Auth::user();
        $this->purchases = Sale::where("user_id", $this->user->id)->get();

        return view('livewire.user.purchases-component');
    }


    public function payAgain($payment_method, $paymentId)

    {
        $method = $this->methods[$payment_method];
        $controller = new $method["controller"];

        $controller->payAgain($paymentId);
    }

    public function review()
    {
        $this->validate([
            "data.comment" => "required",
            "data.rating" => "required",
        ]);

        Review::create([
            "user_id" => $this->user->id,
            "product_id" => $this->data["id"],
            "comment" => $this->data["comment"],
            "rating" => $this->data["rating"],
        ]);
    }

    /*<──  ───────    UTILS   ───────  ──>*/
    public function clean()
    {
        $this->data = $this->initialData;
    }


    public function Modal($modal, $value, $id = null)
    {
        if ($value == true) {
            $this->clean();

            $product = Product::find($id);
            $this->data = $product->toArray();
            $this->data["rating"] = .5;
        }
        $this->modals[$modal] = $value;
    }
}
