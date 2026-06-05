<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = ['company', 'logo', 'role', 'salary', 'location', 'type', 'requirements'];
    protected $casts = ['requirements' => 'array'];

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
