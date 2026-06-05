<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $fillable = ['user_id', 'assessment_id', 'score', 'status', 'responses'];
    protected $casts = ['responses' => 'array'];
}
