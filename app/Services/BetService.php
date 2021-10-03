<?php

namespace App\Services;

use Illuminate\Database\QueryException;

class BetService
{
    public function createBet($player, $data)
    {
        try {
            $bet = $player->bets()->create([
                'stake_amount' => $data['stake_amount']
            ]);
            foreach ($data['selections'] as $selection) {
                $bet->betSelections()->create([
                    'selection_id' => $selection['id'],
                    'odds' => $selection['odds']
                ]);
            }
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }
}
