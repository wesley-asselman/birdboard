<?php



namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Facades\Tests\Setup\ProjectArranger;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

       /** @test */
   public function it_has_a_path()
   {
        $project = Project::factory()->create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
   }

   
    /** @test */
    public function guests_cannot_manage_projects()
    {        
        $project = Project::factory()->create();

        //show index
        $this->get('projects')->assertRedirect('login');
        //create page
        $this->get('projects/create')->assertRedirect('login');
        //create 
        $this->post('/projects', $project->toarray())->assertRedirect('login');
        //show single
        $this->get($project->path())->assertRedirect('login');

    }

    /** @test */

    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'general notes here',
        ];
        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());
        

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
            
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->signIn();
        
        $project = ProjectArranger::create();

        $this->actingAs($project->owner)
        ->patch($project->path(), $attributes = ['notes' => 'Changed'])
        ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }
    
    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->signIn();
        
        $project = ProjectArranger::create();

        $this->actingAs($project->owner)->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_anothers_projects()
    {
        $this->signIn();
        
        $project = Project::factory()->create();    
        
        $this->get($project->path())->assertStatus(403);

    }

    /** @test */
    public function an_authenticated_user_cannot_update_anothers_projects()
    {
        $this->signIn();
        
        $project = Project::factory()->create();    
        
        $this->patch($project->path())->assertStatus(403);

    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('projects', $attributes)->assertSessionHasErrors('title');
    }
        
    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_project_belongs_to_an_owner()
    {
        $project = Project::factory()->create();    

        $this->assertInstanceOf(User::class, $project->owner );
    }

    /** @test */
    public function it_can_add_a_task()
    {
        
        $project = Project::factory()->create();    
        
        $task = $project->addTask('Test Task');

        $this->assertCount(1, $project->tasks);

        $this->assertTrue($project->tasks->contains($task));

    }

}
