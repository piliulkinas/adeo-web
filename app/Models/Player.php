<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $table = 'players';

    protected $fillable = [
        'balance',
        'id'
    ];

    // Relationships __________________________________
    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
    public function balanceTransactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }
}
