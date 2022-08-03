<?php

use App\AttackModifiers\CriticalStrike;
use App\AttackModifiers\InstantKill;
use App\AttackModifiers\MissChance;
use App\Models\Soldier;
use App\Models\Tank;

return [

    'attack_modifiers' => [
        Soldier::class => [
            CriticalStrike::class,
            MissChance::class,
        ],
        Tank::class => [
            InstantKill::class,
            MissChance::class
        ]
    ],

    // percents are passed as integers (from 0 to 100)

    'attack_modifiers_percent' => [
        Soldier::class => [CriticalStrike::class => 25, MissChance::class => 20],
        Tank::class => [InstantKill::class => 30, MissChance::class => 35]
    ]

];