<?php

namespace Database\Factories;

use App\Models\Army;
use App\Models\Tank;
use Illuminate\Database\Eloquent\Factories\Factory;

class TankFactory extends Factory
{

    public $tankNames = [
        'T-90M/MS',
        'K1A2',
        'Karrar',
        'Challenger 3',
        'Lecleerc XLR',
        'VT-4'
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->tankNames[mt_rand(0, count($this->tankNames) - 1)],
            'damage' => mt_rand(Tank::MIN_DAMAGE, Tank::MAX_DAMAGE),
            'armor' => Tank::ARMOR,
            'health' => Tank::HEALTH,
            'alive' => true,
            'army_id' => function() {
                return Army::factory()->create()->id;
            },
        ];
    }
}
