<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index() {
        $projects = Project::all();
        return view('projects.index', ['projects' => $projects]);
    }

    public function show(Project $project) {
        return view('projects.show', ['project' => $project]);
    }

    public function store() {
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required',
        ]);

        $attributes['owner_id'] = auth()->id();

        Project::create($attributes);
        
        return redirect('/projects');
    }
}
