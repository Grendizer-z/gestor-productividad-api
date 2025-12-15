<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tasks;

class Projects extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'description',
        'is_archived',
        'user_id',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function user() { 
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tasks() { 
        return $this->hasMany(Tasks::class, 'projects_id'); 
    }
}
