<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get active semester
     */
    public static function getActive()
    {
        return self::where('status', 'active')->first();
    }

    /**
     * Activate this semester and deactivate others
     */
    public function activate(): void
    {
        // Deactivate all semesters
        self::query()->update(['status' => 'inactive']);
        
        // Activate this semester
        $this->update(['status' => 'active']);
    }
}
