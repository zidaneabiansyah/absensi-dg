<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(AdminUserSeeder::class);
        // $this->call(SemesterSeeder::class);

        // Create 5 classes
        $classes = \App\Models\ClassModel::factory(5)->create();

        // Create 50 students across those classes
        foreach ($classes as $class) {
            \App\Models\Student::factory(10)->create([
                'class_id' => $class->id
            ]);
        }
    }
}
