@extends('layouts.designer')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 py-4 sm:py-6 lg:py-10">
    <div class="container mx-auto px-3 sm:px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl sm:rounded-2xl shadow-lg mb-3 sm:mb-4">
                <span class="text-xl sm:text-2xl text-white">🎨</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-purple-700 to-pink-600 bg-clip-text text-transparent mb-2">Card Saya</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-xs sm:text-sm px-4">Kelola dan kerjakan semua card yang Anda terima dari TeamLeader</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white rounded-xl shadow p-3 sm:p-4 border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Total Card</p>
                        <p class="text-lg sm:text-xl font-bold text-purple-700">{{ $cards->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-purple-600">�</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-3 sm:p-4 border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Dalam Review</p>
                        <p class="text-lg sm:text-xl font-bold text-pink-600">{{ $cards->where('status','review')->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-pink-50 rounded-lg flex items-center justify-center">
                        <span class="text-pink-600">�</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-3 sm:p-4 border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Sedang Dikerjakan</p>
                        <p class="text-lg sm:text-xl font-bold text-orange-600">{{ $cards->where('status','in_progress')->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                        <span class="text-orange-600">⏳</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-3 sm:p-4 border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Total Proyek</p>
                        <p class="text-lg sm:text-xl font-bold text-indigo-700">{{ $projects->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <span class="text-indigo-600">�</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Grid -->
        @if($cards->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($cards as $card)
                    <div class="bg-white rounded-2xl shadow-lg border border-purple-100 hover:shadow-xl transition duration-200 overflow-hidden">
                        <div class="p-5 border-b border-purple-50">
                            <h2 class="text-lg font-semibold text-gray-800 mb-1 line-clamp-2">{{ $card->card_title }}</h2>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $card->description ?: 'Tidak ada deskripsi' }}</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $card->status === 'done' ? 'bg-green-100 text-green-700 border border-green-200' :
                                       ($card->status === 'review' ? 'bg-pink-100 text-pink-700 border border-pink-200' :
                                       ($card->status === 'in_progress' ? 'bg-orange-100 text-orange-700 border border-orange-200' :
                                       'bg-purple-50 text-purple-700 border border-purple-100')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="space-y-2 mb-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <span class="font-semibold text-gray-700 mr-2">Project:</span>
                                    <span>{{ $card->project_name ?? '-' }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <span class="font-semibold text-gray-700 mr-2">Leader:</span>
                                    <span>{{ $card->leader->full_name ?? '-' }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <span class="font-semibold text-gray-700 mr-2">SubTasks:</span>
                                    <span>{{ $card->subTasks ? $card->subTasks->count() : 0 }}</span>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('designer.project.boards', $card->project_id) }}" class="flex-1 text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg font-semibold text-sm hover:from-purple-700 hover:to-pink-700 transition">Lihat Board</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="bg-white p-8 rounded-2xl shadow border border-purple-100 max-w-md mx-auto">
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-purple-600">🎨</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-1">Belum ada card</h3>
                    <p class="text-gray-500 text-sm">Anda belum menerima card apa pun. Hubungi team leader untuk menerima card.</p>
                </div>
            </div>
        @endif

        <!-- Quick Access -->
        @if($cards->count() > 0)
            <div class="mt-8 bg-white rounded-2xl shadow p-6 border border-purple-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat ke Proyek</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($projects->take(4) as $p)
                        <a href="{{ route('designer.project.boards', $p->project_id) }}" class="flex items-center p-3 bg-purple-50 rounded-xl border border-purple-100 hover:bg-purple-100 transition">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white font-semibold mr-3">{{ strtoupper(substr($p->project_name,0,1)) }}</div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 text-sm truncate">{{ $p->project_name }}</div>
                                <div class="text-xs text-gray-500">{{ $p->cards->count() }} card</div>
                            </div>
                            <div class="text-gray-400">
                                ➜
                            </div>
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
