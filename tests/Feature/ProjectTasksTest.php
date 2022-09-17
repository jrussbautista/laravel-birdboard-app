<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_add_tasks_to_projects() {
        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    public function test_only_owner_of_the_project_can_add_tasks() {
        $this->signIn();
        $project = Project::factory()->create();

        $attributes = Task::factory()->raw();

        $this->post($project->path() . '/tasks', $attributes)
            ->assertStatus(403);
            
        $this->assertDatabaseMissing('tasks', $attributes);
    }

    public function test_project_can_have_tasks() {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $project = Project::factory(['owner_id' => $user->id])->create();

        $attributes = ['body' => 'Test task'];

        $this->post($project->path() . "/tasks", $attributes);
        
        $this->get($project->path())
            ->assertSee($attributes['body']);
    }
    
    public function test_task_requires_a_body() {
        $user = $this->signIn();

        $project = Project::factory(['owner_id' => $user->id])->create();

        $attributes = Task::factory()->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}