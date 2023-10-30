<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function upload(): BelongsTo
    {
        return $this->belongsTo(Upload::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}