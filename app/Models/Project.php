<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ProjectsController;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {  
        return $this->tasks()->create(compact('body'));
    }

    public function recordActivity($description)
    {
        $this->activity()->create(compact('description'));
    }


    public function activity()
    {  
        return $this->hasMany(Activity::class);
    }

}