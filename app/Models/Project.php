<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['user_id', 'name', 'color', 'order', 'is_default'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function todoTasks()
    {
        return $this->hasMany(Task::class)->where('status', 'todo');
    }

    public function doneTasks()
    {
        return $this->hasMany(Task::class)->where('status', 'done');
    }
}
