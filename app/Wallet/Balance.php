<?php

namespace App\Wallet;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Wallet\Interfaces\Balance as BalanceI;

class Balance implements BalanceI
{
    public function getBalance($object)
    {
        $wallet = Wallet::where('holder_id', $object->id)->first();
        if (!empty($wallet)) {
            return $wallet->balance;
        }
        return 0;
    }

    public function incBalance($object, $amount, $slug, $type)
    {
        return $this->setBalance($object, $amount, $slug, $type);
    }

    public function decBalance($object, $amount, $slug, $type)
    {

            $balance = $object->balance;
            if ($balance < $amount) {
                throw new \Exception('Insufficient Balance');
            } else {
                return $this->setBalance($object, $amount, $slug, $type);
            }

    }

    public function setBalance($object, $amount, $slug, $type)
    {

        $wallet = Wallet::where('holder_id', $object->id)->first();
        if ($type == 'deposit') {
            $wallet->increment('balance', $amount);
        } else {
            $wallet->decrement('balance', $amount);
        }
        $txn = new WalletTransaction();
        $txn->holder_id = $object->id;
        $txn->wallet_id = $wallet->id;
        $txn->amount = ($type == 'deposit') ? $amount : -$amount;
        $txn->type = $type;
        $txn->description = $slug;
        $txn->save();
        return true;
    }
}
