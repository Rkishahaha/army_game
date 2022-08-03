<?php

namespace App\Interfaces;

interface UnitInterface 
{
    public function attack(UnitInterface $unit);
}