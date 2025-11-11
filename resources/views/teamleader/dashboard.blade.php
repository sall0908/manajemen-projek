@extends('layouts.teamleader')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg mb-4">
                <span class="text-2xl sm:text-3xl text-white">📋</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent mb-3">
                Project Board
            </h1>
            <p class="text-gray-600 text-sm sm:text-base max-w-2xl mx-auto">
                Kelola dan pantau semua proyek Anda dalam satu tampilan yang terorganisir
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Proyek</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-700">{{ $activeProjects->count() + $completedProjects->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-blue-600 text-lg sm:text-xl">📁</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Proyek Aktif</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-700">{{ $activeProjects->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-green-600 text-lg sm:text-xl">🔄</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Proyek Selesai</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-purple-700">{{ $completedProjects->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-purple-600 text-lg sm:text-xl">✅</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Card</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-indigo-700">
                            {{ $activeProjects->sum(function($project) { return $project->cards->count(); }) + $completedProjects->sum(function($project) { return $project->cards->count(); }) }}
                        </p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-indigo-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-indigo-600 text-lg sm:text-xl">📝</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Projects Section -->
        @if($activeProjects->count() > 0)
            <div class="mb-8 sm:mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></span>
                        🔄 Proyek Aktif
                        <span class="ml-3 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $activeProjects->count() }}
                        </span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($activeProjects as $project)
                    @php
                        $todo = $project->cards->where('status', 'todo');
                        $inProgress = $project->cards->where('status', 'in_progress');
                        $review = $project->cards->where('status', 'review');
                        $done = $project->cards->where('status', 'done');
                        $totalCards = $project->cards->count();
                        $progress = $totalCards > 0 ? round(($done->count() / $totalCards) * 100) : 0;
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        <!-- Header -->
                        <div class="p-6 border-b border-blue-50">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ $project->project_name }}</h3>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $project->description ?: 'Tidak ada deskripsi' }}</p>

                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300"
                                                 style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('teamleader.projects.show', $project->project_id) }}"
                                   class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-sm px-4 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 text-center font-semibold">
                                    + Tambah Card
                                </a>
                                <a href="{{ route('teamleader.project.boards', $project->project_id) }}"
                                   class="bg-white border border-blue-200 text-blue-700 text-sm px-4 py-2 rounded-xl hover:bg-blue-50 transition duration-300 font-semibold">
                                    📊 Board
                                </a>
                            </div>
                        </div>

                        <!-- Status Grid -->
                        <div class="grid grid-cols-4 gap-0 divide-x divide-blue-50">
                            <!-- To Do -->
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-gray-700">To Do</span>
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">{{ $todo->count() }}</span>
                                </div>
                                <div class="space-y-2 max-h-20 overflow-y-auto">
                                    @forelse ($todo->take(3) as $card)
                                        <div class="text-xs bg-gray-50 border border-gray-200 rounded-lg px-2 py-2 text-gray-700 truncate">
                                            {{ $card->card_title }}
                                        </div>
                                    @empty
                                        <div class="text-xs text-gray-400 text-center">Kosong</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- In Progress -->
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-yellow-700">Progress</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">{{ $inProgress->count() }}</span>
                                </div>
                                <div class="space-y-2 max-h-20 overflow-y-auto">
                                    @forelse ($inProgress->take(3) as $card)
                                        <div class="text-xs bg-yellow-50 border border-yellow-200 rounded-lg px-2 py-2 text-yellow-700 truncate">
                                            {{ $card->card_title }}
                                        </div>
                                    @empty
                                        <div class="text-xs text-gray-400 text-center">Kosong</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Review -->
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-indigo-700">Review</span>
                                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">{{ $review->count() }}</span>
                                </div>
                                <div class="space-y-2 max-h-20 overflow-y-auto">
                                    @forelse ($review->take(3) as $card)
                                        <div class="text-xs bg-indigo-50 border border-indigo-200 rounded-lg px-2 py-2 text-indigo-700 truncate">
                                            {{ $card->card_title }}
                                        </div>
                                    @empty
                                        <div class="text-xs text-gray-400 text-center">Kosong</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Done -->
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-green-700">Done</span>
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">{{ $done->count() }}</span>
                                </div>
                                <div class="space-y-2 max-h-20 overflow-y-auto">
                                    @forelse ($done->take(3) as $card)
                                        <div class="text-xs bg-green-50 border border-green-200 rounded-lg px-2 py-2 text-green-700 truncate">
                                            {{ $card->card_title }}
                                        </div>
                                    @empty
                                        <div class="text-xs text-gray-400 text-center">Kosong</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md mx-auto border border-blue-100">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-blue-600">📋</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada proyek aktif</h3>
                    <p class="text-gray-500 text-sm mb-4">Mulai dengan membuat proyek pertama Anda</p>
                    <a href="{{ route('teamleader.projects.create') }}"
                       class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 inline-flex items-center">
                        + Buat Proyek Baru
                    </a>
                </div>
            </div>
        @endif

        <!-- Completed Projects Section -->
        @if($completedProjects->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                    ✅ Proyek Selesai
                    <span class="ml-3 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $completedProjects->count() }}
                    </span>
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($completedProjects as $project)
                    @php
                        $todo = $project->cards->where('status', 'todo');
                        $inProgress = $project->cards->where('status', 'in_progress');
                        $review = $project->cards->where('status', 'review');
                        $done = $project->cards->where('status', 'done');
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg border-2 border-green-300 opacity-90 hover:opacity-100 transition duration-300">
                        <!-- Header -->
                        <div class="p-6 border-b border-green-50 bg-green-50">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $project->project_name }}</h3>
                                        <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                                            ✅ Selesai
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $project->description ?: 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('teamleader.projects.show', $project->project_id) }}"
                                   class="flex-1 bg-blue-600 text-white text-sm px-4 py-2 rounded-xl hover:bg-blue-700 transition duration-300 text-center font-semibold">
                                    Lihat Detail
                                </a>
                                <a href="{{ route('teamleader.project.boards', $project->project_id) }}"
                                   class="bg-white border border-blue-200 text-blue-700 text-sm px-4 py-2 rounded-xl hover:bg-blue-50 transition duration-300 font-semibold">
                                    📊 Board
                                </a>
                            </div>
                        </div>

                        <!-- Status Overview -->
                        <div class="p-4">
                            <div class="grid grid-cols-4 gap-4 text-center">
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">To Do</div>
                                    <div class="text-sm font-bold text-gray-700">{{ $todo->count() }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">Progress</div>
                                    <div class="text-sm font-bold text-yellow-700">{{ $inProgress->count() }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">Review</div>
                                    <div class="text-sm font-bold text-indigo-700">{{ $review->count() }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">Done</div>
                                    <div class="text-sm font-bold text-green-700">{{ $done->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Structure (Tetap sama seperti sebelumnya) -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-red-500 text-xl transition duration-200"
                onclick="closeDetail()">✖</button>

        <h2 id="projectTitle" class="text-2xl font-bold text-blue-700 mb-4"></h2>
        <p id="projectDesc" class="text-gray-600 mb-6"></p>

        <div class="flex flex-wrap gap-3 justify-end">
            <button onclick="markDone()" class="bg-gradient-to-r from-green-600 to-emerald-700 text-white px-6 py-3 rounded-xl hover:from-green-700 hover:to-emerald-800 transition duration-300 font-semibold">
                ✅ Tandai Selesai
            </button>
            <button onclick="inviteMember()" class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 font-semibold">
                👥 Undang Anggota
            </button>
            <button onclick="deleteProject()" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-red-800 transition duration-300 font-semibold">
                🗑️ Hapus Proyek
            </button>
        </div>
    </div>
</div>

<script>
// JavaScript functions remain the same as your original code
let currentProjectId = null;

function showProjectDetail(projectId) {
    currentProjectId = projectId;
    fetch(`/teamleader/project/${projectId}`)
        .then(res => {
            if (!res.ok) throw new Error("Gagal mengambil detail proyek");
            return res.json();
        })
        .then(data => {
            document.getElementById('projectTitle').innerText = data.project_name || 'Tanpa Judul';
            document.getElementById('projectDesc').innerText = data.description || 'Tidak ada deskripsi';
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(err => {
            console.error(err);
            alert("❌ Tidak bisa memuat detail proyek.");
        });
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    currentProjectId = null;
}

function markDone() {
    if (!currentProjectId) return;
    if (!confirm("Tandai proyek ini sebagai selesai?")) return;

    fetch(`/teamleader/project/${currentProjectId}/done`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (res.ok) {
            alert(data.message || "✅ Proyek berhasil ditandai selesai!");
            closeDetail();
            location.reload();
        } else {
            alert(data.message || "❌ Gagal memperbarui proyek!");
        }
    })
    .catch(err => console.error(err));
}

function inviteMember() {
    if (!currentProjectId) {
        alert("❌ Tidak ada proyek yang dipilih!");
        return;
    }

    const userId = prompt("Masukkan ID user yang ingin diundang:");
    if (!userId) return;

    const role = prompt("Masukkan role (developer/designer):");
    if (role !== "developer" && role !== "designer") {
        alert("❌ Role tidak valid!");
        return;
    }

    fetch(`/teamleader/project/${currentProjectId}/invite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ user_id: userId, role: role })
    })
    .then(res => res.json())
    .then(data => alert(data.message))
    .catch(err => console.error('Error inviting member:', err));
}

function deleteProject() {
    if (!currentProjectId) return;
    if (!confirm("⚠️ Yakin ingin menghapus proyek ini?")) return;

    fetch(`/teamleader/projects/${currentProjectId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (res.ok) {
            alert(data.message || "🗑️ Proyek berhasil dihapus!");
            closeDetail();
            location.reload();
        } else {
            alert(data.message || "❌ Gagal menghapus proyek!");
        }
    })
    .catch(err => console.error(err));
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for card lists */
.max-h-20::-webkit-scrollbar {
    width: 4px;
}

.max-h-20::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 2px;
}

.max-h-20::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.max-h-20::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection
