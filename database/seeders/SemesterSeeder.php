<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Semester::create([
            'name' => 'Semester 1',
            'academic_year' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2025-12-31',
            'status' => 'active',
        ]);

        Semester::create([
            'name' => 'Semester 2',
            'academic_year' => '2025/2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'status' => 'inactive',
        ]);
    }
}
