<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTask extends Model
{
    use HasFactory;

    protected $table = 'sub_tasks';
    protected $primaryKey = 'sub_task_id';
    public $timestamps = true;

    protected $fillable = [
        'card_id',
        'title',
        'description',
        'is_completed',
        'status',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'sub_task_id', 'sub_task_id');
    }
}
