<?php

namespace App\Http\Livewire\User;

use App\Models\Sale;
use App\Utils\PaymentMethods;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchasesComponent extends Component
{
    public $user;
    public $purchases;

    public $methods;

    public function mount()
    {
        $this->methods = PaymentMethods::get();
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
}
