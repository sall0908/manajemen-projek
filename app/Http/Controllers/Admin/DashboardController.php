<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Card;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik proyek
        $active = Project::where('status', '!=', 'done')->count();
        $completed = Project::where('status', 'done')->count();
        $deleted = Project::onlyTrashed()->count(); // Proyek yang dihapus (soft delete)
        $total = Project::withTrashed()->count(); // Total termasuk yang dihapus

        // Data untuk kalender (proyek dengan deadline)
        $projectsWithDeadline = Project::whereNotNull('deadline')
            ->select('project_id', 'project_name', 'deadline', 'status')
            ->get()
            ->map(function($project) {
                return [
                    'title' => $project->project_name,
                    'start' => Carbon::parse($project->deadline)->format('Y-m-d'),
                    'url' => route('admin.projects.show', $project->project_id),
                    'className' => $project->status === 'done' ? 'bg-green-500' :
                                 ($project->status === 'in_progress' ? 'bg-blue-500' :
                                 ($project->status === 'review' ? 'bg-yellow-500' : 'bg-gray-500'))
                ];
            });

        // --- User productivity: total time (seconds) and completed cards per user ---
        $users = User::all();
        $usersProductivity = $users->map(function($user) {
            $totalSeconds = $user->timeLogs()->sum('duration_seconds') ?? 0;
            $completedCards = $user->assignedCards()->where('status', 'done')->count();
            $assignedCards = $user->assignedCards()->count();

            return [
                'user_id' => $user->user_id,
                'full_name' => $user->full_name,
                'role' => $user->role,
                'status' => $user->status,
                'total_seconds' => (int) $totalSeconds,
                'total_time' => gmdate('H:i:s', $totalSeconds),
                'completed_cards' => $completedCards,
                'assigned_cards' => $assignedCards,
                'detail_url' => route('admin.reports.userDetail', $user->user_id),
            ];
        })->sortByDesc('total_seconds')->values();

        // --- Projects board data: percent progress and deadline ---
        $projects = Project::with('cards')->get();
        $projectsBoard = $projects->map(function($p) {
            $totalCards = $p->cards->count();
            $doneCards = $p->cards->where('status', 'done')->count();
            $percent = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;

            return [
                'project_id' => $p->project_id,
                'project_name' => $p->project_name,
                'status' => $p->status,
                'deadline' => $p->deadline ? Carbon::parse($p->deadline)->format('d M Y') : null,
                'progress_percent' => $percent,
                'cards_total' => $totalCards,
                'done_cards' => $doneCards,
                'detail_url' => route('admin.projects.show', $p->project_id),
            ];
        })->sortByDesc('progress_percent')->values();

        return view('admin.dashboard', compact('completed', 'active', 'deleted', 'total', 'projectsWithDeadline', 'usersProductivity', 'projectsBoard'));
    }
}
