<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Card;
use App\Models\SubTask;
use App\Models\User;

class BoardController extends Controller
{
    // ============================
    // 📋 TAMPILKAN BOARD PROJECT (SUBTASK VIEW)
    // ============================
    public function index($project_id)
    {
        // Muat project + cards + subtasks
        $project = Project::with([
            'members',
            'cards.subTasks',
            'cards.comments.user',
            'cards.timeLogs.user'
        ])->findOrFail($project_id);

        // Dapatkan semua subtask dari semua cards dan pisahkan berdasarkan status
        $allSubTasks = [];
        foreach ($project->cards as $card) {
            foreach ($card->subTasks as $subTask) {
                $subTask->card = $card; // Tambah card info ke subtask
                $allSubTasks[] = $subTask;
            }
        }

        $todo = collect($allSubTasks)->where('status', 'todo')->values();
        $inProgress = collect($allSubTasks)->where('status', 'in_progress')->values();
        $review = collect($allSubTasks)->where('status', 'review')->values();
        $done = collect($allSubTasks)->where('status', 'done')->values();

        return view('teamleader.board', compact('project', 'todo', 'inProgress', 'review', 'done'));
    }

    // ============================
    // ➕ TAMBAH CARD BARU
    // ============================
    public function store(Request $request, $project_id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,user_id',
        ]);

        // Buat card baru
        $card = Card::create([
            'project_id'  => $project_id,
            'card_title'  => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status'      => 'todo',
            'created_by'  => auth()->user()->user_id,
        ]);

        // Jika ada user yang ditentukan, catat penugasan di tabel pivot
        if (!empty($validated['assigned_user_id'])) {
            \Illuminate\Support\Facades\DB::table('card_assignments')->insert([
                'card_id' => $card->card_id,
                'user_id' => $validated['assigned_user_id'],
                'assignment_status' => 'assigned',
                'assigned_at' => now(),
            ]);
        }

        return back()->with('success', 'Card berhasil ditambahkan!');
    }

    // ============================
    // 🔄 UPDATE STATUS CARD
    // ============================

    public function markInProgress($card_id, Request $request)
    {
        $card = Card::with('project')->findOrFail($card_id);
        $card->update(['status' => 'in_progress']);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Card dipindah ke In Progress!']);
        }
        return redirect()->back()->with('success', 'Card dipindah ke In Progress!');
    }

    public function markReview($card_id, Request $request)
    {
        $card = Card::with('project')->findOrFail($card_id);
        $card->update(['status' => 'review']);
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Card dikirim untuk Review!']);
        }
        return redirect()->back()->with('success', 'Card dikirim untuk Review!');
    }

    public function markDone($card_id, Request $request)
    {
        $card = Card::with('project')->findOrFail($card_id);
        $card->update(['status' => 'done']);

        // Update status project jika semua card selesai
        $unfinished = Card::where('project_id', $card->project_id)
                          ->where('status', '!=', 'done')
                          ->count();
        if ($unfinished === 0) {
            $card->project->update(['status' => 'done']);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Card selesai!']);
        }
        return redirect()->back()->with('success', 'Card selesai!');
    }

    // ============================
    // 🔄 UPDATE SUBTASK STATUS (TeamLeader)
    // ============================
    public function updateSubtaskStatus($sub_task_id, Request $request)
    {
        $subtask = SubTask::findOrFail($sub_task_id);

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $subtask->update(['status' => $validated['status']]);

        if ($request->expectsJson()) {
            return response()->json(['message' => "Subtask status diubah ke {$validated['status']}!"]);
        }
        return redirect()->back()->with('success', "Subtask status diubah ke {$validated['status']}!");
    }

    // ============================
    // 🧩 SEED DEFAULT CARDS PER ROLE
    // ============================
    public function seedDefaultCards($project_id)
    {
        $project = Project::with('members')->findOrFail($project_id);

        // Ambil semua member dan buatkan card sesuai role
        foreach ($project->members as $member) {
            $title = $member->role === 'designer'
                ? 'Design untuk proyek: ' . $project->project_name
                : 'Development untuk proyek: ' . $project->project_name;

            $card = Card::create([
                'project_id'  => $project->project_id,
                'card_title'  => $title,
                'description' => 'Auto-generated task dari Team Leader',
                'status'      => 'todo',
                'created_by'  => auth()->user()->user_id,
            ]);

            // Catat penugasan ke member tersebut di tabel pivot
            \Illuminate\Support\Facades\DB::table('card_assignments')->insert([
                'card_id' => $card->card_id,
                'user_id' => $member->user_id,
                'assignment_status' => 'assigned',
                'assigned_at' => now(),
            ]);
        }

        return back()->with('success', 'Card default berhasil dibuat untuk semua member proyek.');
    }

    public function backToTodo($card_id, Request $request)
    {
        $card = Card::findOrFail($card_id);
        $card->update(['status' => 'todo']);

        // Jika project sempat done, ubah lagi ke in_progress
        $project = $card->project;
        if ($project && $project->status === 'done') {
            $project->update(['status' => 'in_progress']);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Card dikembalikan ke To Do!']);
        }
        return redirect()->back()->with('success', 'Card dikembalikan ke To Do!');
    }

    // ============================
    // ✅ UPDATE STATUS PROJECT
    // ============================
    public function markProjectDone($project_id)
    {
        $project = Project::find($project_id);
        if (!$project) {
            return response()->json(['message' => '❌ Proyek tidak ditemukan!'], 404);
        }

        $project->update(['status' => 'done']);
        return response()->json(['message' => '✅ Proyek berhasil diselesaikan!']);
    }

    // ============================
    // ❌ HAPUS CARD (oleh Team Leader)
    // ============================
    public function destroyCard($card_id, Request $request)
    {
        $card = Card::with('project')->findOrFail($card_id);

        // Pastikan pengguna saat ini adalah leader proyek
        $userId = auth()->user()->user_id;
        if (!$card->project || $card->project->created_by !== $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus card ini.');
        }

        // Hapus data terkait: subtasks, comments, timelogs, dan assignment pivot
    try {
            // Hapus subtasks
            if (method_exists($card, 'subTasks')) {
                $card->subTasks()->delete();
            }

            // Hapus komentar
            if (method_exists($card, 'comments')) {
                $card->comments()->delete();
            }

            // Hapus time logs
            if (method_exists($card, 'timeLogs')) {
                $card->timeLogs()->delete();
            }

            // Hapus assignment pivot
            \Illuminate\Support\Facades\DB::table('card_assignments')->where('card_id', $card->card_id)->delete();

            // Hapus card
            $card->delete();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Card berhasil dihapus.']);
            }
            return redirect()->back()->with('success', 'Card berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to delete card: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal menghapus card.'], 500);
            }
            return redirect()->back()->with('error', 'Gagal menghapus card.');
        }
    }

    // ===========================
    // 👥 INVITE MEMBER
    // ===========================
    public function inviteMember(Request $request, $project_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'role' => 'required|in:developer,designer',
        ]);

        $project = Project::findOrFail($project_id);
        $user = User::where('user_id', $request->user_id)->firstOrFail();

        // Validasi role sesuai input
        if ($user->role !== $request->role) {
            $message = "⚠️ Role user tidak sesuai. User ini adalah {$user->role}, bukan {$request->role}.";
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        // Batasi developer/designer hanya 1 proyek aktif
        if (in_array($user->role, ['developer', 'designer'])) {
            $activeProjectsCount = $user->projectsAsMember()->where('status', '!=', 'done')->count();
            if ($activeProjectsCount > 0) {
                $message = "⚠️ {$user->full_name} saat ini sedang mengerjakan proyek lain (status: working). Hanya boleh 1 proyek aktif.";
                if ($request->expectsJson()) {
                    return response()->json(['message' => $message], 400);
                }
                return redirect()->back()->with('error', $message);
            }
        }

        // Jika sudah member, jangan duplikasi
        if ($project->members->contains('user_id', $user->user_id)) {
            $message = "ℹ️ {$user->full_name} sudah menjadi member proyek ini.";
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 200);
            }
            return redirect()->back()->with('info', $message);
        }

        // Tambahkan ke pivot dengan kolom role & joined_at
        $project->members()->syncWithoutDetaching([
            $user->user_id => [
                'role' => $user->role,
                'joined_at' => now(),
            ]
        ]);

        $message = "✅ {$user->full_name} berhasil diundang sebagai {$request->role}!";
        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }
        return redirect()->back()->with('success', $message);
    }

    // ============================
    // ❌ HAPUS PROYEK
    // ============================
    public function destroy($project_id)
    {
        $project = Project::with('cards')->findOrFail($project_id);

        // Hapus semua card sebelum hapus project
        $project->cards()->delete();
        $project->delete();

        return response()->json(['message' => '🗑️ Proyek berhasil dihapus!']);
    }
}
