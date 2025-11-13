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
    public function index(Request $request)
    {
        $query = HelpRequest::with('reporter', 'subTask', 'resolver', 'timeLogs');
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Attach running seconds for display
        foreach ($requests as $req) {
            $running = $req->timeLogs()->whereNull('end_time')->first();
            $req->running_seconds = $running ? now()->diffInSeconds($running->start_time) : 0;
        }

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

        $oldStatus = $helpRequest->status;
        $newStatus = $request->status;

        // Start timer when transitioning to in_progress
        if ($oldStatus !== 'in_progress' && $newStatus === 'in_progress') {
            // stop any running timer for this user on other help requests
            \App\Models\TimeLog::where('user_id', Auth::user()->user_id)
                ->whereNull('end_time')
                ->update([
                    'end_time' => now(),
                    'duration_seconds' => \DB::raw('TIMESTAMPDIFF(SECOND, start_time, NOW())')
                ]);

            // create a new time log attached to this help request
            \App\Models\TimeLog::create([
                'help_request_id' => $helpRequest->request_id,
                'user_id' => Auth::user()->user_id,
                'start_time' => now(),
            ]);
        }

        // Stop timer when moving out of in_progress (to fixed or completed)
        if ($oldStatus === 'in_progress' && in_array($newStatus, ['fixed', 'completed'])) {
            $running = \App\Models\TimeLog::where('help_request_id', $helpRequest->request_id)
                ->whereNull('end_time')
                ->first();

            if ($running) {
                $endTime = now();
                $duration = $endTime->diffInSeconds($running->start_time);
                $running->update([
                    'end_time' => $endTime,
                    'duration_seconds' => $duration,
                ]);
            }
        }

        // Update resolved_by only when fixed/completed
        $resolvedBy = in_array($newStatus, ['fixed', 'completed']) ? Auth::user()->user_id : null;
        $resolvedAt = $newStatus === 'completed' ? now() : ($newStatus === 'fixed' ? now() : null);

        $helpRequest->update([
            'status' => $newStatus,
            'resolution_notes' => $request->resolution_notes,
            'resolved_by' => $resolvedBy,
            'resolved_at' => $resolvedAt,
        ]);

        return redirect()->back()->with('success', "Status blocker berhasil diperbarui menjadi: {$newStatus}");
    }

    /**
     * Mark blocker as done (quick action)
     */
    public function markDone($request_id)
    {
        $helpRequest = HelpRequest::findOrFail($request_id);

        // Stop any running timer for this help request
        $running = \App\Models\TimeLog::where('help_request_id', $helpRequest->request_id)
            ->whereNull('end_time')
            ->first();

        if ($running) {
            $endTime = now();
            $duration = $endTime->diffInSeconds($running->start_time);
            $running->update([
                'end_time' => $endTime,
                'duration_seconds' => $duration,
            ]);
        }

        $helpRequest->update([
            'status' => 'completed',
            'resolved_by' => Auth::user()->user_id,
            'resolved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Blocker berhasil ditandai selesai.');
    }
}
