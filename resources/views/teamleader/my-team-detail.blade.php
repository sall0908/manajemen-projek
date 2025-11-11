@extends('layouts.teamleader')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $project->project_name }}</h1>
                    <p class="text-gray-600 mt-2">{{ $project->description }}</p>
                </div>
                <a href="{{ route('teamleader.my-team') }}" class="text-blue-600 hover:underline text-sm font-semibold">← Kembali</a>
            </div>
        </div>

        <!-- Team Members -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">👥 Anggota Tim</h2>

            @if($project->members->isEmpty())
                <p class="text-gray-500">Belum ada anggota tim dalam proyek ini.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($project->members as $member)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h3 class="font-bold text-gray-800">{{ $member->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $member->email }}</p>
                            <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                {{ $member->role }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
