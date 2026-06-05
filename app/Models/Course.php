<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'thumbnail_url', 'price', 'status', 'mentor_id'];

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('order_no');
    }
}
