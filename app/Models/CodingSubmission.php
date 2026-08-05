<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodingSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'project_id',
        'question_id',
        'code',
        'language',
        'status',
        'runtime',
        'memory',
        'runtime_ms',
        'memory_kb',
        'error_log'
    ];
}
