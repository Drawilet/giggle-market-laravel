<?php

namespace App\Http\Livewire;

use App\Http\Controllers\TransactionController;
use App\Models\SaleDescription;
use App\Models\Tenant;
use App\Models\User;
use App\Utils\ComponentWithTransactions;
use Illuminate\Support\Facades\Auth;

class TenantDashboard extends ComponentWithTransactions
{
    public $type = Tenant::class;

    public $tenant, $sales, $users, $transactions;

    public $user_id, $amount, $description;
    public $transferModal = false;

    public function render()
    {
        return view('livewire.tenant-dashboard');
    }

    public function mount()
    {
        $this->tenant = Auth::user()->tenant;
        $this->sales = SaleDescription::where("tenant_id", $this->tenant->id)->get();
        $this->users = User::where("tenant_id", $this->tenant->id)->get();
        $this->getTransactions(Tenant::class,  $this->tenant->id);
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
        $balance = $this->tenant->balance;

        $this->validate([
            "user_id" => "required",
            "amount" => "required|numeric|max:$balance",
            "description" => "required"
        ]);
        $this->amount = floatval($this->amount);

        $transaction = new TransactionController();

        $transaction->setPayer($this->tenant);
        $transaction->setRecepient($this->user_id, "user");
        $transaction->setAmount($this->amount);
        $transaction->setDescription($this->description);

        $transaction->execute();

        $this->closeTransferModal();
        $this->getTransactions(Tenant::class,  $this->tenant->id);
    }
}
