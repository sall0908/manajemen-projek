<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Card;
use App\Models\Project;
use App\Models\SubTask;
use App\Models\TimeLog;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // laporan user
    public function users()
    {
        // ambil data semua user + jumlah projek
        $users = User::withCount(['projectsAsLeader', 'projectsAsMember'])->get();

        // untuk admin, tampilkan jumlah proyek yang dibuat secara global
        $totalProjects = Project::count();
        foreach ($users as $u) {
            if ($u->role === 'admin') {
                $u->projects_created_admin_count = $totalProjects;
            }
        }

        return view('admin.reports.users', compact('users'));
    }

    // detail laporan user
    public function userDetail($id)
    {
        $user = User::findOrFail($id);

        // Data untuk Team Leader
        $cardsCreated = 0;
        $projectsCompleted = 0;
        $projectsDeleted = 0; // Tidak bisa track karena hard delete
        $projectsCreated = collect();
        $projectsJoined = collect();

        // Data untuk Developer/Designer
        $subTasksCreated = 0;
        $subTasksCompleted = 0;
        $totalTimeWorked = 0; // dalam detik
        $timeLogs = collect();
        $assignedCards = collect();

        if ($user->role === 'teamlead') {
            $cardsCreated = Card::where('created_by', $user->user_id)->count();
            $projectsCompleted = Project::where('created_by', $user->user_id)
                ->where('status', 'done')
                ->count();

            $projectsJoined = Project::where(function($q) use ($user) {
                    $q->where('created_by', $user->user_id)
                      ->orWhereHas('members', function ($query) use ($user) {
                          $query->where('project_members.user_id', $user->user_id);
                      });
                })
                ->withCount('cards')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role === 'admin') {
            $projectsCreated = Project::withCount('cards')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Untuk Developer/Designer
            // Ambil semua card yang di-assign ke user ini menggunakan relasi Eloquent
            $assignedCards = $user->assignedCards()
                ->with('project')
                ->withCount('subTasks')
                ->get();

            // Ambil card IDs untuk query lainnya
            $assignedCardIds = $assignedCards->pluck('card_id')->toArray();

            // Sub-task dari card yang di-assign (kita anggap sub-task dibuat oleh user yang di-assign ke card)
            $subTasksCreated = SubTask::whereIn('card_id', $assignedCardIds)->count();
            $subTasksCompleted = SubTask::whereIn('card_id', $assignedCardIds)
                ->where('is_completed', true)
                ->count();

            // Time logs user
            $timeLogs = TimeLog::where('user_id', $user->user_id)
                ->whereNotNull('end_time') // Hanya yang sudah selesai
                ->with('card.project')
                ->orderBy('start_time', 'desc')
                ->get();

            // Total waktu kerja (dalam detik)
            $totalTimeWorked = TimeLog::where('user_id', $user->user_id)
                ->whereNotNull('end_time')
                ->sum('duration_seconds') ?? 0;

            $projectsJoined = $user->projectsAsMember()
                ->withCount('cards')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Format total waktu kerja
        $hours = floor($totalTimeWorked / 3600);
        $minutes = floor(($totalTimeWorked % 3600) / 60);
        $seconds = $totalTimeWorked % 60;
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Siapkan data untuk view
        $data = [
            'user' => $user,
            'cardsCreated' => $cardsCreated,
            'projectsCompleted' => $projectsCompleted,
            'projectsDeleted' => $projectsDeleted,
            'subTasksCreated' => $subTasksCreated,
            'subTasksCompleted' => $subTasksCompleted,
            'timeLogs' => $timeLogs,
            'formattedTime' => $formattedTime,
            'totalTimeWorked' => $totalTimeWorked,
        ];

        // Tambahkan data khusus berdasarkan role
        if ($user->role === 'admin') {
            $data['projectsCreated'] = $projectsCreated;
        } elseif ($user->role !== 'teamlead') {
            $data['assignedCards'] = $assignedCards;
        }
        $data['projectsJoined'] = $projectsJoined;

        return view('admin.reports.user-detail', $data);
    }

    // laporan proyek
    public function projects()
    {
        // ambil data semua proyek dengan relasi
        // Urutkan berdasarkan difficulty: hard > medium > easy
        $projects = Project::with(['leader', 'members'])
            ->withCount(['cards', 'members'])
            ->orderByRaw("CASE difficulty 
                WHEN 'hard' THEN 1 
                WHEN 'medium' THEN 2 
                WHEN 'easy' THEN 3 
                ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.projects', compact('projects'));
    }

    // detail laporan proyek
    public function projectDetail($id)
    {
        $project = Project::with([
            'leader',
            'members',
            'cards' => function($query) {
                $query->with(['creator', 'assignedUsers', 'subTasks', 'timeLogs.user'])
                      ->orderBy('created_at', 'desc');
            },
            'boards'
        ])->findOrFail($id);

        // Statistik proyek
        $totalCards = $project->cards->count();
        $cardsByStatus = [
            'todo' => $project->cards->where('status', 'todo')->count(),
            'in_progress' => $project->cards->where('status', 'in_progress')->count(),
            'review' => $project->cards->where('status', 'review')->count(),
            'done' => $project->cards->where('status', 'done')->count(),
        ];

        // Total sub-task
        $totalSubTasks = 0;
        $completedSubTasks = 0;
        foreach ($project->cards as $card) {
            $totalSubTasks += $card->subTasks->count();
            $completedSubTasks += $card->subTasks->where('is_completed', true)->count();
        }

        // Total waktu kerja dari semua time logs
        $totalTimeWorked = TimeLog::whereIn('card_id', $project->cards->pluck('card_id'))
            ->whereNotNull('end_time')
            ->sum('duration_seconds') ?? 0;

        // Format total waktu kerja
        $hours = floor($totalTimeWorked / 3600);
        $minutes = floor(($totalTimeWorked % 3600) / 60);
        $seconds = $totalTimeWorked % 60;
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Anggota tim dengan detail
        $teamMembers = $project->members->map(function($member) use ($project) {
            // Card yang di-assign ke member ini menggunakan relasi Eloquent
            $assignedCards = $member->assignedCards()
                ->whereIn('card_id', $project->cards->pluck('card_id'))
                ->get();
            
            $assignedCardIds = $assignedCards->pluck('card_id')->toArray();

            // Sub-task dari card yang di-assign
            $subTasksCount = SubTask::whereIn('card_id', $assignedCardIds)->count();
            $subTasksCompletedCount = SubTask::whereIn('card_id', $assignedCardIds)
                ->where('is_completed', true)
                ->count();

            // Total waktu kerja member di proyek ini
            $memberTimeWorked = TimeLog::where('user_id', $member->user_id)
                ->whereIn('card_id', $assignedCardIds)
                ->whereNotNull('end_time')
                ->sum('duration_seconds') ?? 0;

            return [
                'user' => $member,
                'assigned_cards_count' => count($assignedCardIds),
                'sub_tasks_count' => $subTasksCount,
                'sub_tasks_completed' => $subTasksCompletedCount,
                'time_worked' => $memberTimeWorked,
            ];
        });

        return view('admin.reports.project-detail', compact(
            'project',
            'totalCards',
            'cardsByStatus',
            'totalSubTasks',
            'completedSubTasks',
            'formattedTime',
            'totalTimeWorked',
            'teamMembers'
        ));
    }

    // Export PDF laporan user
    public function userDetailPdf($id)
    {
        $user = User::findOrFail($id);

        // Data untuk Team Leader
        $cardsCreated = 0;
        $projectsCompleted = 0;
        $projectsDeleted = 0;
        $projectsCreated = collect();
        $projectsJoined = collect();

        // Data untuk Developer/Designer
        $subTasksCreated = 0;
        $subTasksCompleted = 0;
        $totalTimeWorked = 0;
        $timeLogs = collect();
        $assignedCards = collect();

        if ($user->role === 'teamlead') {
            $cardsCreated = Card::where('created_by', $user->user_id)->count();
            $projectsCompleted = Project::where('created_by', $user->user_id)
                ->where('status', 'done')
                ->count();

            $projectsJoined = Project::where(function($q) use ($user) {
                    $q->where('created_by', $user->user_id)
                      ->orWhereHas('members', function ($query) use ($user) {
                          $query->where('project_members.user_id', $user->user_id);
                      });
                })
                ->withCount('cards')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role === 'admin') {
            $projectsCreated = Project::withCount('cards')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Untuk Developer/Designer - gunakan relasi Eloquent
            $assignedCards = $user->assignedCards()
                ->with('project')
                ->withCount('subTasks')
                ->get();

            // Ambil card IDs untuk query lainnya
            $assignedCardIds = $assignedCards->pluck('card_id')->toArray();

            $subTasksCreated = SubTask::whereIn('card_id', $assignedCardIds)->count();
            $subTasksCompleted = SubTask::whereIn('card_id', $assignedCardIds)
                ->where('is_completed', true)
                ->count();

            $timeLogs = TimeLog::where('user_id', $user->user_id)
                ->whereNotNull('end_time')
                ->with('card.project')
                ->orderBy('start_time', 'desc')
                ->get();

            $totalTimeWorked = TimeLog::where('user_id', $user->user_id)
                ->whereNotNull('end_time')
                ->sum('duration_seconds') ?? 0;

            $projectsJoined = $user->projectsAsMember()
                ->withCount('cards')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $hours = floor($totalTimeWorked / 3600);
        $minutes = floor(($totalTimeWorked % 3600) / 60);
        $seconds = $totalTimeWorked % 60;
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $data = [
            'user' => $user,
            'cardsCreated' => $cardsCreated,
            'projectsCompleted' => $projectsCompleted,
            'projectsDeleted' => $projectsDeleted,
            'subTasksCreated' => $subTasksCreated,
            'subTasksCompleted' => $subTasksCompleted,
            'timeLogs' => $timeLogs,
            'formattedTime' => $formattedTime,
            'totalTimeWorked' => $totalTimeWorked,
        ];

        if ($user->role === 'admin') {
            $data['projectsCreated'] = $projectsCreated;
        } elseif ($user->role !== 'teamlead') {
            $data['assignedCards'] = $assignedCards;
        }
        $data['projectsJoined'] = $projectsJoined;

        try {
            // Gunakan service container langsung
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.reports.user-detail-pdf', $data);
            $pdf->setPaper('a4', 'portrait');
            return $pdf->download('laporan-user-' . $user->username . '-' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    // Export PDF laporan proyek
    public function projectDetailPdf($id)
    {
        $project = Project::with([
            'leader',
            'members',
            'cards' => function($query) {
                $query->with(['creator', 'assignedUsers', 'subTasks', 'timeLogs.user'])
                      ->orderBy('created_at', 'desc');
            },
            'boards'
        ])->findOrFail($id);

        $totalCards = $project->cards->count();
        $cardsByStatus = [
            'todo' => $project->cards->where('status', 'todo')->count(),
            'in_progress' => $project->cards->where('status', 'in_progress')->count(),
            'review' => $project->cards->where('status', 'review')->count(),
            'done' => $project->cards->where('status', 'done')->count(),
        ];

        $totalSubTasks = 0;
        $completedSubTasks = 0;
        foreach ($project->cards as $card) {
            $totalSubTasks += $card->subTasks->count();
            $completedSubTasks += $card->subTasks->where('is_completed', true)->count();
        }

        $totalTimeWorked = TimeLog::whereIn('card_id', $project->cards->pluck('card_id'))
            ->whereNotNull('end_time')
            ->sum('duration_seconds') ?? 0;

        $hours = floor($totalTimeWorked / 3600);
        $minutes = floor(($totalTimeWorked % 3600) / 60);
        $seconds = $totalTimeWorked % 60;
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $teamMembers = $project->members->map(function($member) use ($project) {
            // Card yang di-assign ke member ini menggunakan relasi Eloquent
            $assignedCards = $member->assignedCards()
                ->whereIn('card_id', $project->cards->pluck('card_id'))
                ->get();
            
            $assignedCardIds = $assignedCards->pluck('card_id')->toArray();

            $subTasksCount = SubTask::whereIn('card_id', $assignedCardIds)->count();
            $subTasksCompletedCount = SubTask::whereIn('card_id', $assignedCardIds)
                ->where('is_completed', true)
                ->count();

            $memberTimeWorked = TimeLog::where('user_id', $member->user_id)
                ->whereIn('card_id', $assignedCardIds)
                ->whereNotNull('end_time')
                ->sum('duration_seconds') ?? 0;

            return [
                'user' => $member,
                'assigned_cards_count' => count($assignedCardIds),
                'sub_tasks_count' => $subTasksCount,
                'sub_tasks_completed' => $subTasksCompletedCount,
                'time_worked' => $memberTimeWorked,
            ];
        });

        $data = compact(
            'project',
            'totalCards',
            'cardsByStatus',
            'totalSubTasks',
            'completedSubTasks',
            'formattedTime',
            'totalTimeWorked',
            'teamMembers'
        );

        try {
            // Gunakan service container langsung
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.reports.project-detail-pdf', $data);
            $pdf->setPaper('a4', 'portrait');
            $filename = 'laporan-proyek-' . preg_replace('/[^a-z0-9]+/', '-', strtolower($project->project_name)) . '-' . date('Y-m-d') . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }
}
