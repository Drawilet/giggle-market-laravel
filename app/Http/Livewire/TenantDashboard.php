<?php

namespace App\Http\Livewire;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionUtilsController;
use App\Models\SaleDescription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use App\Utils\TransactionUtils;

class TenantDashboard extends Component
{
    public $tenant, $sales, $users, $transactions;

    public $user_id, $amount, $description;
    public $transferModal = false;

    static  function getPayerData($transaction)
    {
        if ($transaction->payer_type == 'system') {
            return [
                'name' => $transaction->payer_name,
                'type' => $transaction->payer_type,
            ];
        }

        return [
            'name' => $transaction->payer->name,
            'type' => substr($transaction->payer->getTable(), 0, -1),
        ];
    }

    public function render()
    {
        return view('livewire.tenant-dashboard');
    }

    public function mount()
    {
        $this->tenant = Auth::user()->tenant;
        $this->sales = SaleDescription::where("tenant_id", $this->tenant->id)->get();
        $this->users = User::where("tenant_id", $this->tenant->id)->get();
        $this->getTransactions();
    }

    public function getTransactions()
    {
        $this->transactions = Transaction::where('payer_id', $this->tenant->id)
            ->orWhere('recipient_id', $this->tenant->id)->get();
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
        $this->getTransactions();
    }
}
