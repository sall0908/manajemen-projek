<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Project;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel di database.
     */
    protected $table = 'users';

    /**
     * Primary key tabel users.
     */
    protected $primaryKey = 'user_id';

    /**
     * Primary key bertipe integer dan auto increment.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /**
     * Aktifkan timestamps (created_at & updated_at)
     */
    public $timestamps = true;

    /**
     * Kolom yang bisa diisi mass assignment.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'role',
        'status',
    ];

    /**
     * Kolom yang disembunyikan dari array/json.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi: User sebagai leader project
     * projects.created_by → users.user_id
     */
    public function projectsAsLeader(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by', 'user_id');
    }

    /**
     * Relasi: User sebagai member project
     * project_members.user_id → users.user_id
     */
    public function projectsAsMember(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members', 'user_id', 'project_id')
                    ->withPivot('role', 'joined_at'); // tambahkan kolom pivot yang ada
    }

    public function assignedCards()
    {
        return $this->belongsToMany(Card::class, 'card_assignments', 'user_id', 'card_id')
            ->withPivot('assigned_at', 'assignment_status');
    }

    /**
     * ⏱️ Relasi ke time logs user
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class, 'user_id', 'user_id');
    }
}
