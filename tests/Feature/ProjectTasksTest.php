<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use Facades\Tests\Setup\ProjectArranger;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectArranger::create();

        $this->actingas($project->owner)
            ->post($project->path() . '/tasks' , ['body' => 'Test Task']);

        $this->get($project->path())
            ->assertSee('Test Task');
    }

    /** @test */
    public function a_task_requires_a_body()
    {

        $project = ProjectArranger::create();


        $task = Task::factory()->raw(['body' => '']);

        $this->actingas($project->owner)->post($project->path() . '/tasks', $task)->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        
        $project = ProjectArranger::withTasks(1)->create();

        $this->actingAs($project->owner)
        ->patch($project->tasks->first()->path(), [
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
    
            $project = ProjectArranger::withTasks(1)->create();

            $this->patch($project->tasks->first()->path(), ['body' => 'Test Task'])
                ->assertStatus(403);
    
            $this->assertDatabaseMissing('tasks', ['body' => 'Test']);
        }
}
