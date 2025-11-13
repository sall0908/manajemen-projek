@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6">
        <div class="max-w-2xl mx-auto">
            {{-- Header Card --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 border border-blue-100">
                <div class="text-center mb-2">
                    <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                        Buat Project Baru
                    </h2>
                    <p class="text-gray-600 mt-2 text-sm sm:text-base">Isi detail project untuk memulai pekerjaan tim</p>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 border border-blue-100">
                <form method="POST" action="{{ route('admin.projects.store') }}" class="space-y-4 sm:space-y-6">
                    @csrf

                    {{-- Nama Project --}}
                    <div>
                        <label class="text-gray-700 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Nama Project
                        </label>
                        <input type="text" name="project_name" required
                            class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            Deskripsi
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"></textarea>
                    </div>

                    {{-- Difficulty Level --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Tingkat Kesulitan
                        </label>
                        <select name="difficulty" required
                                class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none bg-white">
                            <option value="">-- Pilih tingkat kesulitan --</option>
                            <option value="easy" class="text-green-600">Easy</option>
                            <option value="medium" class="text-yellow-600">Medium</option>
                            <option value="hard" class="text-red-600">Hard</option>
                        </select>
                    </div>

                    {{-- Pilih Team Leader --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Pilih Team Leader
                        </label>
                        <select name="leader_id" required
                            class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none bg-white">
                            <option value="">-- Pilih team leader --</option>
                            @foreach($leaders as $leader)
                                <option value="{{ $leader->user_id }}">{{ $leader->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Anggota Project (checkbox list untuk mobile-friendly) --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Anggota Project
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            @foreach($members as $member)
                                @php
                                    $workingCount = $member->projectsAsMember()->where('status', '!=', 'done')->count();
                                    $isWorking = $workingCount > 0 && in_array($member->role, ['developer','designer']);
                                @endphp
                                <label class="flex items-center gap-3 p-2 rounded hover:bg-gray-50">
                                    <input type="checkbox" name="members[]" value="{{ $member->user_id }}" class="h-4 w-4" {{ $isWorking ? 'disabled' : '' }}>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">{{ $member->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($member->role) }}</div>
                                    </div>
                                    <div class="text-right">
                                        @if($isWorking)
                                            <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">Working</span>
                                        @else
                                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Available</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Pilih anggota dengan men-tap kotak pada masing-masing nama (mobile friendly).</p>
                    </div>

                    {{-- Deadline --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Deadline
                        </label>
                        <input type="date" name="deadline" id="deadline" min="{{ date('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>

                    {{-- Submit Button --}}
                    <div class="text-center pt-4 sm:pt-6">
                        <button type="submit"
                            class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold px-6 sm:px-8 py-2 sm:py-3 rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl text-sm sm:text-base">
                            <span class="flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Project
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>
@endsection
