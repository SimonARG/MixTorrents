<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function upload(): HasMany
    {
        return $this->hasMany(Upload::class);
    }
}
