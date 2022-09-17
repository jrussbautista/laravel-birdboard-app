<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{   
    use WithFaker, RefreshDatabase;


    public function test_guest_cannot_create_projects() {
        $attributes = Project::factory()->raw();

        $this->get('/projects/create', $attributes)->assertRedirect('login');
        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    public function test_guest_cannot_view_projects() {
        $this->get('/projects')->assertRedirect('login');
    }

    
    public function test_guest_cannot_view_single_project() {
        $project = Project::factory()->create(); 

        $this->get($project->path())->assertRedirect('login');
    }


    public function test_authenticated_user_can_create_projects() {
        $user = $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = Project::factory(['owner_id' => $user->id])->raw();

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();
        
        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
        
    }

    public function test_authenticated_user_can_view_their_project() {
        $user = $this->signIn();

        $project = Project::factory(['owner_id' => $user->id])->create();
        
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_unauthenticated_user_cannot_view_the_project_of_others() {
        $this->signIn();

        $project = Project::factory()->create();
        
        $this->get($project->path())->assertStatus(403);
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
