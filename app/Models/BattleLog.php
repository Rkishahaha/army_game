<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleLog extends Model
{
    use HasFactory;

    public function firstArmy()
    {
        return $this->belongsTo(Army::class, 'army1_id', 'id');
    }

    public function secondArmy()
    {
        return $this->belongsTo(Army::class, 'army2_id', 'id');
    }    

    public function victor()
    {
        return $this->belongsTo(Army::class, 'winner', 'id');
    }
}
