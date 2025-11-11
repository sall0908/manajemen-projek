@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6">
        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent mb-2 sm:mb-3">
                👤 Manajemen Pengguna
            </h2>
            <p class="text-gray-600 text-sm sm:text-base">Kelola role dan status anggota tim secara real-time</p>
        </div>

        {{-- Auto refresh setiap 30 detik --}}
        <meta http-equiv="refresh" content="30">

        {{-- Pesan sukses / error --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl sm:rounded-2xl p-3 sm:p-4 mb-4 sm:mb-6 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800 font-medium text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl sm:rounded-2xl p-3 sm:p-4 mb-4 sm:mb-6 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-red-800 font-medium text-sm sm:text-base">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Mobile Cards View -->
        <div class="block lg:hidden space-y-4 mb-6">
            @foreach($users as $user)
                <div class="bg-white rounded-xl shadow-lg border border-blue-100 p-4 hover:shadow-xl transition duration-200">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-xs sm:text-sm mr-3">
                                {{ substr($user->full_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <form method="POST" action="{{ route('admin.users.updateRole', $user->user_id) }}" class="role-form">
                            @csrf
                            <select name="role" onchange="this.form.requestSubmit();"
                                class="w-full border border-blue-200 rounded-lg sm:rounded-xl px-3 py-2 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none cursor-pointer text-sm
                                    {{ $user->role == 'admin' ? 'bg-blue-50 text-blue-700' :
                                       ($user->role == 'teamlead' ? 'bg-purple-50 text-purple-700' :
                                       ($user->role == 'designer' ? 'bg-pink-50 text-pink-700' :
                                       ($user->role == 'developer' ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-700'))) }}">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="teamlead" {{ $user->role == 'teamlead' ? 'selected' : '' }}>Team Lead</option>
                                <option value="designer" {{ $user->role == 'designer' ? 'selected' : '' }}>Designer</option>
                                <option value="developer" {{ $user->role == 'developer' ? 'selected' : '' }}>Developer</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            <button type="submit" class="hidden">Simpan</button>
                        </form>
                    </div>

                    <!-- Status & Keterangan -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <!-- Status -->
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            @if($user->role == 'admin')
                                <span class="text-gray-400 text-xs italic">-</span>
                            @else
                                <div class="flex items-center">
                                    <span class="relative flex h-2 w-2 sm:h-3 sm:w-3 mr-1 sm:mr-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full
                                            {{ $user->status == 'active' ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 sm:h-3 sm:w-3
                                            {{ $user->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    </span>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $user->status == 'active' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Keterangan</p>
                            @if(in_array($user->role, ['designer', 'developer']))
                                @php
                                    // User is working only if assigned to an active project
                                    $isWorking = \App\Models\Project::whereHas('members', function($q) use ($user) {
                                        $q->where('project_members.user_id', $user->user_id);
                                    })->where('status', '!=', 'done')->exists();
                                @endphp
                                <div class="flex items-center">
                                    @if($isWorking)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                            Working
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-300">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                            Available
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-xs italic">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="text-center">
                        @if(Auth::user()->user_id !== $user->user_id)
                            <form method="POST" action="{{ route('admin.users.delete', $user->user_id) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                  class="inline-block w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-3 py-2 rounded-lg font-medium transition duration-200 flex items-center justify-center gap-2 w-full text-sm">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus Pengguna
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-sm italic">Akun Anda</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100 mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-600 to-indigo-700">
                            <th class="py-4 px-6 text-left font-semibold text-white">Nama</th>
                            <th class="py-4 px-6 text-left font-semibold text-white">Email</th>
                            <th class="py-4 px-6 text-left font-semibold text-white">Role</th>
                            <th class="py-4 px-6 text-left font-semibold text-white">Status</th>
                            <th class="py-4 px-6 text-left font-semibold text-white">Keterangan</th>
                            <th class="py-4 px-6 text-center font-semibold text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-blue-50 transition duration-200 group">
                                <!-- Nama -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                            {{ substr($user->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $user->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->username }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td class="py-4 px-6">
                                    <p class="text-gray-600">{{ $user->email }}</p>
                                </td>

                                <!-- Role Selection -->
                                <td class="py-4 px-6">
                                    <form method="POST" action="{{ route('admin.users.updateRole', $user->user_id) }}" class="role-form">
                                        @csrf
                                        <select name="role" onchange="this.form.requestSubmit();"
                                            class="w-full border border-blue-200 rounded-xl px-3 py-2 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none cursor-pointer
                                                {{ $user->role == 'admin' ? 'bg-blue-50 text-blue-700' :
                                                   ($user->role == 'teamlead' ? 'bg-purple-50 text-purple-700' :
                                                   ($user->role == 'designer' ? 'bg-pink-50 text-pink-700' :
                                                   ($user->role == 'developer' ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-700'))) }}">
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="teamlead" {{ $user->role == 'teamlead' ? 'selected' : '' }}>Team Lead</option>
                                            <option value="designer" {{ $user->role == 'designer' ? 'selected' : '' }}>Designer</option>
                                            <option value="developer" {{ $user->role == 'developer' ? 'selected' : '' }}>Developer</option>
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                        </select>
                                        <button type="submit" class="hidden">Simpan</button>
                                    </form>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6">
                                    @if($user->role == 'admin')
                                        <span class="text-gray-400 text-sm italic">-</span>
                                    @else
                                        <div class="flex items-center">
                                            <span class="relative flex h-3 w-3 mr-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full
                                                    {{ $user->status == 'active' ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3
                                                    {{ $user->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                            </span>
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                {{ $user->status == 'active' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Keterangan Available / Working -->
                                <td class="py-4 px-6">
                                    @if(in_array($user->role, ['designer', 'developer']))
                                        @php
                                            // User is working only if assigned to an active project
                                            $isWorking = \App\Models\Project::whereHas('members', function($q) use ($user) {
                                                $q->where('project_members.user_id', $user->user_id);
                                            })->where('status', '!=', 'done')->exists();
                                        @endphp
                                        <div class="flex items-center">
                                            @if($isWorking)
                                                <span class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                    <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                                                    Working
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-300">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                    Available
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm italic">-</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-center">
                                    @if(Auth::user()->user_id !== $user->user_id)
                                        <form method="POST" action="{{ route('admin.users.delete', $user->user_id) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-xl font-medium transition duration-200 flex items-center gap-2 group-hover:bg-red-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                <div class="flex items-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Total Pengguna</h3>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-600">{{ $users->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                <div class="flex items-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Aktif</h3>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">
                            {{ $users->where('status', 'active')->where('role', '!=', 'admin')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                <div class="flex items-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-yellow-100 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Sedang Bekerja</h3>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-yellow-600">
                            {{ $users->whereIn('role', ['designer', 'developer'])->filter(function($user) {
                                // Only count users who have active projects
                                return \App\Models\Project::whereHas('members', function($q) use ($user) {
                                    $q->where('project_members.user_id', $user->user_id);
                                })->where('status', '!=', 'done')->exists();
                            })->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1em 1em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .role-form select:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    }
</style>
@endsection
