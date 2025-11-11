<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($id)
    {
        $project = Project::with(['members', 'cards.assignedUsers'])->findOrFail($id);
        return view('teamleader.projects.show', compact('project'));
    }
}