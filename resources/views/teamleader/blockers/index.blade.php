@extends('layouts.teamleader')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-6 sm:py-10">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-red-600 to-orange-600 rounded-2xl shadow-lg mb-4">
                <span class="text-2xl text-white">🚨</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-red-700 to-orange-600 bg-clip-text text-transparent mb-2">Permintaan Bantuan (Blocker)</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Kelola permintaan bantuan dari developer dan designer</p>
        </div>

        {{-- Pesan sukses / error --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Tabs / Filter -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('teamleader.blockers.index') }}" class="px-4 py-2 bg-white border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50">
                Semua
            </a>
            <a href="{{ route('teamleader.blockers.index') }}?status=pending" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                Pending
            </a>
            <a href="{{ route('teamleader.blockers.index') }}?status=in_progress" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                In Progress
            </a>
            <a href="{{ route('teamleader.blockers.index') }}?status=completed" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                Completed
            </a>
        </div>

        <!-- Requests List -->
        @if($requests->count() > 0)
            <div class="space-y-4">
                @foreach($requests as $req)
                    <div class="bg-white rounded-xl shadow-md border-l-4 {{ $req->status === 'pending' ? 'border-l-red-500' : ($req->status === 'in_progress' ? 'border-l-yellow-500' : 'border-l-green-500') }} p-6 hover:shadow-lg transition duration-200">
                        <!-- Header -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">
                                    {{ $req->reporter->full_name }}
                                    <span class="text-xs font-normal text-gray-500">({{ ucfirst($req->reporter->role) }})</span>
                                </h3>
                                @if($req->subTask)
                                    <p class="text-sm text-gray-600">📋 Subtask: <strong>{{ $req->subTask->title }}</strong></p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $req->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $req->status === 'pending' ? 'bg-red-100 text-red-800' :
                                       ($req->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                       ($req->status === 'fixed' ? 'bg-blue-100 text-blue-800' :
                                       'bg-green-100 text-green-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-800">{{ $req->issue_description }}</p>
                        </div>

                        <!-- Resolution Notes (if any) -->
                        @if($req->resolution_notes)
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm font-semibold text-blue-800 mb-1">Catatan Penyelesaian:</p>
                                <p class="text-gray-800">{{ $req->resolution_notes }}</p>
                            </div>
                        @endif

                        <!-- Update Form -->
                        <form action="{{ route('teamleader.blockers.update', $req->request_id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Update Status</label>
                                    <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $req->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="fixed" {{ $req->status === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="completed" {{ $req->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Progres</label>
                                    <textarea name="resolution_notes" rows="1" maxlength="500" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan catatan progres...">{{ $req->resolution_notes }}</textarea>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition-colors">
                                    💾 Simpan Update
                                </button>
                                @if($req->status !== 'completed')
                                    <form action="{{ route('teamleader.blockers.done', $req->request_id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg transition-colors">
                                            ✅ Tandai Selesai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </form>

                        @if($req->resolver)
                            <p class="text-xs text-gray-500 mt-3">Diselesaikan oleh: <strong>{{ $req->resolver->full_name }}</strong> pada {{ $req->resolved_at?->format('d M Y H:i') ?? '-' }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $requests->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="bg-white p-8 rounded-2xl shadow border border-gray-200 max-w-md mx-auto">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-1">Tidak ada permintaan</h3>
                    <p class="text-gray-500 text-sm">Semua permintaan bantuan sudah diselesaikan atau tidak ada permintaan baru.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
