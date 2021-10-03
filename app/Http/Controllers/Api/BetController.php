<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Http\Requests\Api\StoreBetRequest;

class BetController extends Controller
{
    public function __construct()
    {
        $this->betService = new BetService();
        $this->transactionService = new TransactionService();
    }

    public function store(StoreBetRequest $request)
    {
        $validated = $request->validated();

        $player = Player::find($validated['player_id']);
        if (!$player) {                                                                                    // Create new player if player_id is undefined
            $player = Player::create([
                'player_id' => $validated['player_id']
            ]);
        }
        if (!$this->transactionService->transactionValid($player->balance, $validated['stake_amount'])) {  // Check if player balance is valid to bet
            return response(['errors' => ['code' => 11, 'message' => 'Insufficient balance']], 405);
        }
        if (!$this->betService->createBet($player, $validated)) {                                          // Create new player bet
            return response(['errors' => ['code' => 0, 'message' => 'Unknown error']], 500);
        }
        if (!$this->transactionService->createTransaction($player, $validated['stake_amount'])) {         // Create new transaction
            return response(['errors' => ['code' => 0, 'message' => 'Unknown error']], 405);
        }

        $player->decrement('balance', $validated['stake_amount']);                                        // Update player balance

        return response()->noContent(201);
    }
}
