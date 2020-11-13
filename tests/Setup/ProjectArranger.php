<?php

namespace Tests\Setup;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;

class ProjectArranger
{   
    protected $tasksCount = 0;
    
    protected $user;

   

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }


    public function withTasks($count)
    {
        $this->tasksCount = $count;

        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::Factory(),
        ]);

        Task::factory($this->tasksCount)->create([
            'project_id' => $project->id
        ]);
        
        return $project;
    }    
}