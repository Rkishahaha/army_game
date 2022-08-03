<?php

namespace App\Core;

use App\Models\Army;
use App\Models\BattleLog;
use App\Models\Soldier;
use App\Models\Tank;
use Illuminate\Support\Facades\Redirect;

class Game  
{
    private $army1;
    private $army2;
    private $battleLog = '';

    public function __construct($request)
    {
        $this->army1 = Army::factory()->create();
        $this->army2 = Army::factory()->create();

        if ($request['soldiers1'] > 0) {
            $s1 = Soldier::factory()->count($request['soldiers1'])->make(['army_id' => $this->army1->id]);
            Soldier::insert($s1->toArray());
        }

        if ($request['soldiers2'] > 0) {
            $s2 = Soldier::factory()->count($request['soldiers2'])->make(['army_id' => $this->army2->id]);
            Soldier::insert($s2->toArray());
        }

        if ($request['tanks1'] > 0) {
            $t1 = Tank::factory()->count($request['tanks1'])->make(['army_id' => $this->army1->id]);
            Tank::insert($t1->toArray());
        }

        if ($request['tanks2'] > 0) {
            $t2 = Tank::factory()->count($request['tanks2'])->make(['army_id' => $this->army2->id]);
            Tank::insert($t2->toArray());
        }        
    }

    public function start()
    {

        dump("Starting game: {$this->army1->name} VS {$this->army2->name}");

        $army1UnitsCount = count($this->army1->units);
        $army2UnitsCount = count($this->army2->units);

        while($this->gameIsActive()) {

            $attacker = $this->getRandomUnit($this->army1->units);
            $defender = $this->getRandomUnit($this->army2->units);

            $attacker->attack($defender);

            $this->battleLog .= $attacker->getLastAttackLog();

            if ($this->isArmyDefeated($this->army2)) {
                BattleLog::insert([
                    'army1_id' => $this->army1->id,
                    'army2_id' => $this->army2->id,
                    'battle_log' => json_encode($this->battleLog),
                    'winner' => $this->army1->id
                ]);

                return Redirect::route('game-result', ['army1' => $this->army1->id, 'army2' => $this->army2->id])->send();
            }

            $attacker = $this->getRandomUnit($this->army2->units);
            $defender = $this->getRandomUnit($this->army1->units);

            $attacker->attack($defender);

            $this->battleLog .= $attacker->getLastAttackLog();
           

            if ($this->isArmyDefeated($this->army1)) {
                BattleLog::insert([
                    'army1_id' => $this->army1->id,
                    'army2_id' => $this->army2->id,
                    'battle_log' => json_encode($this->battleLog),
                    'winner' => $this->army2->id

                ]);
                return Redirect::route('game-result', ['army1' => $this->army1->id, 'army2' => $this->army2->id])->send();
            }

        }
    }

    public function getRandomUnit($units)
    {
        $units = $units->filter(function ($item, $key) {
            return $item['alive'];
        })->values();


        $randomUnit = mt_rand(0, count($units) - 1);

        return $units[$randomUnit];
    }

    public function gameIsActive()
    {
        return $this->isArmyDefeated($this->army1) === false && $this->isArmyDefeated($this->army2) === false;
    }

    public function isArmyDefeated($army)
    {
        foreach (array_column($army->units->toArray(), 'alive') as $val) {
            if (false !== $val) {
                return false;
            }
        }
        return true;
    }    
}
