<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * 🟢 Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * 🟢 Proses login
     */
    public function login(Request $request)
    {
        // Validasi input form
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Set status aktif saat berhasil login (kecuali admin)
            if ($user && $user->role !== 'admin') {
                if ($user->status !== 'active') {
                    $user->status = 'active';
                    $user->save();
                }
            }

            // 🧭 Arahkan berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'teamlead':
                case 'teamleader':
                    return redirect()->route('teamleader.dashboard');
                case 'designer':
                    return redirect()->route('designer.dashboard');
                case 'developer':
                    return redirect()->route('developer.dashboard');
                default:
                    return redirect()->route('users.dashboard');
            }
        }

        // Jika gagal login
        return back()->withErrors([
            'login' => 'Email atau password salah!',
        ])->withInput();
    }

    /**
     * 🟢 Tampilkan form register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * 🟢 Proses register user baru
     */
    public function register(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'full_name'             => 'required|string|max:255',
            'username'              => 'required|string|max:255|unique:users,username',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:5|confirmed', // Harus ada password_confirmation di form
        ]);

        // Simpan user baru
        $user = User::create([
            'full_name' => $validated['full_name'],
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'password'  => bcrypt($validated['password']),
            'role'      => 'user',       // Default role
            'status'    => 'inactive',   // Default status
        ]);

        // Setelah register langsung login
        Auth::login($user);

        // Redirect ke dashboard user
        return redirect()->route('users.dashboard');
    }

    /**
     * 🟢 Logout user
     */
    public function logout(Request $request)
    {
        // Set status inactive sebelum logout (kecuali admin)
        $user = Auth::user();
        if ($user && $user->role !== 'admin' && $user->status !== 'inactive') {
            $user->status = 'inactive';
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
