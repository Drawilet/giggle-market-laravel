<?php

namespace App\Http\Livewire;

use App\Http\Controllers\TransactionController;
use App\Models\SaleDescription;
use App\Models\Store;
use App\Models\User;
use App\Utils\ComponentWithTransactions;
use Illuminate\Support\Facades\Auth;

class StoreDashboard extends ComponentWithTransactions
{
    public $type = Store::class;

    public $store, $sales, $users, $transactions;

    public $user_id, $amount, $description;
    public $transferModal = false;

    public function render()
    {
        return view('livewire.store-dashboard');
    }

    public function mount()
    {
        $this->store = Auth::user()->store;
        $this->sales = SaleDescription::where("store_id", $this->store->id)->get();
        $this->users = User::where("store_id", $this->store->id)->get();
        $this->getTransactions(Store::class,  $this->store->id);
    }



    public function cleanTransferModal()
    {
        $this->user_id = null;
        $this->description = null;
        $this->amount = null;
    }

    public function openTransferModal()
    {
        $this->cleanTransferModal();

        $this->transferModal = true;
    }
    public function closeTransferModal()
    {
        $this->cleanTransferModal();
        $this->transferModal = false;
    }

    public function transfer()
    {
        $balance = $this->store->balance;

        $this->validate([
            "user_id" => "required",
            "amount" => "required|numeric|max:$balance",
            "description" => "required"
        ]);
        $this->amount = floatval($this->amount);

        $transaction = new TransactionController();

        $transaction->setPayer($this->store);
        $transaction->setRecepient($this->user_id, "user");
        $transaction->setAmount($this->amount);
        $transaction->setDescription($this->description);

        $transaction->execute();

        $this->closeTransferModal();
        $this->getTransactions(Store::class,  $this->store->id);
    }
}
