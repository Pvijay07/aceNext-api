<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAttendance extends Model
{
    protected $fillable = ['live_class_id', 'user_id', 'join_time', 'leave_time', 'status'];

    public function liveClass()
    {
        return $this->belongsTo(LiveClass::class);
    }
}
