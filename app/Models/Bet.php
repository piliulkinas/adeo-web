<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $table = 'bets';

    protected $fillable = [
        'player_id',
        'stake_amount',
    ];

    // Relationships ____________________________
    public function betSelections()
    {
        return $this->hasMany(BetSelection::class);
    }
}
