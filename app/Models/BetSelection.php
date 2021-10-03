<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetSelection extends Model
{
    use HasFactory;

    protected $table = 'bet_selections';

    protected $fillable = [
        'bet_id',
        'selection_id',
        'odds',
        'bet_id',
    ];

    // Relationships _________________________________________
    public function bet()
    {
        return $this->belongsTo(Bet::class);
    }
}
