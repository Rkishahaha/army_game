<?php

namespace App\Models;

use App\Interfaces\UnitInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soldier extends Model implements UnitInterface
{
    use HasFactory;

    protected $guarded = [''];

    const HEALTH = 10;
    const ARMOR = 3;
    const MIN_DAMAGE = 5;
    const MAX_DAMAGE = 9;
    const TYPE = 'Soldier';

    private $lastHit;
    private $possibleAttackModifiers;
    private $attackModifiers = [];
    private $attackModifiersPercent;
    private $attackMisses = false;
    private $lastAttackLog = '';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->possibleAttackModifiers = config("game.attack_modifiers." . get_class($this));
        $this->attackModifiersPercent = config("game.attack_modifiers_percent." . get_class($this));
        
    }

    public function setAttackModifierPercent($class, $percent)
    {
        $this->attackModifiersPercent[$class] = $percent;
    }

    public function setAttackMisses($boolean)
    {
        $this->attackMisses = $boolean;
    }

    public function getLastHit()
    {
        return $this->lastHit;
    }

    public function getLastAttackLog()
    {
        return $this->lastAttackLog;
    }

    public function attack(UnitInterface $unit)
    {
        if ($this->army_id == $unit->army_id) {
            throw new \Exception("Cannot attack unit within the same army", 500);
        }

        foreach ($this->possibleAttackModifiers as $attackModifier) {
            $modifier = new $attackModifier($this);
            $this->attackModifiers[] = $modifier;

            if ($modifier->condition($this->attackModifiersPercent[get_class($modifier)])) {
                $modifier->apply();
            }
        }

        if (($this->attackMisses)) {

            $this->lastAttackLog = "{$this->army->name} unit {$this->name} (" . $this::TYPE . ") attacked an enemy unit {$unit->name} (" . $unit::TYPE . ") and missed<br>";            

            $this->unsetModifiers();
            return false;
        }        


        $this->lastHit = $this->damage;

        if ($unit->armor > $this->damage) {

            $this->lastAttackLog = "{$this->army->name} unit {$this->name} (" . $this::TYPE . ") attacked an enemy unit {$unit->name} (" . $unit::TYPE . ") but the armor was too high (enemy armor: {$unit->armor}, attacker damage: {$this->damage})<br>";            

            $this->unsetModifiers();
            return true;
        }

        
        if (($this->damage - $unit->armor) >= $unit->health) {

            $this->lastAttackLog = "{$this->army->name} unit {$this->name} (" . $this::TYPE . ") attacked an enemy unit {$unit->name} (" . $unit::TYPE . ") and killed it<br>";            

            $unit->alive = false;
            $this->unsetModifiers();
            return true;
        }
        
        $this->lastAttackLog = "{$this->army->name} unit {$this->name} (" . $this::TYPE . ") attacked an enemy unit {$unit->name} (" . $unit::TYPE . ") and dealt " . ($this->damage - $unit->armor) . " damage<br>";
        
        $unit->health = $unit->health - ($this->damage - $unit->armor);
        $this->unsetModifiers();
        return true;
    }

    public function unsetModifiers()
    {
        foreach ($this->attackModifiers as $modifier) {
            $modifier->clear();
        }
    }

    public function army()
    {
        return $this->belongsTo(Army::class);
    }    

}
