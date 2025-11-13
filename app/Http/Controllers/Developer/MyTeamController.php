<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class MyTeamController extends Controller
{
    public function index()
    {
        // Dapatkan semua projects yang developer-nya adalah user yang login
        $projects = Project::whereHas('members', function ($query) {
            $query->where('project_members.user_id', Auth::id());
        })->with(['members', 'leader'])->get();

        return view('developer.my-team', compact('projects'));
    }

    public function show($project_id)
    {
        $project = Project::with('members')->findOrFail($project_id);

        // Pastikan developer adalah member dari project
        if (!$project->members->contains('user_id', Auth::id())) {
            abort(403, 'Unauthorized access');
        }

        return view('developer.my-team-detail', compact('project'));
    }
}
