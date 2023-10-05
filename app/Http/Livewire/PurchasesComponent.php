<?php

namespace App\Http\Livewire;

use App\Http\Controllers\PayPalController;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchasesComponent extends Component
{
    public $user;
    public $purchases;
    public function render()
    {
        $this->user = Auth::user();
        $this->purchases = Sale::where("user_id", $this->user->id)->get();

        return view('livewire.purchases-component');
    }


    public function payAgain($method, $paymentId)
    {
        switch ($method) {
            case 'paypal':
                $paypalController = new PayPalController();
                return $paypalController->payAgain($paymentId);

            default:
                break;
        }
    }
}
