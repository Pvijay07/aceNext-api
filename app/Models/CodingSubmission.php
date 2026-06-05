<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodingSubmission extends Model
{
    protected $fillable = ['user_id', 'project_id', 'code', 'language', 'status', 'runtime_ms', 'memory_kb', 'error_log'];
}
