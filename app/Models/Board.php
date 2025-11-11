<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'board_id';
    public $timestamps = true;

    protected $fillable = ['board_name', 'project_id', 'position'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function cards()
    {
        return $this->hasMany(Card::class, 'board_id', 'board_id');
    }
}