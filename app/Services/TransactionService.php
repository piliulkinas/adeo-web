<?php

namespace App\Services;

use Illuminate\Database\QueryException;

class TransactionService
{
    public function transactionValid($amount, $stake_amount)
    {
        return $amount >= intval($stake_amount);
    }

    public function createTransaction($player, $amount)
    {
        try {
            $player->balanceTransactions()->create([
                'amount' => $amount,
                'amount_before' => $player->balance,
            ]);
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }
}
