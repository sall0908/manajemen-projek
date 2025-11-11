<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->project_name }} - Developer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .kanban-column {
            min-height: 600px;
        }

        .card-item {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .todo-card {
            border-left-color: #3b82f6;
        }

        .progress-card {
            border-left-color: #f59e0b;
        }

        .review-card {
            border-left-color: #8b5cf6;
        }

        .done-card {
            border-left-color: #10b981;
        }

        .subtask-item {
            transition: background-color 0.2s;
        }

        .subtask-item:hover {
            background-color: #f8fafc;
        }

        .comment-section {
            max-height: 150px;
            overflow-y: auto;
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            background-color: #1e293b;
            color: #f1f5f9;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $project->project_name }}</h1>
                    <p class="text-gray-600 mt-2">{{ $project->description }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button onclick="toggleCardForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Subtask</span>
                    </button>
                    <button onclick="openBlockerModal()" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Laporkan Blocker</span>
                    </button>
                    <a href="{{ route('developer.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Tambah Subtask -->
        <div id="cardForm" class="hidden mb-6 bg-white rounded-xl shadow-lg p-6 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Tambah Subtask Baru</h3>
                <button onclick="toggleCardForm()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('developer.project.cards.store', $project->project_id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="card_id">
                            Pilih Card
                        </label>
                        <select id="card_id" name="card_id" class="w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">-- Pilih card --</option>
                            @foreach($project->cards as $c)
                                <option value="{{ $c->card_id }}">#{{ $c->card_id }} - {{ $c->card_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            Judul Subtask
                        </label>
                        <input class="w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               id="title" name="title" type="text" placeholder="Masukkan judul subtask" required>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Deskripsi Subtask
                    </label>
                    <textarea class="w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              id="description" name="description" rows="3" placeholder="Masukkan deskripsi subtask"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleCardForm()" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-save"></i>
                        <span>Simpan Subtask</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- To Do Column -->
            <div class="bg-white rounded-xl shadow-md kanban-column">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-blue-600 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">To Do</h2>
                        <span class="bg-blue-800 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $project->cards->where('status', 'todo')->count() }}
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($project->cards->where('status', 'todo') as $card)
                    <div class="card-item todo-card bg-white rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-800">{{ $card->card_title }}</h3>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">To Do</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <!-- Assigned User -->
                        @if($card->assignedUsers->isNotEmpty())
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PIC: <strong>{{ $card->assignedUsers->first()->full_name }}</strong>
                                </p>
                            </div>
                        @endif

                        <!-- Subtasks -->
                        @if($card->subTasks && $card->subTasks->count())
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                    <i class="fas fa-tasks text-blue-500"></i>
                                    <span>Subtasks</span>
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($card->subTasks as $st)
                                        <li class="text-sm flex items-start gap-2 subtask-item p-2 rounded-md cursor-pointer"
                                            onclick="toggleSubTask({{ $st->sub_task_id }}, this)">
                                            <input type="checkbox"
                                                   class="mt-1 rounded text-blue-500 focus:ring-blue-500"
                                                   {{ $st->is_completed ? 'checked' : '' }}
                                                   onclick="event.stopPropagation();"
                                                   readonly>
                                            <div class="flex-1">
                                                <span class="text-gray-800 {{ $st->is_completed ? 'line-through text-gray-500' : '' }}">{{ $st->title }}</span>
                                                @if($st->description)
                                                    <p class="text-gray-500 text-xs mt-1">{{ $st->description }}</p>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Action Button -->
                        @if($card->assignedUsers && $card->assignedUsers->contains('user_id', Auth::id()))
                            <form action="{{ route('developer.cards.inProgress', $card->card_id) }}" method="POST" class="mb-4">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class="fas fa-play"></i>
                                    <span>Mulai Kerjakan</span>
                                </button>
                            </form>
                        @endif

                        <!-- Comments Section -->
                        <div class="border-t pt-3">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class="fas fa-comments text-blue-500"></i>
                                <span>Komentar</span>
                            </h4>
                            @if($card->comments->count() > 0)
                                <div class="comment-section mb-3 space-y-2">
                                    @foreach($card->comments as $comment)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between items-start">
                                                <strong class="text-xs text-blue-600">{{ $comment->user->full_name ?? 'User' }}</strong>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm mt-1">{{ $comment->comment_text }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm text-center py-2">Belum ada komentar.</p>
                            @endif

                            <form method="POST" action="{{ route('developer.project.comment', $project->project_id) }}">
                                @csrf
                                <input type="hidden" name="card_id" value="{{ $card->card_id }}">
                                <div class="flex gap-2">
                                    <textarea name="content" rows="1" class="flex-1 border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tulis komentar..." required></textarea>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-white rounded-xl shadow-md kanban-column">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-amber-500 to-amber-600 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">In Progress</h2>
                        <span class="bg-amber-800 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $project->cards->where('status', 'in_progress')->count() }}
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($project->cards->where('status', 'in_progress') as $card)
                    <div class="card-item progress-card bg-white rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-800">{{ $card->card_title }}</h3>
                            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full">In Progress</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <!-- Assigned User -->
                        @if($card->assignedUsers->isNotEmpty())
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center text-white text-xs">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PIC: <strong>{{ $card->assignedUsers->first()->full_name }}</strong>
                                </p>
                            </div>
                        @endif

                        <!-- Subtasks -->
                        @if($card->subTasks && $card->subTasks->count())
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                    <i class="fas fa-tasks text-amber-500"></i>
                                    <span>Subtasks</span>
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($card->subTasks as $st)
                                        <li class="text-sm flex items-start gap-2 subtask-item p-2 rounded-md cursor-pointer"
                                            onclick="toggleSubTask({{ $st->sub_task_id }}, this)">
                                            <input type="checkbox"
                                                   class="mt-1 rounded text-amber-500 focus:ring-amber-500"
                                                   {{ $st->is_completed ? 'checked' : '' }}
                                                   onclick="event.stopPropagation();"
                                                   readonly>
                                            <div class="flex-1">
                                                <span class="text-gray-800 {{ $st->is_completed ? 'line-through text-gray-500' : '' }}">{{ $st->title }}</span>
                                                @if($st->description)
                                                    <p class="text-gray-500 text-xs mt-1">{{ $st->description }}</p>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Time Tracking -->
                        <div class="mb-4 bg-gray-50 rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class="fas fa-clock text-amber-500"></i>
                                <span>Time Tracking</span>
                            </h4>
                            <div id="timer-{{ $card->card_id }}" class="timer-display text-center py-2 rounded-lg mb-2" data-total-seconds="{{ $card->timeLogs->sum('duration_seconds') }}">
                                @php
                                    $totalDuration = $card->timeLogs->sum('duration_seconds');
                                    echo gmdate('H:i:s', $totalDuration);
                                @endphp
                            </div>
                            @php
                                $runningLog = $card->timeLogs->where('user_id', Auth::id())->where('end_time', null)->first();
                            @endphp
                            @if($runningLog)
                                <form action="{{ route('developer.cards.stopTimer', $card->card_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                        <i class="fas fa-stop"></i>
                                        <span>Stop Timer</span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('developer.cards.inProgress', $card->card_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                        <i class="fas fa-play"></i>
                                        <span>Mulai Kerjakan</span>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Comments Section -->
                        <div class="border-t pt-3">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class="fas fa-comments text-amber-500"></i>
                                <span>Komentar</span>
                            </h4>
                            @if($card->comments->count() > 0)
                                <div class="comment-section mb-3 space-y-2">
                                    @foreach($card->comments as $comment)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between items-start">
                                                <strong class="text-xs text-amber-600">{{ $comment->user->full_name ?? 'User' }}</strong>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm mt-1">{{ $comment->comment_text }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm text-center py-2">Belum ada komentar.</p>
                            @endif

                            <form method="POST" action="{{ route('developer.project.comment', $project->project_id) }}">
                                @csrf
                                <input type="hidden" name="card_id" value="{{ $card->card_id }}">
                                <div class="flex gap-2">
                                    <textarea name="content" rows="1" class="flex-1 border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-1 focus:ring-amber-500" placeholder="Tulis komentar..." required></textarea>
                                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white p-2 rounded-lg transition-colors">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-4">
                            <form action="{{ route('developer.cards.review', $card->card_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class="fas fa-eye"></i>
                                    <span>Minta Review</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Review Column -->
            <div class="bg-white rounded-xl shadow-md kanban-column">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-purple-500 to-purple-600 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">Review</h2>
                        <span class="bg-purple-800 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $project->cards->where('status', 'review')->count() }}
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($project->cards->where('status', 'review') as $card)
                    <div class="card-item review-card bg-white rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-800">{{ $card->card_title }}</h3>
                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Review</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <!-- Assigned User -->
                        @if($card->assignedUsers->isNotEmpty())
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PIC: <strong>{{ $card->assignedUsers->first()->full_name }}</strong>
                                </p>
                            </div>
                        @endif

                        <!-- Subtasks -->
                        @if($card->subTasks && $card->subTasks->count())
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                    <i class="fas fa-tasks text-purple-500"></i>
                                    <span>Subtasks</span>
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($card->subTasks as $st)
                                        <li class="text-sm flex items-start gap-2 subtask-item p-2 rounded-md cursor-pointer"
                                            onclick="toggleSubTask({{ $st->sub_task_id }}, this)">
                                            <input type="checkbox"
                                                   class="mt-1 rounded text-purple-500 focus:ring-purple-500"
                                                   {{ $st->is_completed ? 'checked' : '' }}
                                                   onclick="event.stopPropagation();"
                                                   readonly>
                                            <div class="flex-1">
                                                <span class="text-gray-800 {{ $st->is_completed ? 'line-through text-gray-500' : '' }}">{{ $st->title }}</span>
                                                @if($st->description)
                                                    <p class="text-gray-500 text-xs mt-1">{{ $st->description }}</p>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Comments Section -->
                        <div class="border-t pt-3">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class="fas fa-comments text-purple-500"></i>
                                <span>Komentar</span>
                            </h4>
                            @if($card->comments->count() > 0)
                                <div class="comment-section mb-3 space-y-2">
                                    @foreach($card->comments as $comment)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between items-start">
                                                <strong class="text-xs text-purple-600">{{ $comment->user->full_name ?? 'User' }}</strong>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm mt-1">{{ $comment->comment_text }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm text-center py-2">Belum ada komentar.</p>
                            @endif

                            <form method="POST" action="{{ route('developer.project.comment', $project->project_id) }}">
                                @csrf
                                <input type="hidden" name="card_id" value="{{ $card->card_id }}">
                                <div class="flex gap-2">
                                    <textarea name="content" rows="1" class="flex-1 border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500" placeholder="Tulis komentar..." required></textarea>
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-lg transition-colors">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-4">
                            <form action="{{ route('developer.cards.done', $card->card_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class="fas fa-check"></i>
                                    <span>Tandai Selesai</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-white rounded-xl shadow-md kanban-column">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-green-500 to-green-600 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">Done</h2>
                        <span class="bg-green-800 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $project->cards->where('status', 'done')->count() }}
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($project->cards->where('status', 'done') as $card)
                    <div class="card-item done-card bg-white rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-800">{{ $card->card_title }}</h3>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Done</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <!-- Assigned User -->
                        @if($card->assignedUsers->isNotEmpty())
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PIC: <strong>{{ $card->assignedUsers->first()->full_name }}</strong>
                                </p>
                            </div>
                        @endif

                        <!-- Subtasks -->
                        @if($card->subTasks && $card->subTasks->count())
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                    <i class="fas fa-tasks text-green-500"></i>
                                    <span>Subtasks</span>
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($card->subTasks as $st)
                                        <li class="text-sm flex items-start gap-2 subtask-item p-2 rounded-md cursor-pointer"
                                            onclick="toggleSubTask({{ $st->sub_task_id }}, this)">
                                            <input type="checkbox"
                                                   class="mt-1 rounded text-green-500 focus:ring-green-500"
                                                   {{ $st->is_completed ? 'checked' : '' }}
                                                   onclick="event.stopPropagation();"
                                                   readonly>
                                            <div class="flex-1">
                                                <span class="text-gray-800 {{ $st->is_completed ? 'line-through text-gray-500' : '' }}">{{ $st->title }}</span>
                                                @if($st->description)
                                                    <p class="text-gray-500 text-xs mt-1">{{ $st->description }}</p>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Comments Section -->
                        <div class="border-t pt-3">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class="fas fa-comments text-green-500"></i>
                                <span>Komentar</span>
                            </h4>
                            @if($card->comments->count() > 0)
                                <div class="comment-section mb-3 space-y-2">
                                    @foreach($card->comments as $comment)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between items-start">
                                                <strong class="text-xs text-green-600">{{ $comment->user->full_name ?? 'User' }}</strong>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm mt-1">{{ $comment->comment_text }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm text-center py-2">Belum ada komentar.</p>
                            @endif

                            <form method="POST" action="{{ route('developer.project.comment', $project->project_id) }}">
                                @csrf
                                <input type="hidden" name="card_id" value="{{ $card->card_id }}">
                                <div class="flex gap-2">
                                    <textarea name="content" rows="1" class="flex-1 border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Tulis komentar..." required></textarea>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleCardForm() {
        const form = document.getElementById('cardForm');
        form.classList.toggle('hidden');
    }

    function toggleSubTask(subTaskId, element) {
        fetch(`/developer/subtasks/${subTaskId}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const checkbox = element.querySelector('input[type="checkbox"]');
                const span = element.querySelector('span.text-gray-800');

                checkbox.checked = data.is_completed;
                if (data.is_completed) {
                    span.classList.add('line-through', 'text-gray-500');
                    span.classList.remove('text-gray-800');
                } else {
                    span.classList.remove('line-through', 'text-gray-500');
                    span.classList.add('text-gray-800');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengupdate sub-task');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setInterval(function () {
            document.querySelectorAll('[id^="timer-"]').forEach(function (timerElement) {
                let cardId = timerElement.id.split('-')[1];
                fetch(`/developer/cards/${cardId}/running-timer`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.running) {
                            let totalSeconds = parseInt(timerElement.dataset.totalSeconds || 0) + data.elapsed_seconds;
                            timerElement.textContent = new Date(totalSeconds * 1000).toISOString().substr(11, 8);
                        }
                    });
            });
        }, 1000);
    });
        });
    </script>

    <!-- Blocker Modal -->
    <div id="blockerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none; align-items: center; justify-content: center;">
        <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-96 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Laporkan Blocker</h2>
                <button onclick="closeBlockerModal()" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
            </div>
            <form action="{{ route('developer.blockers.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subtask (Opsional)</label>
                    <select name="subtask_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Jelaskan masalah yang Anda hadapi..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimal 10 karakter, maksimal 1000 karakter</p>
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
    </div>

    <script>
        function openBlockerModal() {
            const modal = document.getElementById('blockerModal');
            modal.style.display = 'flex';
        }

        function closeBlockerModal() {
            const modal = document.getElementById('blockerModal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('blockerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBlockerModal();
            }
        });
    </script>
</body>
</html>

```
