<?php

namespace Database\Factories;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('#########'),
            'nisn' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->name(),
            'class_id' => ClassModel::factory(),
            'rfid_uid' => $this->faker->unique()->hexColor(), // Using hex color as fake RFID UID
            'status' => 'active',
        ];
    }
}
