@extends('layouts.designer')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-start mb-8 gap-4 flex-wrap">
            <div>
                <h1 class="text-4xl font-bold text-purple-900"> {{ $project->project_name }}</h1>
                <p class="text-gray-600 mt-2">{{ $project->description }}</p>
            </div>
            <div class="flex gap-2">
                <button onclick="document.getElementById('cardForm').classList.toggle('hidden')" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold">+ Tambah SubTask</button>
                <button onclick="openBlocker()" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold"> Blocker</button>
                <a href="{{ route('designer.dashboard') }}" class="bg-gray-300 px-4 py-2 rounded-lg font-bold"> Kembali</a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        <div id="cardForm" class="hidden bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('designer.project.cards.store', $project->project_id) }}" method="POST" class="grid md:grid-cols-2 gap-4">
                @csrf
                <select name="card_id" class="border rounded px-3 py-2" required>
                    <option>Pilih Card...</option>
                    @foreach($project->cards as $c)
                        <option value="{{ $c->card_id }}">#{{ $c->card_id }} - {{ $c->card_title }}</option>
                    @endforeach
                </select>
                <input name="title" type="text" class="border rounded px-3 py-2" placeholder="Judul" required>
                <textarea name="description" rows="2" class="md:col-span-2 border rounded px-3 py-2"></textarea>
                <button type="submit" class="bg-green-600 text-white py-2 rounded font-bold">Simpan</button>
                <button type="button" onclick="document.getElementById('cardForm').classList.add('hidden')" class="bg-gray-400 text-white py-2 rounded font-bold">Batal</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- To Do Column -->
            <div class="bg-white rounded-xl border-t-4 border-blue-500 overflow-hidden flex flex-col">
                <div class="bg-blue-100 p-4 border-b">
                    <h2 class="font-bold text-blue-700">To Do ({{ $todo->count() }})</h2>
                </div>
                <div class="p-3 space-y-3 overflow-y-auto flex-1">
                    @forelse($todo as $subtask)
                        <div class="bg-blue-50 rounded border-l-4 border-blue-500 p-3">
                            <h3 class="font-bold text-sm mb-2">{{ $subtask->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">Card: {{ $subtask->card->card_title ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 60) }}</p>
                            
                            <form action="{{ route('designer.subtasks.update', $subtask->sub_task_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button class="w-full bg-purple-500 hover:bg-purple-600 text-white text-xs py-1 rounded transition">
                                    Mulai Kerjakan
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm py-8">Tidak ada subtask</p>
                    @endforelse
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-white rounded-xl border-t-4 border-orange-500 overflow-hidden flex flex-col">
                <div class="bg-orange-100 p-4 border-b">
                    <h2 class="font-bold text-orange-700">In Progress ({{ $inProgress->count() }})</h2>
                </div>
                <div class="p-3 space-y-3 overflow-y-auto flex-1">
                    @forelse($inProgress as $subtask)
                        <div class="bg-orange-50 rounded border-l-4 border-orange-500 p-3">
                            <h3 class="font-bold text-sm mb-2">{{ $subtask->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">Card: {{ $subtask->card->card_title ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 60) }}</p>
                            
                            <div class="flex gap-2">
                                <form action="{{ route('designer.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="review">
                                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white text-xs py-1 rounded transition">
                                        Review
                                    </button>
                                </form>
                                <form action="{{ route('designer.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="todo">
                                    <button class="w-full bg-blue-400 hover:bg-blue-500 text-white text-xs py-1 rounded transition">
                                        Kembali
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm py-8">Tidak ada subtask</p>
                    @endforelse
                </div>
            </div>

            <!-- Review Column -->
            <div class="bg-white rounded-xl border-t-4 border-purple-500 overflow-hidden flex flex-col">
                <div class="bg-purple-100 p-4 border-b">
                    <h2 class="font-bold text-purple-700">Review ({{ $review->count() }})</h2>
                </div>
                <div class="p-3 space-y-3 overflow-y-auto flex-1">
                    @forelse($review as $subtask)
                        <div class="bg-purple-50 rounded border-l-4 border-purple-500 p-3">
                            <h3 class="font-bold text-sm mb-2">{{ $subtask->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">Card: {{ $subtask->card->card_title ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 60) }}</p>
                            
                            <div class="flex gap-2">
                                <form action="{{ route('designer.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="done">
                                    <button class="w-full bg-green-600 hover:bg-green-700 text-white text-xs py-1 rounded transition">
                                        Selesai
                                    </button>
                                </form>
                                <form action="{{ route('designer.subtasks.update', $subtask->sub_task_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button class="w-full bg-blue-400 hover:bg-blue-500 text-white text-xs py-1 rounded transition">
                                        Kembali
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm py-8">Tidak ada subtask</p>
                    @endforelse
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-white rounded-xl border-t-4 border-green-500 overflow-hidden flex flex-col">
                <div class="bg-green-100 p-4 border-b">
                    <h2 class="font-bold text-green-700">Done ({{ $done->count() }})</h2>
                </div>
                <div class="p-3 space-y-3 overflow-y-auto flex-1">
                    @forelse($done as $subtask)
                        <div class="bg-green-50 rounded border-l-4 border-green-500 p-3">
                            <h3 class="font-bold text-sm mb-2">{{ $subtask->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">Card: {{ $subtask->card->card_title ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mb-3">{{ \Illuminate\Support\Str::limit($subtask->description, 60) }}</p>
                            
                            <form action="{{ route('designer.subtasks.update', $subtask->sub_task_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="review">
                                <button class="w-full bg-blue-400 hover:bg-blue-500 text-white text-xs py-1 rounded transition">
                                    Kembali ke Review
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm py-8">Tidak ada subtask</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div id="blockerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none; align-items: center; justify-content: center;">
    <div class="bg-white rounded-xl p-6 w-96">
        <h2 class="text-2xl font-bold mb-4">Blocker</h2>
        <form action="{{ route('designer.blockers.store') }}" method="POST">
            @csrf
            <textarea name="issue_description" required rows="4" class="w-full border rounded px-3 py-2 mb-3"></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded">Kirim</button>
                <button type="button" onclick="closeBlocker()" class="flex-1 bg-gray-400 text-white py-2 rounded">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openBlocker() { document.getElementById('blockerModal').style.display = 'flex'; }
function closeBlocker() { document.getElementById('blockerModal').style.display = 'none'; }
</script>
@endsection
