<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TeamLeaderController extends Controller
{
    // 🔁 Ubah status proyek
    public function updateStatus($id, Request $request)
    {
        $project = Project::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['todo', 'in_progress', 'review', 'done'])) {
            return response()->json(['message' => 'Status tidak valid!'], 400);
        }

        $project->update(['status' => $status]);

        return response()->json(['message' => "Status proyek berhasil diubah ke {$status}!"]);
    }

    // 👥 Invite member
    public function inviteMember($id, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'role' => 'required|in:developer,designer',
        ]);

        $project = Project::findOrFail($id);
        $user = User::find($request->user_id);

        if ($user->role !== $request->role) {
            return response()->json([
                'message' => "⚠️ Role user tidak sesuai. User ini adalah {$user->role}, bukan {$request->role}."
            ], 400);
        }

        $project->members()->syncWithoutDetaching([$user->user_id]);

        return response()->json(['message' => "✅ {$user->full_name} berhasil diundang ke proyek ini sebagai {$user->role}."]);
    }

    // 🔍 Show detail proyek (AJAX)
    public function show($id)
    {
        $project = Project::with('members')->findOrFail($id);
        return response()->json($project);
    }
}
