<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Get cards for a project
     */
    public function index($project_id)
    {
        $project = Project::findOrFail($project_id);
        $cards = $project->cards()->with('subTasks', 'comments.user', 'assignedUsers')->get();

        return response()->json([
            'message' => 'Cards retrieved successfully',
            'data' => $cards,
        ]);
    }

    /**
     * Get card by status
     */
    public function byStatus($project_id, $status)
    {
        $project = Project::findOrFail($project_id);
        $cards = $project->cards()
            ->where('status', $status)
            ->with('subTasks', 'comments.user', 'assignedUsers')
            ->get();

        return response()->json([
            'message' => "Cards with status '{$status}' retrieved successfully",
            'data' => $cards,
        ]);
    }

    /**
     * Get card detail
     */
    public function show($card_id)
    {
        $card = Card::with('subTasks', 'comments.user', 'assignedUsers', 'timeLogs.user')
            ->findOrFail($card_id);

        return response()->json([
            'message' => 'Card retrieved successfully',
            'data' => $card,
        ]);
    }

    /**
     * Create new card
     */
    public function store(Request $request, $project_id)
    {
        $validated = $request->validate([
            'card_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric',
        ]);

        $card = Card::create([
            'project_id' => $project_id,
            'card_title' => $validated['card_title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'medium',
            'due_date' => $validated['due_date'],
            'estimated_hours' => $validated['estimated_hours'],
            'status' => 'todo',
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Card created successfully',
            'data' => $card,
        ], 201);
    }

    /**
     * Update card status
     */
    public function updateStatus(Request $request, $card_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $card = Card::findOrFail($card_id);
        $card->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Card status updated successfully',
            'data' => $card,
        ]);
    }
}
