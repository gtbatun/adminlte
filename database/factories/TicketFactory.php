<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'user_id' => $this->faker->numberBetween(17,53),
            'type' => $this->faker->numberBetween(17,53),            
            'department_id' => $this->faker->numberBetween(1,22),                        
            'status_id' => $this->faker->numberBetween(1,5),
            'category_id' => $this->faker->numberBetween(1,51),
            'area_id' => $this->faker->numberBetween(1,6)           
        ];
    }
}
