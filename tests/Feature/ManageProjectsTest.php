<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectsTest extends TestCase
{   
    use WithFaker, RefreshDatabase;

    public function test_authenticated_user_can_create_projects() {        
        $user = $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = Project::factory(['owner_id' => $user->id, 'notes' => 'General notes'])->raw();

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();
        
        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    public function test_authenticated_user_can_view_their_project() {
        $user = $this->signIn();

        $project = ProjectFactory::ownedBy($user)->create();
        
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_authenticated_user_cannot_view_the_project_of_others() {
        $this->signIn();

        $project = ProjectFactory::create();
        
        $this->get($project->path())
            ->assertStatus(403);
    }

    public function test_authenticated_user_cannot_update_the_project_of_others() {
        $this->signIn();

        $project = ProjectFactory::create();

        $attributes = ['notes' => 'Changed notes.'];

        $this->patch($project->path(), $attributes)
            ->assertStatus(403);

    }

    public function test_guest_cannot_create_projects() {
        $attributes = Project::factory()->raw();

        $this->get('/projects/create', $attributes)->assertRedirect('login');
        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    public function test_guest_cannot_view_projects() {
        $this->get('/projects')->assertRedirect('login');
    }

    public function test_guest_cannot_view_single_project() {
        $project = ProjectFactory::create();

        $this->get($project->path())->assertRedirect('login');
    }

    public function test_guest_cannot_update_project() {
        $project = ProjectFactory::create();

        $attributes = Project::factory()->raw(); 

        $this->patch($project->path(), $attributes)
            ->assertRedirect('login');
    }


    public function test_project_requires_a_title() {
        $this->signIn();

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_project_requires_a_description() {
        $this->signIn();

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

}
