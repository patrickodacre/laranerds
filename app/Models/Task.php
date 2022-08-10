<?php

namespace App\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $guarded = [];
    protected $dates = [
        'due_date',
    ];

    protected $casts = [
        'is_complete' => 'boolean',
        'team_id' => 'integer',
        'creator_id' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

}

