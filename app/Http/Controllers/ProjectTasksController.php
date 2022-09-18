<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project) {
        $this->authorize('update', $project);

        $attributes = request()->validate(['body' => 'required']);

        $project->addTask($attributes['body']);

        return redirect($project->path());
    }

    public function update(Project $project, Task $task) {
        $this->authorize('update', $task->project);

        $task->update([
            'body' => request('body'),
            'completed' => request()->has('completed')
        ]);

        return redirect($project->path());
    }
}
