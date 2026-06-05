<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['assessment_id', 'content', 'type', 'category', 'difficulty', 'metadata'];
    protected $casts = ['metadata' => 'array'];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
