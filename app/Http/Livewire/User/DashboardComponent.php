<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use App\Utils\ComponentWithTransactions;
use App\Utils\PaymentMethods;
use Illuminate\Support\Facades\Auth;

class DashboardComponent extends ComponentWithTransactions
{
    public $type = User::class;
    public $user;

    public $amount, $method;
    public $withdrawModal = false;

    public $methods = [];
    public function mount()
    {
        $this->methods = PaymentMethods::get();
        $this->user = Auth::user();
        $this->getTransactions(User::class, $this->user->id);
    }
    public function render()
    {
        return view('livewire.user.dashboard-component');
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

        $Method = $this->methods[$this->method];
        $controller = new $Method["controller"];

        $controller->createPayout($this->user, $this->amount);

        $this->closeWithdrawModal();
        $this->getTransactions(User::class, $this->user->id);
    }
}
