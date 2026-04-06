<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'late_threshold',
        'is_active',
    ];

    protected $casts = [
        'check_in_start' => 'datetime:H:i',
        'check_in_end' => 'datetime:H:i',
        'check_out_start' => 'datetime:H:i',
        'check_out_end' => 'datetime:H:i',
        'late_threshold' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    /**
     * Get active attendance setting
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
