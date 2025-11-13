@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-blue-700 mb-2">📊 Detail Laporan User</h2>
            <a href="{{ route('admin.reports.users') }}" class="text-blue-600 hover:text-blue-800">
                ← Kembali ke Daftar Laporan
            </a>
        </div>
        <a href="{{ route('admin.reports.userDetailPdf', $user->user_id) }}"
           class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 transition">
            📄 Export PDF
        </a>
    </div>

    <!-- Info User -->
    <div class="bg-white shadow-md rounded-xl p-6 mb-6 border border-blue-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $user->full_name }}</h3>
                <p class="text-gray-600 mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="text-gray-600 mb-1"><strong>Username:</strong> {{ $user->username }}</p>
                <div class="flex items-center gap-4 mt-3">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $user->role === 'admin' ? 'bg-blue-700 text-white' : ($user->role === 'teamlead' ? 'bg-blue-500 text-white' : 'bg-blue-100 text-blue-800') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($user->role === 'teamlead')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Card Dibuat</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $cardsCreated }}</p>
                    </div>
                    <div class="text-4xl">📋</div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Proyek Diikuti</p>
                        <p class="text-3xl font-bold text-purple-700">{{ $projectsJoined->count() }}</p>
                    </div>
                    <div class="text-4xl">👥</div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100 mb-6">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-xl font-bold">👥 Daftar Proyek yang Diikuti</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Nama Proyek</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Jumlah Card</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Deadline</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($projectsJoined as $project)
                            <tr class="hover:bg-blue-50">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $project->project_name }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $project->status === 'done' ? 'bg-green-100 text-green-700' :
                                           ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                           ($project->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center text-blue-700 font-semibold">{{ $project->cards_count }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">Belum mengikuti proyek</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($user->role === 'admin')
        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100 mb-6">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-xl font-bold">📁 Daftar Proyek yang Dibuat</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Nama Proyek</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Jumlah Card</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Deadline</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($projectsCreated as $project)
                            <tr class="hover:bg-blue-50">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $project->project_name }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $project->status === 'done' ? 'bg-green-100 text-green-700' :
                                           ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                           ($project->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center text-blue-700 font-semibold">{{ $project->cards_count }}</td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $project->created_at ? \Carbon\Carbon::parse($project->created_at)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">Belum ada proyek yang dibuat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Laporan Developer/Designer -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Sub-task Created -->
            <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Sub-task Dibuat</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $subTasksCreated }}</p>
                    </div>
                    <div class="text-4xl">📝</div>
                </div>
            </div>

            <!-- Sub-task Completed -->
            <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Sub-task Selesai</p>
                        <p class="text-3xl font-bold text-green-700">{{ $subTasksCompleted }}</p>
                    </div>
                    <div class="text-4xl">✅</div>
                </div>
            </div>

            <!-- Total Time Worked -->
            <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Waktu Kerja</p>
                        <p class="text-2xl font-bold text-purple-700">{{ $formattedTime }}</p>
                    </div>
                    <div class="text-4xl">⏱️</div>
                </div>
            </div>

            <!-- Time Logs Count -->
            <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Sesi Time Log</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $timeLogs->count() }}</p>
                    </div>
                    <div class="text-4xl">📊</div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100 mb-6">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-xl font-bold">👥 Daftar Proyek yang Diikuti</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Nama Proyek</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Jumlah Card</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Deadline</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($projectsJoined as $project)
                            <tr class="hover:bg-blue-50">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $project->project_name }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $project->status === 'done' ? 'bg-green-100 text-green-700' :
                                           ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                           ($project->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center text-blue-700 font-semibold">{{ $project->cards_count }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">Belum mengikuti proyek</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daftar Card yang Di-assign -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100 mb-6">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-xl font-bold">📋 Card yang Di-assign</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Judul Card</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Proyek</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Sub-task</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($assignedCards as $card)
                            <tr class="hover:bg-blue-50">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $card->card_title }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $card->project->project_name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $card->status === 'done' ? 'bg-green-100 text-green-700' :
                                           ($card->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                           ($card->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center text-blue-700 font-semibold">{{ $card->sub_tasks_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">Belum ada card yang di-assign</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daftar Time Logs -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-xl font-bold">⏱️ Riwayat Time Log</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Card</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Proyek</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Mulai</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Selesai</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700">Durasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($timeLogs as $log)
                            <tr class="hover:bg-blue-50">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $log->card->card_title ?? '-' }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $log->card->project->project_name ?? '-' }}</td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $log->start_time ? \Carbon\Carbon::parse($log->start_time)->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-blue-700 font-semibold">
                                    {{ $log->formatted_duration ?? '00:00:00' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">Belum ada time log</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

