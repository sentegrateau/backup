<?php

namespace App\Wallet\Traits;

use App\Models\Wallet as WalletModel;
use App\Wallet\Interfaces\Balance;

trait Wallet
{
    public function deposit($amount, $slug)
    {
        return app(Balance::class)->incBalance($this, $amount, $slug, 'deposit');
    }

    public function withdraw($amount, $slug)
    {
        try {
            return app(Balance::class)->decBalance($this, $amount, $slug, 'withdraw');
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function getBalanceAttribute()
    {
        return app(Balance::class)->getBalance($this);
    }

    public function createWallet()
    {
        $wallet = WalletModel::firstOrNew(['holder_id' => $this->id]);
        if (empty($wallet->id)) {
            $wallet->holder_id = $this->id;
            $wallet->balance = 0;
            $wallet->save();
        }
        return $wallet;
    }
}

