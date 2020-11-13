<?php



namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\User;

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
        ];
        $request = $this->post('/projects', $attributes);
        $project = Project::where($attributes)->first();

        $request->assertRedirect($project->path());
        
        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }
    
    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->signIn();
        
        $project = Project::factory()->create(['owner_id' => auth()->id()]);        

        $this->get($project->path())
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
