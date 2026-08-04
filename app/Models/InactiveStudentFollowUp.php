<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InactiveStudentFollowUp extends Model
{
    protected $table = 'inactive_students_follow_up';

    protected $fillable = [
        'student_id',
        'last_active_date',
        'contact_status',
        'remarks',
        'follow_up_date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
