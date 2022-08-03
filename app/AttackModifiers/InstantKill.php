<?php

namespace App\AttackModifiers;

use App\Interfaces\AttackModifierInterface;
use App\Interfaces\UnitInterface;

class InstantKill implements AttackModifierInterface
{
    public function __construct(UnitInterface $unit)
    {
        $this->unit = $unit;
        $this->originalDamage = $unit->damage;
    }

    public function apply()
    {
        $this->unit->damage = 9999999; // a unit can oneshot an enemy
    }

    public function condition($percent)
    {
        return $this->unit->health <= $this->unit::HEALTH - $this->unit::HEALTH * 80 / 100 && random_chance($percent);
    }

    public function clear()
    {
        $this->unit->damage = $this->originalDamage;
    }
}
