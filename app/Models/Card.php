<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SubTask;

class Card extends Model
{
    use HasFactory;

    protected $primaryKey = 'card_id';
    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'card_title',
        'description',
        'status',
        'board_id',
        'priority',
        'due_date',
        'created_by',
        'assigned_to',
        'estimated_hours',
        'actual_hours',
    ];

    /**
     * 🔗 Relasi ke project induknya
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    /**
     * 💬 Relasi ke komentar pada card
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'card_id', 'card_id');
    }

    /**
     * 👤 Relasi ke user yang membuat card
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    /**
     * 👨‍💻 Relasi ke user yang ditugaskan (developer/designer)
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'card_assignments', 'card_id', 'user_id')
            ->withPivot('assigned_at', 'assignment_status');
    }

    /**
     * 📌 Relasi ke subtasks di dalam card
     */
    public function subTasks(): HasMany
    {
        return $this->hasMany(SubTask::class, 'card_id', 'card_id');
    }

    /**
     * ⏱️ Relasi ke time logs pada card
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class, 'card_id', 'card_id');
    }
}
