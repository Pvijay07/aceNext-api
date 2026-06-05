<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
    protected $fillable = ['course_id', 'topic', 'date', 'start_time', 'end_time', 'meeting_link', 'recording_url'];

    public function attendances()
    {
        return $this->hasMany(ClassAttendance::class);
    }
}
