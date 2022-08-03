<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    use HasFactory;

    public function tanks()
    {
        return $this->hasMany(Tank::class);
    }

    public function soldiers()
    {
        return $this->hasMany(Soldier::class);
    }

    public function getUnitsAttribute()
    {
        return $this->tanks->concat($this->soldiers)->shuffle();
    }
}
