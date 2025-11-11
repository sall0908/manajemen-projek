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
                <p class="text-gray-600 text-sm sm:text-base">Kelola dan pantau progress cards dalam proyek ini</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
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

        <!-- Board Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- To Do Column -->
            <div data-column="todo" class="bg-gradient-to-b from-blue-50 to-white border-2 border-blue-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-blue-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        To Do
                    </h2>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $todo->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($todo as $card)
                        <div data-card-id="{{ $card->card_id }}" class="bg-white border border-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $card->card_title }}</div>
                            <div class="text-xs text-gray-500 mb-2">#{{ $card->card_id }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($card->description, 100) }}</p>

                            <!-- Card info -->
                            <div class="mb-3 p-2 bg-blue-50 rounded-lg text-xs text-gray-600">
                                <div>📌 Status: <strong class="text-blue-700">{{ ucfirst(str_replace('_', ' ', $card->status)) }}</strong></div>
                                <div>📦 SubTasks: <strong>{{ $card->subTasks ? $card->subTasks->count() : 0 }}</strong></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.cards.inProgress', $card->card_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ⏳ Start
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📝</div>
                            <p class="text-gray-500 text-sm">Tidak ada card</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- In Progress Column -->
            <div data-column="in_progress" class="bg-gradient-to-b from-orange-50 to-white border-2 border-orange-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-orange-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                        In Progress
                    </h2>
                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $inProgress->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($inProgress as $card)
                        <div data-card-id="{{ $card->card_id }}" class="bg-white border border-orange-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $card->card_title }}</div>
                            <div class="text-xs text-gray-500 mb-2">#{{ $card->card_id }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($card->description, 100) }}</p>

                            <!-- Card info -->
                            <div class="mb-3 p-2 bg-orange-50 rounded-lg text-xs text-gray-600">
                                <div>📌 Status: <strong class="text-orange-700">{{ ucfirst(str_replace('_', ' ', $card->status)) }}</strong></div>
                                <div>📦 SubTasks: <strong>{{ $card->subTasks ? $card->subTasks->count() : 0 }}</strong></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.cards.review', $card->card_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        👀 Review
                                    </button>
                                </form>
                                <form action="{{ route('teamleader.cards.back', $card->card_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ↩️ Back
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">⏳</div>
                            <p class="text-gray-500 text-sm">Tidak ada card yang sedang dikerjakan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Review Column -->
            <div data-column="review" class="bg-gradient-to-b from-purple-50 to-white border-2 border-purple-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-purple-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                        Review
                    </h2>
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $review->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($review as $card)
                        <div data-card-id="{{ $card->card_id }}" class="bg-white border border-purple-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $card->card_title }}</div>
                            <div class="text-xs text-gray-500 mb-2">#{{ $card->card_id }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($card->description, 100) }}</p>

                            <!-- Card info -->
                            <div class="mb-3 p-2 bg-purple-50 rounded-lg text-xs text-gray-600">
                                <div>📌 Status: <strong class="text-purple-700">{{ ucfirst(str_replace('_', ' ', $card->status)) }}</strong></div>
                                <div>📦 SubTasks: <strong>{{ $card->subTasks ? $card->subTasks->count() : 0 }}</strong></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.cards.done', $card->card_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ✅ Done
                                    </button>
                                </form>
                                <form action="{{ route('teamleader.cards.back', $card->card_id) }}" method="POST" class="flex-1 min-w-[80px]">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ↩️ Back
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">🔍</div>
                            <p class="text-gray-500 text-sm">Tidak ada card yang perlu direview</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Done Column -->
            <div data-column="done" class="bg-gradient-to-b from-green-50 to-white border-2 border-green-200 rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="font-bold text-green-700 text-lg sm:text-xl flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        Done
                    </h2>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $done->count() }}</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($done as $card)
                        <div data-card-id="{{ $card->card_id }}" class="bg-white border border-green-100 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="font-semibold text-gray-800 text-sm sm:text-base mb-2">{{ $card->card_title }}</div>
                            <div class="text-xs text-gray-500 mb-2">#{{ $card->card_id }}</div>
                            <p class="text-xs text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($card->description, 100) }}</p>

                            <!-- Card info -->
                            <div class="mb-3 p-2 bg-green-50 rounded-lg text-xs text-gray-600">
                                <div>📌 Status: <strong class="text-green-700">{{ ucfirst(str_replace('_', ' ', $card->status)) }}</strong></div>
                                <div>📦 SubTasks: <strong>{{ $card->subTasks ? $card->subTasks->count() : 0 }}</strong></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('teamleader.cards.review', $card->card_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white text-xs sm:text-sm py-2 px-3 rounded-lg transition duration-200 font-semibold">
                                        ↩️ Back to Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">🎉</div>
                            <p class="text-gray-500 text-sm">Tidak ada card yang selesai</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .min-w-\[80px\] {
        min-width: 80px;
    }
</style>

<script>
    // Auto refresh board status setiap 5 detik
    setInterval(function() {
        const projectId = '{{ $project->project_id }}';
        fetch(`/teamleader/project/${projectId}/boards`)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                
                // Update setiap column
                const columns = ['todo', 'in_progress', 'review', 'done'];
                columns.forEach(column => {
                    const oldColumn = document.querySelector(`[data-column="${column}"]`);
                    const newColumn = newDoc.querySelector(`[data-column="${column}"]`);
                    if (oldColumn && newColumn) {
                        oldColumn.innerHTML = newColumn.innerHTML;
                    }
                });
            })
            .catch(error => console.error('Error refreshing board:', error));
    }, 5000); // Refresh setiap 5 detik
</script>

@endsection
