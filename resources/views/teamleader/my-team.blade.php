@extends('layouts.teamleader')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">My Team</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Daftar proyek yang ditugaskan kepada Anda beserta anggota tim.</p>
        </div>

        @if($projects->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                <i class="fas fa-inbox text-gray-300 text-3xl sm:text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm sm:text-base">Belum ada proyek yang ditugaskan kepada Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($projects as $project)
                    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition duration-200">
                        <h3 class="font-bold text-gray-800 text-base sm:text-lg mb-2">{{ $project->project_name }}</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-2 line-clamp-2">{{ \Illuminate\Support\Str::limit($project->description, 80) }}</p>
                        <p class="text-xs text-gray-500 mb-3">Anggota: {{ $project->members->count() }} anggota</p>
                        <a href="{{ route('teamleader.my-team.show', $project->project_id) }}" class="text-blue-600 hover:underline text-xs sm:text-sm font-semibold inline-flex items-center">Lihat Detail <span class="ml-1">→</span></a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
