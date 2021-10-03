<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    use HasFactory;

    protected $table = 'balance_transactions';

    protected $fillable = [
        'player_id',
        'amount',
        'amount_before'
    ];

    // Relationships ________________________________
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
