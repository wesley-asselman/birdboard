<?php



namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;


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

    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }
    
    /** @test */
    public function a_user_can_view_a_project()
    {
        $this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('projects', $attributes)->assertSessionHasErrors('title');
    }
        
    /** @test */
    public function a_project_requires_a_description()
    {
        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('projects', $attributes)->assertSessionHasErrors('description');
    }

    public function a_project_requires_an_owner()
    {        
        $attributes = Project::factory()->raw();

        $this->post('projects', $attributes)->assertRedirect('login');
    }

}
