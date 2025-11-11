<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua proyek yang dibuat/dipimpin oleh team leader ini + eager load cards
        // Urutkan berdasarkan difficulty: hard > medium > easy, lalu created_at
        $projects = Project::where('created_by', $user->user_id)
            ->with(['cards' => function ($q) {
                $q->select('card_id', 'project_id', 'card_title', 'status');
            }])
            ->orderByRaw("CASE difficulty 
                WHEN 'hard' THEN 1 
                WHEN 'medium' THEN 2 
                WHEN 'easy' THEN 3 
                ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Pisahkan proyek yang sudah selesai dan belum selesai
        $activeProjects = $projects->where('status', '!=', 'done');
        $completedProjects = $projects->where('status', 'done');

        return view('teamleader.dashboard', compact('activeProjects', 'completedProjects'));
    }

    public function show($project_id)
    {
        $project = Project::findOrFail($project_id);
        return response()->json($project);
    }
}
