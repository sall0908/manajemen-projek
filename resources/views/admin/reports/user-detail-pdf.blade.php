<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan User - {{ $user->full_name }}</title>
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
    <h1>📊 Laporan User - {{ $user->full_name }}</h1>

    <div class="info-box">
        <p><strong>Nama:</strong> {{ $user->full_name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
    </div>

    @if($user->role === 'teamleader')
        <h2>Statistik Team Leader</h2>
        <div class="stat-box">
            <div class="stat-number">{{ $cardsCreated }}</div>
            <p>Card Dibuat</p>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $projectsCompleted }}</div>
            <p>Proyek Selesai</p>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ isset($projectsCreated) ? $projectsCreated->count() : 0 }}</div>
            <p>Total Proyek</p>
        </div>

        @if(isset($projectsCreated) && $projectsCreated->count() > 0)
            <h2>Daftar Proyek yang Dibuat</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Proyek</th>
                        <th>Status</th>
                        <th>Jumlah Card</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projectsCreated as $project)
                        <tr>
                            <td>{{ $project->project_name }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $project->status)) }}</td>
                            <td>{{ $project->cards_count }}</td>
                            <td>{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <h2>Statistik Developer/Designer</h2>
        <div class="stat-box">
            <div class="stat-number">{{ $subTasksCreated }}</div>
            <p>Sub-task Dibuat</p>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $subTasksCompleted }}</div>
            <p>Sub-task Selesai</p>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $formattedTime }}</div>
            <p>Total Waktu Kerja</p>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $timeLogs->count() }}</div>
            <p>Sesi Time Log</p>
        </div>

        @if(isset($assignedCards) && $assignedCards->count() > 0)
            <h2>Card yang Di-assign</h2>
            <table>
                <thead>
                    <tr>
                        <th>Judul Card</th>
                        <th>Proyek</th>
                        <th>Status</th>
                        <th>Sub-task</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedCards as $card)
                        <tr>
                            <td>{{ $card->card_title }}</td>
                            <td>{{ $card->project->project_name ?? '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $card->status)) }}</td>
                            <td>{{ $card->sub_tasks_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($timeLogs->count() > 0)
            <h2>Riwayat Time Log</h2>
            <table>
                <thead>
                    <tr>
                        <th>Card</th>
                        <th>Proyek</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeLogs as $log)
                        <tr>
                            <td>{{ $log->card->card_title ?? '-' }}</td>
                            <td>{{ $log->card->project->project_name ?? '-' }}</td>
                            <td>{{ $log->start_time ? \Carbon\Carbon::parse($log->start_time)->format('d M Y H:i') : '-' }}</td>
                            <td>{{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('d M Y H:i') : '-' }}</td>
                            <td>{{ $log->formatted_duration ?? '00:00:00' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif

    <p style="margin-top: 30px; text-align: center; color: #666;">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </p>
</body>
</html>

