<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Projects;

class Tasks extends Model
{
    use HasFactory;

    public function project() { 
        return $this->belongsTo(Projects::class); 
    }
}
