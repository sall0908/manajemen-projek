<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua cards dari proyek yang user adalah member
        $projects = $user->projectsAsMember()->with('cards', 'leader')->get();

        // Flatten semua cards dari projects tersebut
        $cards = $projects->flatMap(function($project) {
            return $project->cards->map(function($card) use ($project) {
                $card->project_name = $project->project_name;
                $card->leader = $project->leader;
                return $card;
            });
        });

        return view('designer.dashboard', compact('user', 'cards', 'projects'));
    }
}
