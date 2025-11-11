<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockerController extends Controller
{
    /**
     * Store a new blocker/help request
     */
    public function store(Request $request)
    {
        $request->validate([
            'subtask_id' => 'nullable|exists:sub_tasks,sub_task_id',
            'issue_description' => 'required|string|min:10|max:1000',
        ]);

        HelpRequest::create([
            'subtask_id' => $request->subtask_id,
            'user_id' => Auth::user()->user_id,
            'issue_description' => $request->issue_description,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan bantuan berhasil dikirim ke Team Lead.');
    }

    /**
     * Get blocker requests for current developer (JSON for AJAX)
     */
    public function getMyBlockers()
    {
        $blockers = HelpRequest::where('user_id', Auth::user()->user_id)
            ->with('subTask', 'resolver')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($blockers);
    }
}
