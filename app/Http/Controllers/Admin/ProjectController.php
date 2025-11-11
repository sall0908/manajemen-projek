<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Comment;
use App\Models\Card;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    // Tampilkan semua project + cards + komentar
    public function index()
    {
        $projects = Project::with(['leader', 'members', 'cards.comments.user'])
            ->orderByRaw("CASE difficulty
                WHEN 'hard' THEN 1
                WHEN 'medium' THEN 2
                WHEN 'easy' THEN 3
                ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get(); // Tidak menampilkan yang dihapus (soft delete)
        return view('admin.projects.index', compact('projects'));
    }

    // Form buat project baru
    public function create()
    {
        $leaders = User::where('role', 'teamlead')->get();
        $members = User::whereIn('role', ['designer', 'developer'])->get();
        return view('admin.projects.create', compact('leaders', 'members'));
    }

    // Detail project lengkap
    public function show($project_id)
    {
        $project = Project::with([
            'leader',
            'members',
            'boards',
            'cards.assignedUsers',
            'cards.subTasks',
            'cards.timeLogs.user',
        ])->findOrFail($project_id);

        return view('admin.projects.show', compact('project'));
    }

    // Simpan project baru + subtask
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'leader_id'    => 'required|exists:users,user_id',
            'deadline'     => 'nullable|date',
            'difficulty'   => 'required|in:easy,medium,hard',
            'members'      => 'array',
            'members.*'    => 'exists:users,user_id',
            'subtasks'     => 'array',
            'subtasks.*.title' => 'nullable|string|max:255',
            'subtasks.*.description' => 'nullable|string|max:1000',
        ]);

        // buat project utama
        $project = Project::create([
            'project_name' => $request->project_name,
            'description'  => $request->description,
            'created_by'   => $request->leader_id,
            'deadline'     => $request->deadline,
            'status'       => 'todo', // default status
            'difficulty'   => $request->difficulty,
        ]);

        // tambah anggota ke proyek + isi pivot role & joined_at
        if ($request->has('members')) {
            $selected = User::whereIn('user_id', $request->members)->get();
            $pivotData = [];
            foreach ($selected as $member) {
                $pivotData[$member->user_id] = [
                    'role' => $member->role,
                    'joined_at' => now(),
                ];
            }
            $project->members()->attach($pivotData);
        }

        // Buat board default sesuai skema (board_name)
        $project->boards()->createMany([
            ['board_name' => 'To Do', 'position' => 1],
            ['board_name' => 'In Progress', 'position' => 2],
            ['board_name' => 'Done', 'position' => 3],
        ]);

        // tambah subtask ke tabel cards
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $task) {
                if (!empty($task['title'])) {
                    Card::create([
                        'project_id'  => $project->project_id,
                        'card_title'       => $task['title'],
                        'description' => $task['description'] ?? null,
                        'status'      => 'todo',
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects')->with('success', 'Project dan subtasks berhasil dibuat!');
    }

    // Tambah komentar untuk card tertentu
    public function addComment(Request $request, $project_id): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'card_id' => 'required|exists:cards,card_id',
        ]);

        $project = Project::findOrFail($project_id);

        Comment::create([
            'project_id'   => $project->project_id,
            'card_id'      => $request->card_id,
            'user_id'      => auth()->user()->user_id,
            'comment_text' => $request->content,
            // Isi juga kolom 'content' untuk kompatibilitas skema DB
            'content'      => $request->content,
            'comment_type' => 'feedback',
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    // Mark project as done
    public function markAsDone($project_id): RedirectResponse
    {
        $project = Project::findOrFail($project_id);
        $project->update(['status' => 'done']);

        return redirect()->back()->with('success', 'Proyek berhasil ditandai selesai!');
    }

    // Hapus project (soft delete)
    public function destroy($project_id): RedirectResponse
    {
        $project = Project::with(['cards', 'boards', 'members'])->findOrFail($project_id);

        // Soft delete project (tidak hapus dependensi karena bisa di-restore)
        $project->delete();

        return redirect()->route('admin.projects')->with('success', 'Project berhasil dihapus.');
    }

    // Invite member by name (admin action)
    public function inviteMembers(Request $request, $project_id)
    {
        $request->validate([
            'member_name' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($project_id);

        $name = trim($request->member_name);

        // Try exact username or full_name first
        $user = User::where('username', $name)
                    ->orWhere('full_name', $name)
                    ->first();

        // Fallback to partial match on full_name
        if (!$user) {
            $user = User::where('full_name', 'like', "%{$name}%")->first();
        }

        if (!$user) {
            return redirect()->back()->with('error', "User tidak ditemukan: {$name}");
        }

        if (!in_array($user->role, ['developer', 'designer'])) {
            return redirect()->back()->with('error', 'Hanya developer atau designer yang bisa ditambahkan sebagai member proyek.');
        }

        // Cegah menambahkan jika user sudah punya project aktif
        $hasActive = $user->projectsAsMember()->where('status', '!=', 'done')->exists();
        if ($hasActive) {
            return redirect()->back()->with('error', 'User sudah memiliki proyek aktif.');
        }

        // Attach if not already member
        if (!$project->members()->where('user_id', $user->user_id)->exists()) {
            $project->members()->attach($user->user_id, [
                'role' => $user->role,
                'joined_at' => now(),
            ]);
            return redirect()->back()->with('success', "Member {$user->full_name} berhasil ditambahkan ke proyek.");
        }

        return redirect()->back()->with('warning', 'User sudah menjadi anggota proyek.');
    }

}
