<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class MyTeamController extends Controller
{
    public function index()
    {
        // Dapatkan semua projects dimana user adalah leader (created_by) atau menjadi member
        $userId = Auth::id();
        $projects = Project::where(function ($q) use ($userId) {
            $q->where('created_by', $userId)
              ->orWhereHas('members', function ($query) use ($userId) {
                  $query->where('project_members.user_id', $userId);
              });
        })->with(['members', 'leader'])->get();

        return view('teamleader.my-team', compact('projects'));
    }

    public function show($project_id)
    {
        $project = Project::with(['members', 'leader'])->findOrFail($project_id);

        // Pastikan teamleader adalah leader atau member dari project
        $userId = Auth::id();
        $isMember = $project->members->contains('user_id', $userId);
        $isLeader = $project->created_by === $userId;

        if (! $isMember && ! $isLeader) {
            abort(403, 'Unauthorized access');
        }

        return view('teamleader.my-team-detail', compact('project'));
    }
}
