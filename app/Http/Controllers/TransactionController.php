<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    private $payer, $payer_type, $recipient, $amount, $description;

    public function setPayer($payer, $type = null)
    {
        if ($type == "system") {
            $this->payer = $payer;
            $this->payer_type = "system";
            return;
        }

        if (gettype($payer) == "string" && $type) {
            if ($type == "tenant") $this->payer = Tenant::where("id", "$payer")->first();
            elseif ($type == "user") $this->payer = User::where("id", "$payer")->first();
        } else $this->payer = $payer;
    }

    public function setRecepient($recipient, $type = null)
    {
        if (gettype($recipient) == "string" && $type) {
            if ($type == "tenant") $this->recipient = Tenant::where("id", "$recipient")->first();
            elseif ($type == "user") $this->recipient = User::where("id", "$recipient")->first();
        } else $this->recipient = $recipient;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function execute()
    {
        $data = [
            "recipient_id" => $this->recipient->id,
            "recipient_type" => $this->recipient::class,

            "amount" => $this->amount,
            "description" => $this->description
        ];

        if ($this->payer_type == "system") {
            $data["payer_name"] = $this->payer;
            $data["payer_type"] = "system";
        } else {
            if ($this->payer->balance < $this->amount)
                return   abort(403, 'Insufficient balance to perform this transaction');

            $this->payer->balance -= $this->amount;
            $this->payer->save();

            $data["payer_id"] = $this->payer->id;
            $data["payer_type"] = $this->payer::class;
        }

        $this->recipient->balance += $this->amount;
        $this->recipient->save();

        Transaction::create($data);
    }
}
