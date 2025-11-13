<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class MyTeamController extends Controller
{
    public function index()
    {
        // Dapatkan semua projects yang designer-nya adalah user yang login
        $projects = Project::whereHas('members', function ($query) {
            $query->where('project_members.user_id', Auth::id());
        })->with(['members', 'leader'])->get();

        return view('designer.my-team', compact('projects'));
    }

    public function show($project_id)
    {
        $project = Project::with('members')->findOrFail($project_id);

        // Pastikan designer adalah member dari project
        if (!$project->members->contains('user_id', Auth::id())) {
            abort(403, 'Unauthorized access');
        }

        return view('designer.my-team-detail', compact('project'));
    }
}
