<?php

namespace App\AttackModifiers;

use App\Interfaces\AttackModifierInterface;
use App\Interfaces\UnitInterface;

class MissChance implements AttackModifierInterface
{
    private $unit;

    public function __construct(UnitInterface $unit)
    {
        $this->unit = $unit;
    }

    public function apply()
    {
        $this->unit->setAttackMisses(true);
        
    }

    public function condition($percent)
    {
        return random_chance($percent);
    }

    public function clear()
    {
        $this->unit->setAttackMisses(false);
    }
}
