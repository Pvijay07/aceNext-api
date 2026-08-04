<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'name',
        'course_id',
        'faculty_id',
        'start_date',
        'end_date',
        'capacity',
        'status',
        'delay_reason',
        'expected_completion_date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'batch_students', 'batch_id', 'student_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
