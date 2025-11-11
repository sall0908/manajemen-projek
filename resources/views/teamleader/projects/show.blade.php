@extends('layouts.teamleader')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header Navigation -->
        <div class="mb-6">
            <a href="{{ route('teamleader.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Project Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent mb-2">
                {{ $project->project_name }}
            </h1>
            <p class="text-gray-600 text-sm sm:text-base leading-relaxed">{{ $project->description }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            <!-- Left Column - Create Card & Cards List -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Create Card Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 sm:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Card Baru
                    </h2>
                    <form action="{{ route('teamleader.cards.store', $project->project_id) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-gray-700 text-sm font-semibold mb-2">Judul Card</label>
                                <input type="text" name="title" id="title"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                       placeholder="Masukkan judul card" required>
                            </div>
                            <div>
                                <label for="description" class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                                <textarea name="description" id="description" rows="3"
                                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                          placeholder="Masukkan deskripsi card"></textarea>
                            </div>
                            <div>
                                <label for="user_id" class="block text-gray-700 text-sm font-semibold mb-2">Assign Ke</label>
                                <select name="user_id" id="user_id"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none">
                                    @foreach ($project->members->whereIn('role', ['developer','designer']) as $member)
                                        <option value="{{ $member->user_id }}">
                                            {{ $member->full_name }} ({{ $member->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                                + Buat Card
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Cards List -->
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Daftar Card
                        </h2>
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $project->cards->count() }} Card
                        </span>
                    </div>

                    @if ($project->cards->isEmpty())
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-3">📝</div>
                            <p class="text-gray-500 text-sm mb-4">Belum ada card yang dibuat untuk proyek ini</p>
                            <p class="text-gray-400 text-xs">Buat card pertama menggunakan form di atas</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ditugaskan Ke</th>
                                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($project->cards as $card)
                                        <tr class="hover:bg-blue-50 transition duration-200">
                                            <td class="py-4 px-4">
                                                <div class="font-medium text-gray-800 text-sm">{{ $card->card_title }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if ($card->assignedUsers->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($card->assignedUsers as $user)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                                {{ $user->full_name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-sm">Belum ditugaskan</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $card->status === 'done' ? 'bg-green-100 text-green-800 border border-green-200' :
                                                       ($card->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                                                       ($card->status === 'review' ? 'bg-purple-100 text-purple-800 border border-purple-200' :
                                                       'bg-gray-100 text-gray-800 border border-gray-200')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="text-gray-600 text-sm">
                                                    {{ $card->due_date ? \Carbon\Carbon::parse($card->due_date)->format('d M Y') : '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Team Members -->
            <div class="space-y-4 sm:space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 sm:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Anggota Tim
                    </h2>

                    @if($project->members->whereIn('role', ['developer','designer'])->count() > 0)
                        <div class="space-y-3">
                            @foreach ($project->members->whereIn('role', ['developer','designer']) as $member)
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl border border-blue-100 hover:bg-blue-100 transition duration-200">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-3">
                                            {{ substr($member->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-800 text-sm">{{ $member->full_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $member->role === 'developer' ? 'bg-blue-100 text-blue-700 border border-blue-200' :
                                           'bg-purple-100 text-purple-700 border border-purple-200' }}">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="text-gray-400 text-3xl mb-2">👥</div>
                            <p class="text-gray-500 text-sm">Belum ada anggota tim developer/designer</p>
                        </div>
                    @endif
                </div>

                <!-- Project Stats -->
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 sm:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Statistik Proyek
                    </h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Total Card</span>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $project->cards->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Anggota Tim</span>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $project->members->whereIn('role', ['developer','designer'])->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Status Proyek</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $project->status === 'done' ? 'bg-green-100 text-green-700 border border-green-200' :
                                   ($project->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' :
                                   'bg-blue-100 text-blue-700 border border-blue-200') }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom select dropdown arrow */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1em 1em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
