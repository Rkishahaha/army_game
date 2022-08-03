<?php

namespace App\AttackModifiers;

use App\Interfaces\AttackModifierInterface;
use App\Interfaces\UnitInterface;

class CriticalStrike implements AttackModifierInterface
{
    private $unit;
    private $originalDamage;

    public function __construct(UnitInterface $unit)
    {
        $this->unit = $unit;
        $this->originalDamage = $unit->damage;
    }

    public function apply()
    {
        $this->unit->damage = $this->unit->damage * 2.5; // a unit can attack with 2 or 3 times greater force
    }

    public function condition($percent)
    {
        return $this->unit->health < $this->unit::HEALTH / 2 && random_chance($percent);
    }

    public function clear()
    {
        $this->unit->damage = $this->originalDamage;
    }
}
