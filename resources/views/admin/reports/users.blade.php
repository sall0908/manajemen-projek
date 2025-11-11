@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 sm:mb-8">
            <div class="mb-4 lg:mb-0">
                <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent mb-2">
                    📊 Laporan Pengguna
                </h2>
                <p class="text-gray-600 text-sm sm:text-base">Tinjau performa dan kontribusi seluruh anggota tim</p>
            </div>
            <a href="{{ route('admin.reports.projects') }}"
               class="bg-gradient-to-r from-green-600 to-emerald-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl hover:from-green-700 hover:to-emerald-800 transition duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center w-full lg:w-auto mt-4 lg:mt-0 text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                📁 Laporan Proyek
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Pengguna</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-700">{{ $users->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Aktif</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-700">
                            {{ $users->where('status', 'active')->count() }}
                        </p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Team Leaders</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-purple-700">
                            {{ $users->where('role', 'teamleader')->count() }}
                        </p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Dev/Design</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-indigo-700">
                            {{ $users->whereIn('role', ['developer', 'designer'])->count() }}
                        </p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-indigo-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden border border-blue-100">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 sm:px-6 py-3 sm:py-4">
                <h3 class="text-lg sm:text-xl font-bold flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Daftar Pengguna
                </h3>
            </div>

            <!-- Mobile Cards View -->
            <div class="block lg:hidden">
                @foreach($users as $user)
                    @php
                        if ($user->role === 'admin') {
                            $roleClass = 'bg-blue-600 text-white border border-blue-600';
                        } elseif ($user->role === 'teamleader') {
                            $roleClass = 'bg-purple-500 text-white border border-purple-500';
                        } elseif ($user->role === 'developer') {
                            $roleClass = 'bg-green-100 text-green-800 border border-green-200';
                        } elseif ($user->role === 'designer') {
                            $roleClass = 'bg-pink-100 text-pink-800 border border-pink-200';
                        } else {
                            $roleClass = 'bg-gray-100 text-gray-800 border border-gray-200';
                        }

                        $statusClass = $user->status === 'active'
                            ? 'bg-green-100 text-green-800 border border-green-200'
                            : 'bg-red-100 text-red-800 border border-red-200';
                    @endphp

                    <div class="border-b border-gray-100 p-4 hover:bg-blue-50 transition duration-200">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-3">
                                    {{ substr($user->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $user->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $roleClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-center">
                                <p class="text-xs text-gray-600 mb-1">Project Dibuat</p>
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $user->projects_as_leader_count }}
                                </span>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600 mb-1">Project Diikuti</p>
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $user->projects_as_member_count }}
                                </span>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="text-center">
                            <a href="{{ route('admin.reports.userDetail', $user->user_id ) }}"
                               class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-3 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-800 transition duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-sm w-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="py-4 px-6 text-left font-semibold text-gray-700">Nama</th>
                            <th class="py-4 px-6 text-left font-semibold text-gray-700">Email</th>
                            <th class="py-4 px-6 text-left font-semibold text-gray-700">Role</th>
                            <th class="py-4 px-6 text-left font-semibold text-gray-700">Status</th>
                            <th class="py-4 px-6 text-center font-semibold text-gray-700">Project Dibuat</th>
                            <th class="py-4 px-6 text-center font-semibold text-gray-700">Project Diikuti</th>
                            <th class="py-4 px-6 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            @php
                                if ($user->role === 'admin') {
                                    $roleClass = 'bg-blue-600 text-white border border-blue-600';
                                } elseif ($user->role === 'teamleader') {
                                    $roleClass = 'bg-purple-500 text-white border border-purple-500';
                                } elseif ($user->role === 'developer') {
                                    $roleClass = 'bg-green-100 text-green-800 border border-green-200';
                                } elseif ($user->role === 'designer') {
                                    $roleClass = 'bg-pink-100 text-pink-800 border border-pink-200';
                                } else {
                                    $roleClass = 'bg-gray-100 text-gray-800 border border-gray-200';
                                }

                                $statusClass = $user->status === 'active'
                                    ? 'bg-green-100 text-green-800 border border-green-200'
                                    : 'bg-red-100 text-red-800 border border-red-200';
                            @endphp

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

                                <!-- Role -->
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $roleClass }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <span class="relative flex h-3 w-3 mr-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full
                                                {{ $user->status == 'active' ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3
                                                {{ $user->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Project Dibuat -->
                                <td class="py-4 px-6 text-center">
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $user->projects_as_leader_count }}
                                    </span>
                                </td>

                                <!-- Project Diikuti -->
                                <td class="py-4 px-6 text-center">
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $user->projects_as_member_count }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('admin.reports.userDetail', $user->user_id) }}"
                                       class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        @if($users->count() == 0)
        <div class="text-center py-8 sm:py-12">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-6 sm:p-8 max-w-md mx-auto border border-blue-100">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">Belum ada pengguna</h3>
                <p class="text-gray-500 text-sm sm:text-base">Tidak ada data pengguna yang tersedia saat ini.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
