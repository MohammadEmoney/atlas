<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'position' => $this->faker->jobTitle(),
            'manager_id' => null,
            'role' => 'employee',
            'leave_balance' => $this->faker->numberBetween(5, 30),
        ];
    }

    public function hr():self { return $this->state(fn()=> ['role' => 'hr']); }
    public function manager():self { return $this->state(fn()=> ['role' => 'manager']); }
    public function ceo():self { return $this->state(fn()=> ['role' => 'ceo']); }
}
