<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;

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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'middle_initial' => fake()->randomLetter(),
            'sex' => fake()->randomElement(['Male', 'Female']),
            'position_name' => fake()->jobTitle(),
            'is_head' => false,
            'is_divisionhead' => false,
            'is_vp' => false,
            'is_president' => false,
        ];
    }
}