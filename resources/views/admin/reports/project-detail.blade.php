@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-blue-700 mb-2">📊 Detail Laporan Proyek</h2>
            <a href="{{ route('admin.reports.projects') }}" class="text-blue-600 hover:text-blue-800">
                ← Kembali ke Daftar Laporan
            </a>
        </div>
        <a href="{{ route('admin.reports.projectDetailPdf', $project->project_id) }}"
           class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 transition">
            📄 Export PDF
        </a>
    </div>

    <!-- Info Proyek -->
    <div class="bg-white shadow-md rounded-xl p-6 mb-6 border border-blue-100">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $project->project_name }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 mb-1"><strong>Deskripsi:</strong> {{ $project->description ?? '-' }}</p>
                <p class="text-gray-600 mb-1"><strong>Team Leader:</strong> {{ $project->leader->full_name ?? '-' }}</p>
                <p class="text-gray-600 mb-1"><strong>Status:</strong>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $project->status === 'done' ? 'bg-green-100 text-green-700' :
                           ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                           ($project->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-gray-600 mb-1"><strong>Deadline:</strong>
                    {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}
                </p>
                <p class="text-gray-600 mb-1"><strong>Kesulitan:</strong>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $project->difficulty === 'easy' ? 'bg-green-100 text-green-700' :
                           ($project->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($project->difficulty ?? 'medium') }}
                    </span>
                </p>
                <p class="text-gray-600 mb-1"><strong>Tanggal Dibuat:</strong>
                    {{ $project->created_at ? \Carbon\Carbon::parse($project->created_at)->format('d M Y') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Card</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $totalCards }}</p>
                </div>
                <div class="text-4xl">📋</div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Card Selesai</p>
                    <p class="text-3xl font-bold text-green-700">{{ $cardsByStatus['done'] }}</p>
                </div>
                <div class="text-4xl">✅</div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Sub-task Selesai</p>
                    <p class="text-3xl font-bold text-purple-700">{{ $completedSubTasks }}/{{ $totalSubTasks }}</p>
                </div>
                <div class="text-4xl">📝</div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Waktu Kerja</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $formattedTime }}</p>
                </div>
                <div class="text-4xl">⏱️</div>
            </div>
        </div>
    </div>

    <!-- Card Status Breakdown -->
    <div class="bg-white shadow-md rounded-xl p-6 mb-6 border border-blue-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Breakdown Status Card</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-1">To Do</p>
                <p class="text-2xl font-bold text-gray-700">{{ $cardsByStatus['todo'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-1">In Progress</p>
                <p class="text-2xl font-bold text-blue-700">{{ $cardsByStatus['in_progress'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-1">Review</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $cardsByStatus['review'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-1">Done</p>
                <p class="text-2xl font-bold text-green-700">{{ $cardsByStatus['done'] }}</p>
            </div>
        </div>
    </div>

    <!-- Anggota Tim -->
    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100 mb-6">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h3 class="text-xl font-bold">👥 Anggota Tim</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Nama</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Role</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Card Di-assign</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Sub-task</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Waktu Kerja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($teamMembers as $member)
                        @php
                            $memberHours = floor($member['time_worked'] / 3600);
                            $memberMinutes = floor(($member['time_worked'] % 3600) / 60);
                            $memberSeconds = $member['time_worked'] % 60;
                            $memberFormattedTime = sprintf('%02d:%02d:%02d', $memberHours, $memberMinutes, $memberSeconds);
                        @endphp
                        <tr class="hover:bg-blue-50">
                            <td class="py-3 px-4 text-gray-800 font-medium">{{ $member['user']->full_name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    {{ ucfirst($member['user']->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center text-blue-700 font-semibold">{{ $member['assigned_cards_count'] }}</td>
                            <td class="py-3 px-4 text-center text-purple-700 font-semibold">
                                {{ $member['sub_tasks_completed'] }}/{{ $member['sub_tasks_count'] }}
                            </td>
                            <td class="py-3 px-4 text-blue-700 font-semibold">{{ $memberFormattedTime }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Belum ada anggota tim</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daftar Card -->
    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h3 class="text-xl font-bold">📋 Daftar Card</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Judul Card</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Status</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Dibuat Oleh</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Assigned To</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Sub-task</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($project->cards as $card)
                        <tr class="hover:bg-blue-50">
                            <td class="py-3 px-4 text-gray-800 font-medium">{{ $card->card_title }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $card->status === 'done' ? 'bg-green-100 text-green-700' :
                                       ($card->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                       ($card->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $card->creator->full_name ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">
                                @if($card->assignedUsers->isNotEmpty())
                                    {{ $card->assignedUsers->pluck('full_name')->join(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center text-purple-700 font-semibold">
                                {{ $card->subTasks->where('is_completed', true)->count() }}/{{ $card->subTasks->count() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Belum ada card</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

