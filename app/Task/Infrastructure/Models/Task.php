<?php

namespace App\Task\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
