<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index() {
        $projects = auth()->user()->projects;
        return view('projects.index', ['projects' => $projects]);
    }

    public function show(Project $project) {

        if(auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        return view('projects.show', ['project' => $project]);
    }

    public function store() {
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required',
        ]);

        auth()->user()->projects()->create($attributes);
        
        return redirect('/projects');
    }
}
