@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4 sm:py-6">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8">
            <div class="mb-4 sm:mb-0">
                <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                    Daftar Project
                </h2>
                <p class="text-gray-600 mt-2 text-sm sm:text-base">Kelola dan pantau semua project tim Anda</p>
            </div>
            <a href="{{ route('admin.projects.create') }}"
               class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-indigo-800 transition duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Project Baru
            </a>
        </div>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Projects Table View --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow p-3 sm:p-4 border border-blue-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Leader</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Members</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Deadline</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Status</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($projects as $index => $project)
                            @php
                                $totalCards = $project->cards->count();
                                $doneCards = $project->cards->where('status','done')->count();
                                $percent = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
                            @endphp
                            <tr>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3">
                                    <div class="font-medium text-gray-800 text-xs sm:text-sm">{{ $project->project_name }}</div>
                                    <div class="text-xs text-gray-500 hidden sm:block">{{ Str::limit($project->description ?? '-', 80) }}</div>
                                    <div class="text-xs text-gray-500 sm:hidden">{{ Str::limit($project->description ?? '-', 40) }}</div>
                                    <div class="text-xs sm:hidden mt-1">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $project->status === 'done' ? 'bg-green-100 text-green-700' :
                                               ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ ucfirst($project->status ?? 'pending') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700 hidden md:table-cell">{{ $project->leader->full_name ?? '-' }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700 hidden lg:table-cell">{{ $project->members->count() }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm {{ $project->deadline && \Carbon\Carbon::parse($project->deadline)->isPast() ? 'text-red-600 font-semibold' : 'text-gray-600' }} hidden lg:table-cell">
                                    {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 w-32 sm:w-48">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-600 whitespace-nowrap">{{ $percent }}%</div>
                                    </div>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm hidden sm:table-cell">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $project->status === 'done' ? 'bg-green-100 text-green-700' :
                                           ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($project->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3">
                                        <a href="{{ route('admin.projects.show', $project->project_id) }}" class="text-blue-600 hover:text-blue-800 whitespace-nowrap">Lihat</a>
                                        <form action="{{ route('admin.projects.destroy', $project->project_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus project ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 whitespace-nowrap">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-xs sm:text-sm text-gray-500">Belum ada project. <a href="{{ route('admin.projects.create') }}" class="text-blue-600 hover:underline">Buat Project Baru</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
