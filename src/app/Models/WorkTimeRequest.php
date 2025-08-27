<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_day_id',
        'start_time',
        'end_time',
        'reason',
        'approval',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approval'   => 'integer',
    ];

    public function attendanceDay()
    {
        return $this->belongsTo(AttendanceDay::class);
    }

    public function breakTimeRequests()
    {
        return $this->hasMany(BreakTimeRequest::class);
    }
}
