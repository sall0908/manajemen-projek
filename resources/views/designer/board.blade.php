@extends('layouts.designer')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $project->project_name }}</h1>
                    <p class="text-gray-600 mt-2">{{ $project->description }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button onclick="openBlockerModal()" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Laporkan Blocker</span>
                    </button>
                    <a href="{{ route('designer.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        <!-- Cards List (Received Cards - No Work Buttons) -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📋 Cards Diterima</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($project->cards as $card)
                    <div class="bg-white rounded-lg shadow-md p-4 cursor-pointer hover:shadow-lg transition-shadow" onclick="expandCard({{ $card->card_id }})">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-800">{{ $card->card_title }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                @if($card->status === 'todo') bg-blue-100 text-blue-800
                                @elseif($card->status === 'in_progress') bg-amber-100 text-amber-800
                                @elseif($card->status === 'review') bg-purple-100 text-purple-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($card->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ \Illuminate\Support\Str::limit($card->description, 80) }}</p>

                        @if($card->assignedUsers->isNotEmpty())
                            <div class="flex items-center gap-2 mb-3 text-xs text-gray-500">
                                <i class="fas fa-user"></i>
                                <span>{{ $card->assignedUsers->first()->full_name }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                {{ $card->subTasks->count() }} SubTask{{ $card->subTasks->count() !== 1 ? 's' : '' }}
                            </span>
                            <button class="text-purple-600 text-sm font-semibold hover:underline">
                                Lihat Detail →
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-lg shadow-md p-8 text-center">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Belum ada card yang diterima</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Card Details Modal -->
        @foreach($project->cards as $card)
            <div id="cardModal{{ $card->card_id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 my-8 p-6">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-6 pb-4 border-b">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $card->card_title }}</h2>
                            <p class="text-gray-600 text-sm mt-1">{{ $card->description }}</p>
                        </div>
                        <button onclick="closeCardModal({{ $card->card_id }})" class="text-gray-500 hover:text-gray-700 text-3xl">×</button>
                    </div>

                    <!-- Create SubTask Form -->
                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <h3 class="font-bold text-purple-900 mb-3">➕ Tambah SubTask Baru</h3>
                        <form action="{{ route('designer.project.cards.store', $project->project_id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="card_id" value="{{ $card->card_id }}">

                            <input name="title" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Judul SubTask" required>

                            <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Deskripsi SubTask (opsional)"></textarea>

                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                Tambah SubTask
                            </button>
                        </form>
                    </div>

                    <!-- SubTasks Kanban -->
                    @if($card->subTasks->count() > 0)
                        <h3 class="font-bold text-gray-800 mb-4">📊 SubTask Workflow</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- To Do -->
                            <div class="bg-blue-50 rounded-lg p-3 border-l-4 border-blue-500">
                                <h4 class="font-semibold text-blue-900 mb-3">To Do ({{ $card->subTasks->where('status', 'todo')->count() }})</h4>
                                <div class="space-y-2">
                                    @forelse($card->subTasks->where('status', 'todo') as $st)
                                        <div class="bg-white rounded p-3 shadow-sm">
                                            <p class="font-semibold text-sm text-gray-800">{{ $st->title }}</p>
                                            @if($st->description)
                                                <p class="text-xs text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($st->description, 50) }}</p>
                                            @endif
                                            <form action="{{ route('designer.subtasks.update', $st->sub_task_id) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-xs py-1 rounded transition">
                                                    Mulai Kerjakan
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <p class="text-gray-400 text-sm text-center py-4">-</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- In Progress -->
                            <div class="bg-amber-50 rounded-lg p-3 border-l-4 border-amber-500">
                                <h4 class="font-semibold text-amber-900 mb-3">In Progress ({{ $card->subTasks->where('status', 'in_progress')->count() }})</h4>
                                <div class="space-y-2">
                                    @forelse($card->subTasks->where('status', 'in_progress') as $st)
                                        <div class="bg-white rounded p-3 shadow-sm">
                                            <p class="font-semibold text-sm text-gray-800">{{ $st->title }}</p>
                                            @if($st->description)
                                                <p class="text-xs text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($st->description, 50) }}</p>
                                            @endif

                                            <!-- Timer -->
                                            @php
                                                $totalDuration = $st->timeLogs->sum('duration_seconds');
                                                $runningLog = $st->timeLogs->where('user_id', Auth::id())->where('end_time', null)->first();
                                            @endphp
                                            <div class="bg-gray-900 text-white text-center py-2 rounded text-sm font-mono mt-2 mb-2" id="timer-{{ $st->sub_task_id }}" data-total="{{ $totalDuration }}" data-running="{{ $runningLog ? 'true' : 'false' }}" data-start="{{ $runningLog ? $runningLog->start_time->timestamp : '' }}">
                                                {{ gmdate('H:i:s', $totalDuration) }}
                                            </div>

                                            <div class="flex gap-1">
                                                @php
                                                    $runningLog = $st->timeLogs->where('user_id', Auth::id())->where('end_time', null)->first();
                                                @endphp

                                                @if($runningLog)
                                                    <form action="{{ route('designer.subtasks.stopTimer', $st->sub_task_id) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs py-1 rounded transition">
                                                            Stop
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('designer.subtasks.startTimer', $st->sub_task_id) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs py-1 rounded transition">
                                                            ▶ Timer
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('designer.subtasks.update', $st->sub_task_id) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="review">
                                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-xs py-1 rounded transition">
                                                        Review
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-400 text-sm text-center py-4">-</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Review -->
                            <div class="bg-purple-50 rounded-lg p-3 border-l-4 border-purple-500">
                                <h4 class="font-semibold text-purple-900 mb-3">Review ({{ $card->subTasks->where('status', 'review')->count() }})</h4>
                                <div class="space-y-2">
                                    @forelse($card->subTasks->where('status', 'review') as $st)
                                        <div class="bg-white rounded p-3 shadow-sm">
                                            <p class="font-semibold text-sm text-gray-800">{{ $st->title }}</p>
                                            @if($st->description)
                                                <p class="text-xs text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($st->description, 50) }}</p>
                                            @endif
                                            <div class="flex gap-1 mt-2">
                                                <form action="{{ route('designer.subtasks.update', $st->sub_task_id) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="in_progress">
                                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs py-1 rounded transition">
                                                        Kembali
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-400 text-sm text-center py-4">-</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Done -->
                            <div class="bg-green-50 rounded-lg p-3 border-l-4 border-green-500">
                                <h4 class="font-semibold text-green-900 mb-3">Done ({{ $card->subTasks->where('status', 'done')->count() }})</h4>
                                <div class="space-y-2">
                                    @forelse($card->subTasks->where('status', 'done') as $st)
                                        <div class="bg-white rounded p-3 shadow-sm opacity-75">
                                            <p class="font-semibold text-sm text-gray-800 line-through">{{ $st->title }}</p>
                                            <p class="text-xs text-gray-500 mt-1">✓ Selesai</p>
                                        </div>
                                    @empty
                                        <p class="text-gray-400 text-sm text-center py-4">-</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <i class="fas fa-tasks text-gray-300 text-3xl mb-2"></i>
                            <p class="text-gray-500">Belum ada SubTask. Tambahkan SubTask untuk memulai.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Blocker Modal -->
    <div id="blockerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-96 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Laporkan Blocker</h2>
                <button onclick="closeBlockerModal()" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
            </div>
            <form action="{{ route('designer.blockers.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subtask (Opsional)</label>
                    <select name="subtask_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Subtask --</option>
                        @foreach($project->cards as $card)
                            @if($card->subTasks)
                                @foreach($card->subTasks as $subtask)
                                    <option value="{{ $subtask->sub_task_id }}">{{ $card->card_title }} → {{ $subtask->title }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Masalah <span class="text-red-500">*</span></label>
                    <textarea name="issue_description" required minlength="10" maxlength="1000" rows="5"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Jelaskan masalah yang Anda hadapi..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg transition-colors">
                        Kirim Laporan
                    </button>
                    <button type="button" onclick="closeBlockerModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function expandCard(cardId) {
            document.getElementById('cardModal' + cardId).classList.remove('hidden');
        }

        function closeCardModal(cardId) {
            document.getElementById('cardModal' + cardId).classList.add('hidden');
        }

        function openBlockerModal() {
            document.getElementById('blockerModal').classList.remove('hidden');
        }

        function closeBlockerModal() {
            document.getElementById('blockerModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.id.startsWith('cardModal')) {
                e.target.classList.add('hidden');
            }
        });

        document.getElementById('blockerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBlockerModal();
            }
        });

        // Live timer update
        function formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        setInterval(() => {
            document.querySelectorAll('[id^="timer-"]').forEach(timerElement => {
                const isRunning = timerElement.dataset.running === 'true';
                if (isRunning) {
                    const startTimestamp = parseInt(timerElement.dataset.start);
                    const currentTime = Math.floor(Date.now() / 1000);
                    const elapsedSinceStart = currentTime - startTimestamp;
                    const totalDuration = parseInt(timerElement.dataset.total);
                    const currentTotal = totalDuration + elapsedSinceStart;
                    timerElement.textContent = formatTime(currentTotal);
                }
            });
        }, 1000);
    </script>
</body>
</html>
@endsection
