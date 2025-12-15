<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Projects;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'projects_id',
        'title',
        'due_date',
        'is_completed',
    ];

    public function project() { 
        return $this->belongsTo(Projects::class, 'projects_id'); 
    }
}
