<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_add_task_to_projects() {
        $project = ProjectFactory::create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    public function test_guest_cannot_update_task() {
        $project = ProjectFactory::withTasks(1)
            ->create();

        $updatedAttributes = ['body' => 'Updated task', 'completed' => true];
        
        $task = $project->tasks->first();
    
        $this->patch($task->path(), $updatedAttributes);

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }


    public function test_only_owner_of_the_project_can_add_tasks() {
        $this->signIn();
        $project = ProjectFactory::create();

        $attributes = Task::factory()->raw();

        $this->post($project->path() . '/tasks', $attributes)
            ->assertStatus(403);
            
        $this->assertDatabaseMissing('tasks', $attributes);
    }

    public function test_only_owner_of_the_project_can_update_tasks() {
        $this->signIn();
        
        $project = ProjectFactory::withTasks(1)->create();

        $updatedAttributes = ['body' => 'Updated task', 'completed' => true];
        
        $task = $project->tasks->first();

        $this->patch($task->path(), $updatedAttributes)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $updatedAttributes);
    }

    public function test_project_can_have_tasks() {
        $user = $this->signIn();

        $project = ProjectFactory::ownedBy($user)->create();

        $attributes = ['body' => 'Test task'];

        $this->post($project->path() . "/tasks", $attributes);
        
        $this->get($project->path())
            ->assertSee($attributes['body']);
    }

    public function test_task_can_be_updated() {

        $user = $this->signIn();

        $project = ProjectFactory::ownedBy($user)
                    ->withTasks(1)
                    ->create();

        $attributes = [
            'body' => 'Updated task',
            'completed' => true
        ];

        $task = $project->tasks->first();

        $this->patch($task->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    
    public function test_task_requires_a_body() {
        $user = $this->signIn();
        
        $project = ProjectFactory::ownedBy($user)->create();

        $attributes = Task::factory()->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}