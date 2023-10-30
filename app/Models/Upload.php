<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function subcat(): BelongsTo
    {
        return $this->belongsTo(Subcat::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}