<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'homeroom_teacher',
        'academic_year',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get students in this class
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Get active students only
     */
    public function activeStudents(): HasMany
    {
        return $this->students()->where('status', 'active');
    }
}
