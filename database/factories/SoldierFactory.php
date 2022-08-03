<?php

namespace Database\Factories;

use App\Models\Army;
use App\Models\Soldier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoldierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->firstName(),
            'damage' => mt_rand(Soldier::MIN_DAMAGE, Soldier::MAX_DAMAGE),
            'armor' => Soldier::ARMOR,
            'health' => Soldier::HEALTH,
            'alive' => true,
            'army_id' => function() {
                return Army::factory()->create()->id;
            },
        ];
    }
}
