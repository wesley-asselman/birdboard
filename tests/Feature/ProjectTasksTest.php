<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->withoutExceptionHandling();
        
        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $this->post($project->path() . '/tasks' , ['body' => 'Test Task']);

        $this->get($project->path())
            ->assertSee('Test Task');

            
    }

    /** @test */
    public function a_task_requires_a_body()
    {

        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $task = Task::factory()->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $task)->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_task_can_be_updated()
    {

        $this->withoutExceptionHandling();
        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $task = $project->addTask('test task');

        $this->patch($task->path(), [
            'body' => 'changed',
            'completed'=> true,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed'=> true,
        ]);

    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Task']);
    }

        /** @test */
        public function only_the_owner_of_a_project_may_update_a_task()
        {
            $this->signIn();
    
            $project = Project::factory()->create();

            $task = $project->addTask('test task');
    
            $this->patch($task->path(), ['body' => 'Test Task'])
                ->assertStatus(403);
    
            $this->assertDatabaseMissing('tasks', ['body' => 'Test']);
        }
}
