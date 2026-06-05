<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = ['user_id', 'avatar_url', 'bio', 'phone', 'github_url', 'linkedin_url', 'xp', 'streak', 'badges', 'completed_lessons', 'completed_labs', 'completed_quizzes'];

    protected $casts = [
        'badges' => 'array',
        'completed_lessons' => 'array',
        'completed_labs' => 'array',
        'completed_quizzes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
