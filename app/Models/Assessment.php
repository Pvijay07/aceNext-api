<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = ['course_id', 'title', 'duration_mins', 'passing_score'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
