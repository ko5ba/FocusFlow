<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory,SoftDeletes;

    protected $guarded = false;
    protected $table = 'tags';

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }
}
