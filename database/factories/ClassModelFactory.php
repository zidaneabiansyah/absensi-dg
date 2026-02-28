<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassModel>
 */
class ClassModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $levels = ['X', 'XI', 'XII'];
        $majors = ['TKJ', 'RPL', 'MM', 'TBSM', 'TKR'];
        $sections = ['1', '2', '3'];

        return [
            'name' => $this->faker->randomElement($levels) . ' ' . 
                      $this->faker->randomElement($majors) . ' ' . 
                      $this->faker->randomElement($sections),
            'homeroom_teacher' => $this->faker->name(),
            'academic_year' => '2024/2025',
            'status' => 'active',
        ];
    }
}
