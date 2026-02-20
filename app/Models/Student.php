<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nisn',
        'name',
        'class_id',
        'rfid_uid',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the class that owns the student
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get all attendances for this student
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get attendance for a specific date
     */
    public function attendanceForDate(string $date): ?Attendance
    {
        return $this->attendances()->where('date', $date)->first();
    }
}
