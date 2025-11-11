@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Kembali ke Dashboard
        </a>
    </div>
    <h2 class="text-2xl sm:text-3xl font-bold text-blue-700 mb-6 text-center">📊 Laporan Proyek</h2>

    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-blue-100">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-left font-semibold">Nama Proyek</th>
                    <th class="py-3 px-4 text-left font-semibold">Team Leader</th>
                    <th class="py-3 px-4 text-left font-semibold">Status</th>
                    <th class="py-3 px-4 text-left font-semibold">Jumlah Card</th>
                    <th class="py-3 px-4 text-left font-semibold">Anggota Tim</th>
                    <th class="py-3 px-4 text-left font-semibold">Deadline</th>
                    <th class="py-3 px-4 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-100">
                @foreach($projects as $project)
                    @php
                        // Tentukan warna berdasarkan status
                        $statusClass = $project->status === 'done' ? 'bg-green-100 text-green-700' :
                                      ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                                      ($project->status === 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'));
                    @endphp

                    <tr class="hover:bg-blue-50 transition">
                        <td class="py-3 px-4 text-gray-800 font-medium">{{ $project->project_name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $project->leader->full_name ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center text-blue-700 font-semibold">{{ $project->cards_count }}</td>
                        <td class="py-3 px-4 text-center text-blue-700 font-semibold">{{ $project->members_count }}</td>
                        <td class="py-3 px-4 text-gray-600">
                            {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.reports.projectDetail', $project->project_id) }}"
                               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition inline-block">
                                📊 Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection

