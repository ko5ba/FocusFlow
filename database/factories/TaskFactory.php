<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => $this->faker->text(20),
            'description' => fake()->text(50),
            'priority' => 1,
            'date_deadline' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Не выполнена', 'В работе', 'Завершена', 'Отложена']),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
