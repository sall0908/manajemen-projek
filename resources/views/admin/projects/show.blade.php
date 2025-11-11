@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-6">
    <div class="container mx-auto px-4 sm:px-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                    Detail Project
                </h2>
                <p class="text-gray-600 mt-2">{{ $project->project_name }}</p>
            </div>
            <a href="{{ route('admin.projects') }}"
               class="mt-4 sm:mt-0 text-blue-600 hover:text-blue-800 font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Project
            </a>
        </div>

        {{-- Grid Layout --}}
        <div class="grid lg:grid-cols-4 gap-6">
            {{-- Sidebar Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Info Utama --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informasi Project
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    {{ $project->status === 'done' ? 'bg-green-100 text-green-800' :
                                       ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kesulitan</label>
                            <div class="mt-1">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    {{ $project->difficulty === 'easy' ? 'bg-green-100 text-green-800' :
                                       ($project->difficulty === 'hard' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($project->difficulty ?? 'medium') }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Deadline</label>
                            <p class="mt-1 text-gray-800 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Dibuat</label>
                            <p class="mt-1 text-gray-800">{{ $project->created_at ? $project->created_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Anggota Tim --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Anggota Tim
                    </h3>
                    @if($project->members->count() > 0)
                        <div class="space-y-3">
                            @foreach($project->members as $member)
                                @php
                                    $isWorking = $member->projectsAsMember()->where('status', '!=', 'done')->exists();
                                @endphp
                                <div class="flex items-center p-3 bg-blue-50 rounded-lg justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-3">
                                            {{ substr($member->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $member->full_name }}</p>
                                            <p class="text-sm text-gray-600 capitalize">{{ $member->role }}</p>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        @if($isWorking)
                                            <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">Working</span>
                                        @else
                                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Available</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada anggota.</p>
                    @endif

                    {{-- Form tambah member by name (Admin) --}}
                    <div class="mt-4 border-t pt-4">
                        <form action="{{ route('admin.projects.inviteMembers', $project->project_id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="member_name" required placeholder="Nama lengkap atau username (developer/designer)" class="flex-1 border rounded-xl px-3 py-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl">Tambahkan</button>
                        </form>
                        <p class="text-xs text-gray-500 mt-2">Masukkan nama lengkap atau username, hanya developer/designer dapat ditambahkan. Sistem akan menolak bila user sudah memiliki proyek aktif.</p>
                    </div>
                </div>

                {{-- Ringkasan Waktu --}}
                @php
                    $totalSeconds = 0;
                    foreach ($project->cards as $c) {
                        $totalSeconds += $c->timeLogs->sum('duration_seconds');
                    }
                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;
                    $totalFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                @endphp
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Ringkasan Waktu
                    </h3>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 mb-2">{{ $totalFormatted }}</div>
                        <p class="text-sm text-gray-600">Total waktu pengerjaan</p>
                    </div>
                </div>
            </div>

            {{-- Konten Utama --}}
            <div class="lg:col-span-3">
                {{-- Deskripsi Project --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-blue-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        Deskripsi Project
                    </h3>
                    <p class="text-gray-700 leading-relaxed">{{ $project->description ?? 'Tidak ada deskripsi' }}</p>
                </div>

                {{-- Kartu & Subtask --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-blue-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Kartu & Subtask
                        </h3>
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $project->cards->count() }} Kartu
                        </span>
                    </div>

                    @forelse($project->cards as $card)
                        @php
                            $cardTotalSeconds = $card->timeLogs->sum('duration_seconds');
                            $h = floor(($cardTotalSeconds ?? 0) / 3600);
                            $m = floor((($cardTotalSeconds ?? 0) % 3600) / 60);
                            $s = ($cardTotalSeconds ?? 0) % 60;
                            $cardTotalFormatted = sprintf('%02d:%02d:%02d', $h, $m, $s);

                            $assignedNames = $card->assignedUsers->pluck('full_name')->implode(', ');
                            $subtaskCount = $card->subTasks->count();
                            $completedSubtaskCount = $card->subTasks->where('is_completed', true)->count();

                            $devSeconds = $card->timeLogs->filter(fn($tl) => $tl->user && $tl->user->role === 'developer')->sum('duration_seconds');
                            $desSeconds = $card->timeLogs->filter(fn($tl) => $tl->user && $tl->user->role === 'designer')->sum('duration_seconds');
                            $devFmt = sprintf('%02d:%02d:%02d', floor($devSeconds/3600), floor(($devSeconds%3600)/60), $devSeconds%60);
                            $desFmt = sprintf('%02d:%02d:%02d', floor($desSeconds/3600), floor(($desSeconds%3600)/60), $desSeconds%60);
                        @endphp

                        <div class="border border-gray-200 rounded-xl p-5 mb-6 bg-gray-50 hover:bg-white transition duration-200">
                            {{-- Header Kartu --}}
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <h4 class="text-lg font-semibold text-gray-800">{{ $card->card_title ?? $card->title ?? 'Card' }}</h4>
                                        <span class="px-3 py-1 text-xs rounded-full font-medium
                                            {{ $card->status === 'done' ? 'bg-green-100 text-green-700' :
                                               ($card->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                               ($card->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                        </span>
                                    </div>

                                    @if($card->description)
                                        <p class="text-gray-600 mb-3">{{ $card->description }}</p>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span><strong>Ditugaskan ke:</strong> {{ $assignedNames ?: '-' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span><strong>Dibuat oleh:</strong> {{ $card->creator->full_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <span><strong>Subtask:</strong> {{ $completedSubtaskCount }}/{{ $subtaskCount }}</span>
                                        </div>
                                        @if($card->due_date)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($card->due_date)->format('d M Y') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 lg:mt-0 lg:ml-4 text-right">
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-sm font-semibold text-gray-800">Total Waktu: {{ $cardTotalFormatted }}</p>
                                        <p class="text-xs text-gray-600 mt-1">Dev: {{ $devFmt }} | Des: {{ $desFmt }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Expand --}}
                            <button onclick="toggleCardDetail({{ $card->card_id }})"
                                    class="w-full text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center justify-center gap-2 py-2 border-t border-gray-200">
                                <span id="toggle-icon-{{ $card->card_id }}">▼</span>
                                <span>Detail Lengkap</span>
                            </button>

                            {{-- Detail Expandable --}}
                            <div id="card-detail-{{ $card->card_id }}" class="hidden mt-4 border-t pt-4 space-y-4">
                                {{-- Subtask --}}
                                @if($card->subTasks->count() > 0)
                                    <div>
                                        <h5 class="font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            Sub-task ({{ $completedSubtaskCount }}/{{ $subtaskCount }} selesai)
                                        </h5>
                                        <div class="space-y-2">
                                            @foreach($card->subTasks as $st)
                                                <div class="flex items-start gap-3 p-3 bg-white rounded-lg border {{ $st->is_completed ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                                                    <input type="checkbox" {{ $st->is_completed ? 'checked' : '' }} disabled
                                                           class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium {{ $st->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                                            {{ $st->title }}
                                                        </p>
                                                        @if($st->description)
                                                            <p class="text-xs text-gray-500 mt-1">{{ $st->description }}</p>
                                                        @endif
                                                        <p class="text-xs text-gray-400 mt-1">
                                                            Dibuat: {{ $st->created_at ? $st->created_at->format('d M Y H:i') : '-' }}
                                                            @if($st->is_completed && $st->updated_at)
                                                                | Selesai: {{ $st->updated_at->format('d M Y H:i') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Time Logs --}}
                                @if($card->timeLogs->count() > 0)
                                    <div>
                                        <h5 class="font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Time Logs
                                        </h5>
                                        <div class="space-y-2 max-h-48 overflow-y-auto">
                                            @foreach($card->timeLogs->whereNotNull('end_time') as $log)
                                                <div class="p-3 bg-white rounded-lg border text-sm">
                                                    <p class="font-medium text-gray-800">{{ $log->user->full_name ?? '-' }} ({{ ucfirst($log->user->role ?? '-') }})</p>
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        {{ $log->start_time ? \Carbon\Carbon::parse($log->start_time)->format('d M Y H:i') : '-' }}
                                                        -
                                                        {{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('d M Y H:i') : '-' }}
                                                    </p>
                                                    <p class="text-xs text-blue-600 font-semibold mt-1">
                                                        Durasi: {{ $log->formatted_duration ?? '00:00:00' }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Comments --}}
                                @if($card->comments->count() > 0)
                                    <div>
                                        <h5 class="font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                            </svg>
                                            Komentar ({{ $card->comments->count() }})
                                        </h5>
                                        <div class="space-y-2 max-h-48 overflow-y-auto">
                                            @foreach($card->comments as $comment)
                                                <div class="p-3 bg-white rounded-lg border text-sm">
                                                    <p class="font-medium text-gray-800">{{ $comment->user->full_name ?? 'User' }}</p>
                                                    <p class="text-gray-700 mt-1">{{ $comment->comment_text ?? $comment->content ?? '-' }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '-' }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada kartu</h3>
                            <p class="text-gray-500">Tambahkan kartu pertama untuk memulai project</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-wrap gap-4 justify-end">
            @if($project->status !== 'done')
                <form action="{{ route('admin.projects.markAsDone', $project->project_id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-700 text-white px-6 py-3 rounded-xl hover:from-green-700 hover:to-emerald-800 transition duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tandai Selesai
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.projects.destroy', $project->project_id) }}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus project ini?');" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-red-800 transition duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus Project
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCardDetail(cardId) {
    const detail = document.getElementById('card-detail-' + cardId);
    const icon = document.getElementById('toggle-icon-' + cardId);

    if (detail.classList.contains('hidden')) {
        detail.classList.remove('hidden');
        icon.textContent = '▲';
    } else {
        detail.classList.add('hidden');
        icon.textContent = '▼';
    }
}
</script>
@endsection
