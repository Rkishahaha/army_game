<?php

use App\AttackModifiers\InstantKill;
use App\AttackModifiers\MissChance;
use App\Models\Army;
use App\Models\Soldier;
use App\Models\Tank;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tank stats test', function () {

    $tank = Tank::factory()->make();

    expect($tank->damage)->toBeLessThanOrEqual(Tank::MAX_DAMAGE)->toBeGreaterThanOrEqual(Tank::MIN_DAMAGE);

    expect($tank->armor)->toBe(Tank::ARMOR);

    expect($tank->health)->toBe(Tank::HEALTH);

    expect($tank->alive)->toBe(true);
});


test('tank can attack and hit another tank', function () {

    $tank1 = Tank::factory()->make();
    $tank2 = Tank::factory()->make();

    $tank2HealthBeforeAttack = $tank2->health;

    $tank1->setAttackModifierPercent(MissChance::class, 0);


    $tank1->attack($tank2);


    expect($tank2->health)->toBe($tank2HealthBeforeAttack - ($tank1->damage - $tank2->armor));
});

test('tank can kill another tank', function () {

    $tank1 = Tank::factory()->make();
    $tank2 = Tank::factory()->make();

    while ($tank2->alive) {
        $tank1->attack($tank2);
    }

    expect($tank2->alive)->toBe(false);
});

test('tank can performe instant kill when health is lower or equal than 80%', function () {

    $tank1 = Tank::factory()->make();
    $tank2 = Tank::factory()->make();


    $tank1->health = Tank::HEALTH - Tank::HEALTH * 80 / 100;

    $tank1->setAttackModifierPercent(InstantKill::class, 100);
    $tank1->setAttackModifierPercent(MissChance::class, 0);

    $tank1->attack($tank2);


    expect($tank2->alive)->toBe(false);
});

test('tank can miss an attack', function () {

    $tank1 = Tank::factory()->make();
    $tank2 = Tank::factory()->make();

    $tank2HealthBeforeAttack = $tank2->health;

    $tank1->setAttackModifierPercent(MissChance::class, 100);


    $tank1->attack($tank2);

    expect($tank2->health)->toBe($tank2HealthBeforeAttack);
});

test('tank can attack a soldier', function () {
    
    $tank = Tank::factory()->make();
    $soldier = Soldier::factory()->make();

    $tank->setAttackModifierPercent(MissChance::class, 0);
    $tank->setAttackModifierPercent(InstantKill::class, 0);

    $soldierHealthBefore = $soldier->health;

    $tank->attack($soldier);


    $soldier->alive ? 
        expect($soldier->health)->toBe($soldierHealthBefore - ($tank->damage - $soldier->armor))
        :
        expect($soldier->health - ($tank->damage - $soldier->armor))->toBeLessThanOrEqual(0);

});

test('tank cannot attack a unit within the same army', function () {
    $army = Army::factory()->create();

    $tank = Tank::factory()->make(['army_id' => $army->id]);
    $soldier = Soldier::factory()->make(['army_id' => $army->id]);


    $tank->attack($soldier);


})->throws(\Exception::class);
