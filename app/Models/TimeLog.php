<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;

    protected $table = 'time_logs';
    public $timestamps = true;

    protected $fillable = [
        'card_id',
        'sub_task_id',
        'user_id',
        'start_time',
        'end_time',
        'duration_seconds',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }

    public function subTask()
    {
        return $this->belongsTo(SubTask::class, 'sub_task_id', 'sub_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Hitung durasi dalam format jam:menit:detik
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_seconds) {
            return '00:00:00';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    /**
     * Cek apakah time log masih berjalan (belum dihentikan)
     */
    public function isRunning()
    {
        return $this->end_time === null;
    }
}
