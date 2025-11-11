<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockerController extends Controller
{
    /**
     * Display all help requests for Team Lead's project members
     */
    public function index()
    {
        $requests = HelpRequest::with('reporter', 'subTask', 'resolver')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teamleader.blockers.index', compact('requests'));
    }

    /**
     * Update blocker status (pending → in_progress → fixed → completed)
     */
    public function update(Request $request, $request_id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,fixed,completed',
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $helpRequest = HelpRequest::findOrFail($request_id);

        $helpRequest->update([
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'resolved_by' => Auth::user()->user_id,
            'resolved_at' => $request->status === 'completed' ? now() : null,
        ]);

        return redirect()->back()->with('success', "Status blocker berhasil diperbarui menjadi: {$request->status}");
    }

    /**
     * Mark blocker as done (quick action)
     */
    public function markDone($request_id)
    {
        $helpRequest = HelpRequest::findOrFail($request_id);

        $helpRequest->update([
            'status' => 'completed',
            'resolved_by' => Auth::user()->user_id,
            'resolved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Blocker berhasil ditandai selesai.');
    }
}
