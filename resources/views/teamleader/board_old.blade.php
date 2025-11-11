@extends('layouts.teamleader')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 sm:mb-8">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent mb-2">
                    📋 Board: {{ $project->project_name }}
                </h1>
                <p class="text-gray-600 text-sm sm:text-base">Kelola dan pantau progress tugas dalam proyek ini</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="toggleSubtaskForm()" class="w-full sm:w-auto bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl hover:from-purple-700 hover:to-pink-700 transition duration-300 font-semibold text-sm sm:text-base">
                    ➕ Tambah Subtask
                </button>
                <form action="{{ route('teamleader.project.done', $project->project_id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('PATCH')
                    <button class="w-full bg-gradient-to-r from-green-600 to-emerald-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl hover:from-green-700 hover:to-emerald-800 transition duration-300 font-semibold text-sm sm:text-base">
                        ✅ Tandai Project Selesai
                    </button>
                </form>
                <a href="{{ route('teamleader.dashboard') }}" class="w-full sm:w-auto bg-white border border-blue-200 text-blue-700 px-4 sm:px-6 py-2 sm:py-3 rounded-xl hover:bg-blue-50 transition duration-300 font-semibold text-sm sm:text-base text-center">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Add Subtask Form Modal -->
        <div id="subtaskForm" class="hidden mb-6 sm:mb-8 bg-white rounded-2xl shadow-lg border border-purple-100 p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">➕ Tambah Subtask Baru</h3>
                <button onclick="toggleSubtaskForm()" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
            </div>
            <form action="{{ route('teamleader.project.cards.store', $project->project_id) }}" method="POST" class="grid md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Card</label>
                    <select name="card_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <option value="">-- Pilih card --</option>
                        @foreach($project->cards as $c)
                            <option value="{{ $c->card_id }}">#{{ $c->card_id }} - {{ $c->card_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Subtask</label>
                    <input name="title" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Masukkan judul subtask" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Masukkan deskripsi subtask"></textarea>
                </div>
                <div class="md:col-span-2 flex gap-3">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all">
                        💾 Simpan Subtask
                    </button>
                    <button type="button" onclick="toggleSubtaskForm()" class="flex-1 bg-gray-300 text-gray-800 font-bold py-2 rounded-lg hover:bg-gray-400 transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif
        @if (session('info'))
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('info') }}
                </div>
            </div>
        @endif

        <!-- Team Members Section -->
        <div class="mb-6 sm:mb-8 bg-white rounded-2xl shadow-lg border border-blue-100 p-4 sm:p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Anggota Tim
            </h3>
            @if($project->members && $project->members->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    @foreach($project->members as $member)
                        <div class="flex items-center justify-between bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-3">
                                    {{ substr($member->full_name, 0, 1) }}
                                </div>
                                <span class="text-gray-800 font-medium text-sm">{{ $member->full_name }}</span>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $member->role === 'developer' ? 'bg-blue-100 text-blue-700 border border-blue-200' : ($member->role === 'designer' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-gray-100 text-gray-700 border border-gray-200') }}">
                                {{ ucfirst($member->role) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-4">Belum ada anggota tim.</p>
            @endif
        </div>

        <!-- Board Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- To Do Column -->
            <div class="bg-gradient-to-b from-blue-50 to-white border-2 border-blue-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-blue-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        To Do
                    </h2>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $todo->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($todo as $subtask)
                        <div data-subtask-id="{{ $subtask->sub_task_id }}" class="bg-white border border-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $subtask->title }}</div>
                            <div class="text-xs text-gray-500 mb-2">Card: {{ $subtask->card->card_title ?? 'Unknown' }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 100) }}</p>

                            <!-- Subtask status indicator -->
                            <div class="mb-3 p-2 bg-blue-50 rounded-lg text-xs text-gray-600">
                                📌 Status: <strong class="text-blue-700">{{ ucfirst($subtask->status) }}</strong>
                                <br>✅ Completed: {{ $subtask->is_completed ? 'Ya' : 'Tidak' }}
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        � Start
                                    </button>
                                </form>
                                <form action="{{ route('teamleader.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="review">
                                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        👀 Review
                                    </button>
                                </form>
                                <form action="{{ route('teamleader.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="done">
                                    <button class="w-full bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ✅ Done
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📝</div>
                            <p class="text-gray-500 text-sm">Tidak ada subtask</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- In Progress Column -->
                        <!-- In Progress Column -->
            <div class="bg-gradient-to-b from-orange-50 to-white border-2 border-orange-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-orange-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                        In Progress
                    </h2>
                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $inProgress->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($inProgress as $subtask)
                        <div data-subtask-id="{{ $subtask->sub_task_id }}" class="bg-white border border-orange-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $subtask->title }}</div>
                            <div class="text-xs text-gray-500 mb-2">Card: {{ $subtask->card->card_title ?? 'Unknown' }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 100) }}</p>

                            <!-- Subtask status indicator -->
                            <div class="mb-3 p-2 bg-orange-50 rounded-lg text-xs text-gray-600">
                                📌 Status: <strong class="text-orange-700">{{ ucfirst($subtask->status) }}</strong>
                                <br>✅ Completed: {{ $subtask->is_completed ? 'Ya' : 'Tidak' }}
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="review">
                                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        � Send to Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">⏳</div>
                            <p class="text-gray-500 text-sm">Tidak ada subtask yang sedang dikerjakan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Review Column -->
            <div class="bg-gradient-to-b from-purple-50 to-white border-2 border-purple-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-purple-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                        Review
                    </h2>
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $review->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($review as $subtask)
                        <div data-subtask-id="{{ $subtask->sub_task_id }}" class="bg-white border border-purple-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $subtask->title }}</div>
                            <div class="text-xs text-gray-500 mb-2">Card: {{ $subtask->card->card_title ?? 'Unknown' }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 100) }}</p>

                            <!-- Subtask status indicator -->
                            <div class="mb-3 p-2 bg-purple-50 rounded-lg text-xs text-gray-600">
                                📌 Status: <strong class="text-purple-700">{{ ucfirst($subtask->status) }}</strong>
                                <br>✅ Completed: {{ $subtask->is_completed ? 'Ya' : 'Tidak' }}
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ↩️ Back
                                    </button>
                                </form>
                                <form action="{{ route('teamleader.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="done">
                                    <button class="w-full bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ✅ Done
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">🔍</div>
                            <p class="text-gray-500 text-sm">Tidak ada subtask yang perlu direview</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-gradient-to-b from-green-50 to-white border-2 border-green-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-green-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        Done
                    </h2>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $done->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($done as $subtask)
                        <div data-subtask-id="{{ $subtask->sub_task_id }}" class="bg-white border border-green-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $subtask->title }}</div>
                            <div class="text-xs text-gray-500 mb-2">Card: {{ $subtask->card->card_title ?? 'Unknown' }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 100) }}</p>

                            <!-- Subtask status indicator -->
                            <div class="mb-3 p-2 bg-green-50 rounded-lg text-xs text-gray-600">
                                📌 Status: <strong class="text-green-700">{{ ucfirst($subtask->status) }}</strong>
                                <br>✅ Completed: {{ $subtask->is_completed ? 'Ya' : 'Tidak' }}
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="review">
                                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ↩️ Back to Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">🎉</div>
                            <p class="text-gray-500 text-sm">Tidak ada subtask yang selesai</p>
                        </div>
                    @endforelse
                </div>
            </div>
                                <form action="{{ route('teamleader.cards.back', $card->card_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                    ↩️ Kembalikan ke To Do
                                </button>
                            </form>
                                <form action="{{ route('teamleader.cards.destroy', $card->card_id) }}" method="POST" onsubmit="return false;" class="ajax-delete-card">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">🗑️ Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">🎉</div>
                            <p class="text-gray-500 text-sm">Tidak ada tugas yang selesai</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Invite Member Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 sm:p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Undang Anggota Tim
            </h3>
                <p class="text-xs text-gray-500 mt-2">Note: Invite functionality is moved to Admin project detail.</p>
        </div>
    </div>
</div>

<style>
    .min-w-\[80px\] {
        min-width: 80px;
    }

    /* Custom scrollbar for comments */
    .max-h-24::-webkit-scrollbar {
        width: 4px;
    }

    .max-h-24::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 2px;
    }

    .max-h-24::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }

    .max-h-24::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
<div id="toast" class="fixed right-4 bottom-4 z-50 hidden">
    <div id="toast-msg" class="px-4 py-3 rounded-lg shadow-lg text-white bg-gray-800"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    function showToast(message, success = true) {
        const toast = document.getElementById('toast');
        const msg = document.getElementById('toast-msg');
        msg.textContent = message;
        msg.className = success ? 'px-4 py-3 rounded-lg shadow-lg text-white bg-green-600' : 'px-4 py-3 rounded-lg shadow-lg text-white bg-red-600';
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // Delegate click on ajax-delete-card buttons/forms
    document.querySelectorAll('.ajax-delete-card').forEach(form => {
        form.addEventListener('click', function(e) {
            // allow clicks on button
        });
        form.addEventListener('submit', function(e) {
            e.preventDefault();
        });
        form.querySelector('button')?.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Hapus card ini? Semua data terkait akan dihapus.')) return;
            const action = form.getAttribute('action');
            fetch(action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                }
            }).then(r => r.json().then(j => ({status: r.status, body: j}))).then(({status, body}) => {
                if (status >= 200 && status < 300) {
                    showToast(body.message || 'Card berhasil dihapus', true);
                    // remove card element from DOM
                    const cardEl = form.closest('[data-card-id]');
                    if (cardEl) cardEl.remove();
                } else {
                    showToast(body.message || 'Gagal menghapus card', false);
                }
            }).catch(err => {
                console.error(err);
                showToast('Gagal menghapus card', false);
            });
        });
    });
});

function toggleSubtaskForm() {
    document.getElementById('subtaskForm').classList.toggle('hidden');
}
</script>
@endsection
