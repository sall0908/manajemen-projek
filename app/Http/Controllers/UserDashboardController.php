<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
    $user = Auth::user(); // Ambil data user yang sedang login
    /** @var \App\Models\User $user */
    // Tentukan apakah user sedang 'working' berdasarkan assignment ke proyek aktif
    $isWorking = $user->projectsAsMember()->where('status', '!=', 'done')->exists();

        return view('users.dashboard', compact('user', 'isWorking'));
    }
}
