<?php

namespace App\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BillingComponent extends Component
{
    public $user;
    public $paypal_email;

    public function mount()
    {
        $this->user = Auth::user();
        $this->paypal_email = $this->user->paypal_email;
    }

    public function render()
    {
        return view('livewire.user.billing-component');
    }

    public function updatePayPalInformation()
    {
        $this->validate([
            'paypal_email' => ['required', "email"],
        ]);


        $this->user->paypal_email = $this->paypal_email;
        $this->user->save();
    }
}
