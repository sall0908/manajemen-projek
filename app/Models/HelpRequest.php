<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpRequest extends Model
{
    use HasFactory;

    protected $table = 'help_requests';
    protected $primaryKey = 'request_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'subtask_id',
        'user_id',
        'issue_description',
        'status',
        'resolved_by',
        'resolution_notes',
        'created_at',
        'resolved_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Relasi ke user yang melaporkan blocker (Developer/Designer)
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke user yang menyelesaikan blocker (Team Lead)
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by', 'user_id');
    }

    /**
     * Relasi ke subtask yang bermasalah
     */
    public function subTask(): BelongsTo
    {
        return $this->belongsTo(SubTask::class, 'subtask_id', 'sub_task_id');
    }
}
