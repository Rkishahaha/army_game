<?php

use App\AttackModifiers\CriticalStrike;
use App\AttackModifiers\MissChance;
use App\Models\Army;
use App\Models\Soldier;
use App\Models\Tank;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('soldier stats test', function () {

    $soldier = Soldier::factory()->make();

    expect($soldier->damage)->toBeLessThanOrEqual(Soldier::MAX_DAMAGE)->toBeGreaterThanOrEqual(Soldier::MIN_DAMAGE);

    expect($soldier->armor)->toBe(Soldier::ARMOR);

    expect($soldier->health)->toBe(Soldier::HEALTH);

    expect($soldier->alive)->toBe(true);

});


test('soldier can attack and hit another soldier', function () {

    $soldier1 = Soldier::factory()->make();
    $soldier2 = Soldier::factory()->make();

    $soldier2HealthBeforeAttack = $soldier2->health;

    $soldier1->setAttackModifierPercent(MissChance::class, 0);

    $soldier1->attack($soldier2);


    expect($soldier2->health)->toBe($soldier2HealthBeforeAttack - ($soldier1->damage - $soldier2->armor));
});

test('soldier can kill another soldier', function () {

    $soldier1 = Soldier::factory()->make();
    $soldier2 = Soldier::factory()->make();
    
    while($soldier2->alive) {
        $soldier1->attack($soldier2);
    }

    expect($soldier2->alive)->toBe(false);

});

test('soldier can performe critical strike when health is lower than half', function () {

    $soldier1 = Soldier::factory()->make();
    $soldier2 = Soldier::factory()->make();


    $soldier1->health = Soldier::HEALTH / 2 - 1;

    $soldier1->setAttackModifierPercent(CriticalStrike::class, 100);
    $soldier1->setAttackModifierPercent(MissChance::class, 0);

    $soldier1->attack($soldier2);


    expect($soldier1->getLastHit())->toBe($soldier1->damage * 2.5);

});

test('soldier can miss an attack', function () {

    $soldier1 = Soldier::factory()->make();
    $soldier2 = Soldier::factory()->make();

    $soldier2HealthBeforeAttack = $soldier2->health;

    $soldier1->setAttackModifierPercent(MissChance::class, 100);


    $soldier1->attack($soldier2);

    expect($soldier2->health)->toBe($soldier2HealthBeforeAttack);

});

test('soldier can attack a tank', function () {

    $tank = Tank::factory()->make();
    $soldier = Soldier::factory()->make(['damage' => 9]);

    $soldier->setAttackModifierPercent(MissChance::class, 0);
    $soldier->setAttackModifierPercent(CriticalStrike::class, 0);

    $tankHealthBefore = $tank->health;

    $soldier->attack($tank);

    expect($tank->health)->toBe($tankHealthBefore - ($soldier->damage - $tank->armor));

});

test('soldier cannot attack a unit within the same army', function () {
    $army = Army::factory()->create();

    $tank = Tank::factory()->make(['army_id' => $army->id]);
    $soldier = Soldier::factory()->make(['army_id' => $army->id]);

    $soldier->attack($tank);

})->throws(\Exception::class);

test('soldier damage lower than tank armor has no effect', function () {
    $tank = Tank::factory()->make();
    $soldier = Soldier::factory()->make(['damage' => 5]);

    $tankHealthBefore = $tank->health;

    $soldier->attack($tank);

    expect($tank->health)->toBe($tankHealthBefore);

});
