<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Get all projects for current user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get projects where user is a member or creator
        $projects = Project::whereHas('members', function ($query) use ($user) {
            $query->where('project_members.user_id', $user->user_id);
        })
        ->orWhere('created_by', $user->user_id)
        ->with('members', 'cards.subTasks')
        ->get();

        return response()->json([
            'message' => 'Projects retrieved successfully',
            'data' => $projects,
        ]);
    }

    /**
     * Get project detail
     */
    public function show($project_id)
    {
        $user = Auth::user();

        $project = Project::with('members', 'cards.subTasks', 'cards.comments.user')
            ->findOrFail($project_id);

        // Check if user is member or creator
        if ($project->created_by !== $user->user_id &&
            !$project->members->contains('user_id', $user->user_id)) {
            return response()->json([
                'message' => 'Unauthorized access to this project',
            ], 403);
        }

        return response()->json([
            'message' => 'Project retrieved successfully',
            'data' => $project,
        ]);
    }

    /**
     * Create new project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'difficulty' => 'nullable|in:easy,medium,hard',
        ]);

        $project = Project::create([
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            'deadline' => $validated['deadline'],
            'difficulty' => $validated['difficulty'],
            'created_by' => Auth::id(),
            'status' => 'in_progress',
        ]);

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project,
        ], 201);
    }

    /**
     * Get project members
     */
    public function members($project_id)
    {
        $project = Project::with('members')->findOrFail($project_id);

        return response()->json([
            'message' => 'Project members retrieved successfully',
            'data' => $project->members,
        ]);
    }
}
