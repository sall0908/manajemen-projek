@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                        Admin Dashboard
                    </h1>
                    <p class="text-gray-600 mt-2 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Selamat datang, Super Admin (Role: admin)
                    </p>
                </div>
                <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow-sm border border-blue-100">
                    {{ now()->format('l, d F Y') }}
                </div>
            </div>
        </div>

        {{-- Statistik Project --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 font-medium mb-2">Completed</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $completed ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm mt-3">Projects Selesai</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 font-medium mb-2">Active</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $active ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm mt-3">Projects Aktif</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 font-medium mb-2">Deleted</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $deleted ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm mt-3">Projects Dihapus</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 font-medium mb-2">Total</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $total ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm mt-3">Total Projects</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kalender Section --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Kalender Proyek
                        </h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                            {{ count($projectsWithDeadline ?? []) }} Events
                        </span>
                    </div>
                    <div id="calendar" class="w-full"></div>

                    <!-- Projects Board (progress overview) -->
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Projects Progress</h4>
                        <div class="space-y-3">
                            @foreach($projectsBoard ?? [] as $p)
                                <a href="{{ $p['detail_url'] }}" class="block p-3 bg-gray-50 rounded-lg border border-gray-100 hover:shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium text-gray-800">{{ $p['project_name'] }}</div>
                                            <div class="text-xs text-gray-500">Deadline: {{ $p['deadline'] ?? '-' }}</div>
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $p['progress_percent'] }}%</div>
                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3 overflow-hidden">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2" style="width: {{ $p['progress_percent'] }}%"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Manajemen Cepat
                    </h3>

                    <div class="space-y-4">
                        <a href="{{ route('admin.users') }}" class="group flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 hover:border-blue-400 hover:shadow-md transition-all duration-300">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-600 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 group-hover:text-blue-700">User Management</h4>
                                <p class="text-sm text-gray-600">Kelola role & status user</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.projects') }}" class="group flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:border-green-400 hover:shadow-md transition-all duration-300">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4 group-hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 group-hover:text-green-700">Project Management</h4>
                                <p class="text-sm text-gray-600">Buat & kelola project</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.reports.users') }}" class="group flex items-center p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200 hover:border-yellow-400 hover:shadow-md transition-all duration-300">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-4 group-hover:bg-yellow-600 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 group-hover:text-yellow-700">User Reports</h4>
                                <p class="text-sm text-gray-600">Lihat kontribusi user</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Aktivitas Terbaru
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start text-sm">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-gray-800">Project "Website Redesign" completed</p>
                                <p class="text-gray-500 text-xs">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-gray-800">New user registered</p>
                                <p class="text-gray-500 text-xs">5 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start text-sm">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-gray-800">Project deadline approaching</p>
                                <p class="text-gray-500 text-xs">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Productivity --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c4 0 6 2 6 4v1H6v-1c0-2 2-4 6-4z"></path>
                        </svg>
                        User Productivity
                    </h3>

                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($usersProductivity ?? [] as $u)
                            <a href="{{ $u['detail_url'] }}" class="flex items-center justify-between p-3 bg-gradient-to-r from-white to-gray-50 rounded-lg border border-gray-100 hover:shadow-sm">
                                <div>
                                    <div class="font-medium text-gray-800">{{ $u['full_name'] }} <span class="text-xs text-gray-500">({{ $u['role'] }})</span></div>
                                    <div class="text-xs text-gray-500">Assigned: {{ $u['assigned_cards'] }} • Done: {{ $u['completed_cards'] }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-800">{{ $u['total_time'] }}</div>
                                    <div class="text-xs text-gray-500">Total Time</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FullCalendar CSS & JS --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        themeSystem: 'standard',
        events: @json($projectsWithDeadline ?? []),
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
                return false;
            }
        },
        eventBackgroundColor: '#3B82F6',
        eventBorderColor: '#2563EB',
        eventTextColor: '#FFFFFF'
    });
    calendar.render();
});
</script>

<style>
.fc {
    font-family: 'Inter', sans-serif;
}
.fc .fc-toolbar-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
}
.fc .fc-button {
    background-color: #3B82F6;
    border-color: #3B82F6;
    font-weight: 500;
}
.fc .fc-button:hover {
    background-color: #2563EB;
    border-color: #2563EB;
}
.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #1D4ED8;
    border-color: #1D4ED8;
}
</style>
@endsection
