<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'project_comments';
    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'card_id',
        'user_id',
        'comment_text',
        'comment_type',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }
}
