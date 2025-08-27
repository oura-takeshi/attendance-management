<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workTime()
    {
        return $this->hasOne(WorkTime::class);
    }

    public function workTimeRequests()
    {
        return $this->hasMany(WorkTimeRequest::class);
    }
}
