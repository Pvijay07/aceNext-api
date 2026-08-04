<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'student_name',
        'email',
        'phone',
        'qualification',
        'course_id',
        'status',
        'document_url',
        'admission_date',
        'remarks',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
