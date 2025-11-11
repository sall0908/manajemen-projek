@extends('layouts.developer')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg mb-4">
                <span class="text-2xl sm:text-3xl text-white">�</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent mb-3">
                Card Saya
            </h1>
            <p class="text-gray-600 text-sm sm:text-base max-w-2xl mx-auto">
                Kelola dan kerjakan semua card yang Anda terima dari TeamLeader
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Card</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-700">{{ $cards->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-blue-600 text-lg sm:text-xl">�</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Sedang Dikerjakan</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-orange-700">
                            {{ $cards->where('status', 'in_progress')->count() }}
                        </p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-orange-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-orange-600 text-lg sm:text-xl">⏳</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Dalam Review</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-purple-700">
                            {{ $cards->where('status', 'review')->count() }}
                        </p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-purple-600 text-lg sm:text-xl">📋</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Proyek</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-indigo-700">
                            {{ $projects->count() }}
                        </p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-indigo-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <span class="text-indigo-600 text-lg sm:text-xl">�</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Grid -->
        @if($cards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                @foreach ($cards as $card)
                    <div class="bg-white rounded-2xl shadow-lg border border-blue-100 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        <!-- Header -->
                        <div class="p-6 border-b border-blue-50">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $card->card_title }}</h2>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $card->description ?: 'Tidak ada deskripsi' }}</p>

                                    <!-- Status Badge -->
                                    <div class="inline-block">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $card->status === 'done' ? 'bg-green-100 text-green-700 border border-green-200' :
                                               ($card->status === 'in_progress' ? 'bg-orange-100 text-orange-700 border border-orange-200' :
                                               ($card->status === 'review' ? 'bg-purple-100 text-purple-700 border border-purple-200' :
                                               'bg-blue-100 text-blue-700 border border-blue-200')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div class="p-6">
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span><strong class="text-gray-700">Project:</strong> {{ $card->project_name ?? '-' }}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span><strong class="text-gray-700">Leader:</strong> {{ $card->leader->full_name ?? '-' }}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><strong class="text-gray-700">Dibuat:</strong>
                                        {{ $card->created_at ? \Carbon\Carbon::parse($card->created_at)->format('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- SubTask Count -->
                            <div class="p-3 bg-blue-50 rounded-lg mb-4">
                                <div class="text-sm font-semibold text-blue-700">
                                    📌 {{ $card->subTasks ? $card->subTasks->count() : 0 }} SubTask
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('developer.project.boards', $card->project_id) }}"
                               class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-sm font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Lihat Board Proyek
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md mx-auto border border-blue-100">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-blue-600">�</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada card</h3>
                    <p class="text-gray-500 text-sm mb-4">Anda belum menerima card apapun saat ini</p>
                    <p class="text-gray-400 text-xs">Hubungi team leader untuk menerima card</p>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        @if($cards->count() > 0)
            <div class="mt-8 bg-white rounded-2xl shadow-lg border border-blue-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Akses Cepat ke Proyek
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($projects->take(4) as $project)
                        <a href="{{ route('developer.project.boards', $project->project_id) }}"
                           class="flex items-center p-3 bg-blue-50 rounded-xl border border-blue-100 hover:bg-blue-100 transition duration-200">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-sm font-semibold mr-3">
                                {{ substr($project->project_name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 text-sm truncate">{{ $project->project_name }}</div>
                                <div class="text-xs text-gray-500">{{ $project->cards->count() }} card</div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
