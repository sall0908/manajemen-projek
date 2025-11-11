<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'project_id';
    public $timestamps = true;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'project_name',
        'description',
        'created_by',
        'deadline',
        'status',
        'difficulty',
    ];

    /**
     * Relasi ke user (team leader)
     * projects.created_by → users.user_id
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    /**
     * Relasi ke member (designer/developer)
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class, 'project_id', 'project_id');
    }

    /**
     * Relasi ke card (board)
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'project_id', 'project_id');
    }
}
