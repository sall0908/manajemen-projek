<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Project;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Simpan komentar pada card di dalam project, dapat diakses semua role yang login.
     */
    public function store(Request $request, $project_id): RedirectResponse
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
            // Isi juga kolom 'content' untuk kompatibilitas skema DB yang mewajibkan kolom ini
            'content'      => $request->content,
            'comment_type' => 'feedback',
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}