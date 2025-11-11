<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Ambil users + jumlah time log yang sedang berjalan (end_time null)
        $users = User::withCount([
            'timeLogs as running_time_logs_count' => function ($q) {
                $q->whereNull('end_time');
            }
        ])->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // ✅ Sesuaikan dengan enum di database
        $validRoles = ['admin', 'teamlead', 'designer', 'developer', 'tester', 'user'];

        if (!in_array($request->role, $validRoles)) {
            return redirect()->back()->with('error', 'Role tidak valid!');
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui!');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validStatuses = ['active', 'inactive'];

        if (!in_array($request->status, $validStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid!');
        }

        $user->status = $request->status;
        $user->save();

        return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->user_id == auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
