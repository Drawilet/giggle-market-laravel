<?php

namespace App\Utils;

use App\Models\Transaction;
use Livewire\Component;

class ComponentWithTransactions extends Component
{

    public $transactions = [];

    static  function getPayerData($transaction, $type, $id)
    {
        if ($transaction->payer_type == 'system') {
            return [
                'name' => $transaction->payer_name,
                'type' => $transaction->payer_type,
                "is_mine" => false
            ];
        }

        return [
            'name' => $transaction->payer->name,
            'type' => substr($transaction->payer->getTable(), 0, -1),
            "is_mine" => ($transaction->payer_id == $id) && ($transaction->payer_type == $type)
        ];
    }

    static function getRecipientData($transaction, $type, $id)
    {
        if ($transaction->recipient_type == 'system') {
            return [
                'name' => $transaction->recipient_name,
                'type' => $transaction->recipient_type,
                "is_mine" => false
            ];
        }

        return [
            'name' => $transaction->recipient->name,
            'type' => substr($transaction->recipient->getTable(), 0, -1),
            "is_mine" => ($transaction->recipient_id == $id) && ($transaction->recipient_type == $type)
        ];
    }

    public function getTransactions($type, $id)
    {
        $this->transactions =  Transaction::where(function ($query) use ($type, $id) {
            $query->where(function ($subquery) use ($type, $id) {
                $subquery->where('payer_id', $id)
                    ->where('payer_type', $type);
            })->orWhere(function ($subquery) use ($type, $id) {
                $subquery->where('recipient_id', $id)
                    ->where('recipient_type', $type);
            });
        })->get();
    }
}
