<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $fillable = ['course_module_id', 'title', 'type', 'asset_url', 'duration', 'order_no'];
}
