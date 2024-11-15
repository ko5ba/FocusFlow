<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /**
     * @use HasFactory<\Database\Factories\TaskFactory>
     */
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'priority', 'date_deadline', 'status', 'category_id', 'user_id'];
    protected $table = 'tasks';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
