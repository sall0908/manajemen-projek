@extends('layouts.teamleader')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">My Team</h1>
            <p class="text-gray-600 mt-2">Daftar proyek yang ditugaskan kepada Anda beserta anggota tim.</p>
        </div>

        @if($projects->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Belum ada proyek yang ditugaskan kepada Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($projects as $project)
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="font-bold text-gray-800">{{ $project->project_name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ \Illuminate\Support\Str::limit($project->description, 80) }}</p>
                        <p class="text-xs text-gray-500 mb-2">Anggota: {{ $project->members->count() }} anggota</p>
                        <a href="{{ route('teamleader.my-team.show', $project->project_id) }}" class="text-blue-600 hover:underline text-sm font-semibold">Lihat Detail →</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
