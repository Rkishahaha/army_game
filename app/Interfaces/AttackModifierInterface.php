<?php

namespace App\Interfaces;

interface AttackModifierInterface
{
    public function apply();

    public function condition($percent);

    public function clear();
}