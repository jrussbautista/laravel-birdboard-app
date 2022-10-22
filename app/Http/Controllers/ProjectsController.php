<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index() {
        $projects = auth()->user()->projects;
        return view('projects.index', ['projects' => $projects]);
    }

    public function show(Project $project) {
        $this->authorize('update', $project);
        return view('projects.show', ['project' => $project]);
    }

    public function create() {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request) {
        $attributes = $request->validated();

        $project = auth()->user()->projects()->create($attributes);
        
        return redirect($project->path());
    }

    public function update(UpdateProjectRequest $request, Project $project) {
        $this->authorize('update', $project);
        
        $project->update($request->validated());

        return redirect($project->path());
    }
}
