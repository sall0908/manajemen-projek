<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Card;
use App\Models\TimeLog;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BoardController extends Controller
{
    public function index($project_id)
    {
        $project = Project::with(['cards.subTasks.timeLogs', 'cards.assignedUsers', 'cards.comments.user', 'cards.timeLogs.user'])->findOrFail($project_id);

        // Dapatkan semua SubTasks dari cards di project ini dan group by status
        $allSubTasks = $project->cards->flatMap(fn($card) => $card->subTasks);

        $todo = $allSubTasks->where('status', 'todo')->values();
        $inProgress = $allSubTasks->where('status', 'in_progress')->values();
        $review = $allSubTasks->where('status', 'review')->values();
        $done = $allSubTasks->where('status', 'done')->values();

        return view('designer.board', compact('project', 'todo', 'inProgress', 'review', 'done'));
    }

    public function store(Request $request, $project_id)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,card_id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::with('members')->findOrFail($project_id);

        // Pastikan designer hanya bisa menambah subtask di proyek mereka
        if (!$project->members->contains('user_id', Auth::user()->user_id)) {
            abort(403, 'Unauthorized access');
        }

        // Pastikan card milik project ini
        $card = Card::where('card_id', $request->card_id)
            ->where('project_id', $project_id)
            ->firstOrFail();

        // Buat SubTask baru di dalam card
        $subtask = \App\Models\SubTask::create([
            'card_id' => $card->card_id,
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => false,
            'status' => 'todo',
        ]);

        return redirect()->back()->with('success', 'Subtask berhasil dibuat');
    }

    public function markInProgress($card_id)
    {
        $card = Card::findOrFail($card_id);

        // Pastikan designer hanya bisa update card yang ditugaskan padanya
        $isAssigned = \Illuminate\Support\Facades\DB::table('card_assignments')
            ->where('card_id', $card_id)
            ->where('user_id', Auth::user()->user_id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Unauthorized access');
        }

        $card->update(['status' => 'in_progress']);

        // Buat time log baru untuk tracking waktu pengerjaan
        TimeLog::create([
            'card_id' => $card_id,
            'user_id' => Auth::user()->user_id,
            'start_time' => Carbon::now(),
            'end_time' => null,
            'duration_seconds' => 0,
        ]);

        return redirect()->back()->with('success', 'Card moved to In Progress');
    }

    public function markReview($card_id)
    {
        $card = Card::findOrFail($card_id);

        $isAssigned = \Illuminate\Support\Facades\DB::table('card_assignments')
            ->where('card_id', $card_id)
            ->where('user_id', Auth::user()->user_id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Unauthorized access');
        }

        $card->update(['status' => 'review']);

        return redirect()->back()->with('success', 'Card moved to Review');
    }

    public function markDone($card_id)
    {
        $card = Card::findOrFail($card_id);

        $isAssigned = \Illuminate\Support\Facades\DB::table('card_assignments')
            ->where('card_id', $card_id)
            ->where('user_id', Auth::user()->user_id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Unauthorized access');
        }

        $card->update(['status' => 'done']);

        return redirect()->back()->with('success', 'Card marked as Done');
    }

    public function stopTimeLog($card_id)
    {
        $card = Card::findOrFail($card_id);

        // Pastikan designer hanya bisa stop time log di card yang ditugaskan padanya
        $isAssigned = \Illuminate\Support\Facades\DB::table('card_assignments')
            ->where('card_id', $card_id)
            ->where('user_id', Auth::user()->user_id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Unauthorized access');
        }

        // Cari time log yang masih berjalan
        $timeLog = TimeLog::where('card_id', $card_id)
            ->where('user_id', Auth::user()->user_id)
            ->whereNull('end_time')
            ->first();

        if ($timeLog) {
            $endTime = Carbon::now();
            $duration = $endTime->diffInSeconds($timeLog->start_time);

            $timeLog->update([
                'end_time' => $endTime,
                'duration_seconds' => $duration,
            ]);

            return redirect()->back()->with('success', 'Time log stopped successfully');
        }

        return redirect()->back()->with('error', 'No running time log found');
    }

    public function getRunningTimeLog($card_id)
    {
        $card = Card::findOrFail($card_id);

        // Pastikan designer hanya bisa lihat time log di card yang ditugaskan padanya
        $isAssigned = \Illuminate\Support\Facades\DB::table('card_assignments')
            ->where('card_id', $card_id)
            ->where('user_id', Auth::user()->user_id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Unauthorized access');
        }

        $timeLog = TimeLog::where('card_id', $card_id)
            ->where('user_id', Auth::user()->user_id)
            ->whereNull('end_time')
            ->first();

        if ($timeLog) {
            $elapsedTime = Carbon::now()->diffInSeconds($timeLog->start_time);
            return response()->json([
                'running' => true,
                'elapsed_seconds' => $elapsedTime,
                'formatted_time' => gmdate('H:i:s', $elapsedTime)
            ]);
        }

        return response()->json(['running' => false]);
    }

    // Toggle sub-task completed status
    public function toggleSubTask($sub_task_id)
    {
        $subTask = SubTask::with('card.project')->findOrFail($sub_task_id);

        // Pastikan user adalah member dari project
        $project = $subTask->card->project;
        if (!$project->members->contains('user_id', Auth::user()->user_id)) {
            abort(403, 'Unauthorized access');
        }

        // Toggle status
        $subTask->update([
            'is_completed' => !$subTask->is_completed
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $subTask->is_completed,
            'message' => $subTask->is_completed ? 'Sub-task ditandai selesai' : 'Sub-task dibatalkan selesai'
        ]);
    }

    // Update SubTask status (Designer restricted - only until review, not done)
    public function updateSubtaskStatus($sub_task_id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review',
        ]);

        $subTask = SubTask::with('card.project')->findOrFail($sub_task_id);

        // Pastikan user adalah member dari project
        $project = $subTask->card->project;
        if (!$project->members->contains('user_id', Auth::user()->user_id)) {
            abort(403, 'Unauthorized access');
        }

        // Designer tidak boleh set status 'done'
        if ($request->status === 'done') {
            abort(403, 'Designer tidak dapat menandai SubTask sebagai Done. Hanya TeamLeader yang dapat melakukan ini.');
        }

        $subTask->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'SubTask status berhasil diperbarui');
    }

    public function startTimerSubtask($sub_task_id)
    {
        $subTask = SubTask::with('card.project')->findOrFail($sub_task_id);

        // Pastikan user adalah member dari project
        $project = $subTask->card->project;
        if (!$project->members->contains('user_id', Auth::user()->user_id)) {
            abort(403, 'Unauthorized access');
        }

        // Hentikan timer yang masih berjalan di SubTask lain
        TimeLog::where('sub_task_id', '!=', $sub_task_id)
            ->where('user_id', Auth::user()->user_id)
            ->whereNull('end_time')
            ->update([
                'end_time' => Carbon::now(),
                'duration_seconds' => DB::raw('TIMESTAMPDIFF(SECOND, start_time, NOW())')
            ]);

        // Cari time log yang masih berjalan untuk SubTask ini
        $runningLog = TimeLog::where('sub_task_id', $sub_task_id)
            ->where('user_id', Auth::user()->user_id)
            ->whereNull('end_time')
            ->first();

        // Jika belum ada, buat yang baru
        if (!$runningLog) {
            TimeLog::create([
                'sub_task_id' => $sub_task_id,
                'user_id' => Auth::user()->user_id,
                'start_time' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success', 'Timer started');
    }

    public function stopTimerSubtask($sub_task_id)
    {
        $subTask = SubTask::with('card.project')->findOrFail($sub_task_id);

        // Pastikan user adalah member dari project
        $project = $subTask->card->project;
        if (!$project->members->contains('user_id', Auth::user()->user_id)) {
            abort(403, 'Unauthorized access');
        }

        // Cari time log yang masih berjalan
        $timeLog = TimeLog::where('sub_task_id', $sub_task_id)
            ->where('user_id', Auth::user()->user_id)
            ->whereNull('end_time')
            ->first();

        if ($timeLog) {
            $endTime = Carbon::now();
            $duration = $endTime->diffInSeconds($timeLog->start_time);

            $timeLog->update([
                'end_time' => $endTime,
                'duration_seconds' => $duration,
            ]);

            return redirect()->back()->with('success', 'Timer stopped');
        }

        return redirect()->back()->with('error', 'No running timer found');
    }
}
