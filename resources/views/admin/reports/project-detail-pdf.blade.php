<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Proyek - {{ $project->project_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 10px; }
        h2 { color: #1e40af; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1e40af; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border: 1px solid #ddd; }
        .info-box { background-color: #f3f4f6; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .stat-box { display: inline-block; width: 23%; margin: 1%; padding: 15px; background-color: #eff6ff; border-radius: 5px; text-align: center; }
        .stat-number { font-size: 24px; font-weight: bold; color: #1e40af; }
    </style>
</head>
<body>
    <h1>📊 Laporan Proyek - {{ $project->project_name }}</h1>

    <div class="info-box">
        <p><strong>Nama Proyek:</strong> {{ $project->project_name }}</p>
        <p><strong>Deskripsi:</strong> {{ $project->description ?? '-' }}</p>
        <p><strong>Team Leader:</strong> {{ $project->leader->full_name ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $project->status)) }}</p>
        <p><strong>Kesulitan:</strong> {{ ucfirst($project->difficulty ?? 'medium') }}</p>
        <p><strong>Deadline:</strong> {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</p>
        <p><strong>Tanggal Dibuat:</strong> {{ $project->created_at ? \Carbon\Carbon::parse($project->created_at)->format('d M Y') : '-' }}</p>
    </div>

    <h2>Statistik Proyek</h2>
    <div class="stat-box">
        <div class="stat-number">{{ $totalCards }}</div>
        <p>Total Card</p>
    </div>
    <div class="stat-box">
        <div class="stat-number">{{ $cardsByStatus['done'] }}</div>
        <p>Card Selesai</p>
    </div>
    <div class="stat-box">
        <div class="stat-number">{{ $completedSubTasks }}/{{ $totalSubTasks }}</div>
        <p>Sub-task Selesai</p>
    </div>
    <div class="stat-box">
        <div class="stat-number">{{ $formattedTime }}</div>
        <p>Total Waktu</p>
    </div>

    <h2>Breakdown Status Card</h2>
    <table>
        <thead>
            <tr>
                <th>To Do</th>
                <th>In Progress</th>
                <th>Review</th>
                <th>Done</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">{{ $cardsByStatus['todo'] }}</td>
                <td style="text-align: center;">{{ $cardsByStatus['in_progress'] }}</td>
                <td style="text-align: center;">{{ $cardsByStatus['review'] }}</td>
                <td style="text-align: center;">{{ $cardsByStatus['done'] }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Anggota Tim</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Role</th>
                <th>Card Di-assign</th>
                <th>Sub-task</th>
                <th>Waktu Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teamMembers as $member)
                @php
                    $memberHours = floor($member['time_worked'] / 3600);
                    $memberMinutes = floor(($member['time_worked'] % 3600) / 60);
                    $memberSeconds = $member['time_worked'] % 60;
                    $memberFormattedTime = sprintf('%02d:%02d:%02d', $memberHours, $memberMinutes, $memberSeconds);
                @endphp
                <tr>
                    <td>{{ $member['user']->full_name }}</td>
                    <td>{{ ucfirst($member['user']->role) }}</td>
                    <td style="text-align: center;">{{ $member['assigned_cards_count'] }}</td>
                    <td style="text-align: center;">{{ $member['sub_tasks_completed'] }}/{{ $member['sub_tasks_count'] }}</td>
                    <td>{{ $memberFormattedTime }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada anggota tim</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Daftar Card</h2>
    <table>
        <thead>
            <tr>
                <th>Judul Card</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
                <th>Assigned To</th>
                <th>Sub-task</th>
            </tr>
        </thead>
        <tbody>
            @forelse($project->cards as $card)
                <tr>
                    <td>{{ $card->card_title }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $card->status)) }}</td>
                    <td>{{ $card->creator->full_name ?? '-' }}</td>
                    <td>
                        @if($card->assignedUsers->isNotEmpty())
                            {{ $card->assignedUsers->pluck('full_name')->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: center;">
                        {{ $card->subTasks->where('is_completed', true)->count() }}/{{ $card->subTasks->count() }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada card</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 30px; text-align: center; color: #666;">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </p>
</body>
</html>

