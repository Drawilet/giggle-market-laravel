<?php

namespace App\Http\Livewire;

use App\Http\Controllers\PayPalController;
use App\Models\Transaction;
use App\Models\User;
use App\Utils\ComponentWithTransactions;
use Illuminate\Support\Facades\Auth;

class DashboardComponent extends ComponentWithTransactions
{
    public  $transactions, $user;

    public $amount, $method;
    public $withdrawModal = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->getTransactions(User::class, $this->user->id);
    }
    public function render()
    {
        return view('livewire.dashboard-component');
    }

    public function cleanWithdrawModal()
    {
        $this->method = null;
        $this->amount = null;
    }

    public function openWithdrawModal()
    {
        $this->cleanWithdrawModal();

        $this->withdrawModal = true;
    }
    public function closeWithdrawModal()
    {
        $this->cleanWithdrawModal();
        $this->withdrawModal = false;
    }


    public function withdraw()
    {
        $balance = $this->user->balance;

        $this->validate([
            "method" => "required|string",
            "amount" => "required|numeric|max:$balance",
        ]);
        $this->amount = floatval($this->amount);

        switch ($this->method) {
            case 'paypal':
                $paypalController  = new PayPalController();
                $paypalController->createPayout($this->user->paypal_email, $this->amount);
                break;

            default:
                # code...
                break;
        }
    }
}
