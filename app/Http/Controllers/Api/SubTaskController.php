<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubTask;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubTaskController extends Controller
{
    /**
     * Get subtasks for a card
     */
    public function index($card_id)
    {
        $card = Card::findOrFail($card_id);
        $subtasks = $card->subTasks()->with('timeLogs.user')->get();

        return response()->json([
            'message' => 'Subtasks retrieved successfully',
            'data' => $subtasks,
        ]);
    }

    /**
     * Get subtask by status
     */
    public function byStatus($card_id, $status)
    {
        $card = Card::findOrFail($card_id);
        $subtasks = $card->subTasks()
            ->where('status', $status)
            ->with('timeLogs.user')
            ->get();

        return response()->json([
            'message' => "Subtasks with status '{$status}' retrieved successfully",
            'data' => $subtasks,
        ]);
    }

    /**
     * Get subtask detail
     */
    public function show($sub_task_id)
    {
        $subtask = SubTask::with('timeLogs.user', 'card')->findOrFail($sub_task_id);

        return response()->json([
            'message' => 'Subtask retrieved successfully',
            'data' => $subtask,
        ]);
    }

    /**
     * Create new subtask
     */
    public function store(Request $request, $card_id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subtask = SubTask::create([
            'card_id' => $card_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'todo',
            'is_completed' => false,
        ]);

        return response()->json([
            'message' => 'Subtask created successfully',
            'data' => $subtask,
        ], 201);
    }

    /**
     * Update subtask status
     */
    public function updateStatus(Request $request, $sub_task_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $subtask = SubTask::findOrFail($sub_task_id);
        $subtask->update([
            'status' => $validated['status'],
            'is_completed' => $validated['status'] === 'done',
        ]);

        return response()->json([
            'message' => 'Subtask status updated successfully',
            'data' => $subtask,
        ]);
    }

    /**
     * Toggle subtask completion
     */
    public function toggle($sub_task_id)
    {
        $subtask = SubTask::findOrFail($sub_task_id);
        $subtask->update(['is_completed' => !$subtask->is_completed]);

        return response()->json([
            'message' => 'Subtask toggled successfully',
            'data' => $subtask,
        ]);
    }
}
