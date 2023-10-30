<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    
    public $timestamps = false;
    protected $guarded = [];

    public function uploads(): HasMany 
    {
        return $this->hasMany(Upload::class);
    }

    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class);
    }

    public function roles(): BelongsToMany 
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role_name) 
    {
        foreach ($this->roles as $role) {
            if ($role->role == $role_name)
                return true;
            }
        return false;
    }

    public function getRole() 
    {
        foreach ($this->roles as $role) {
            $role = $role->role;
            return $role;
        }
    }

    public function getTrust() 
    {
        if ($this->trust === null) {
            return '';
        } elseif ($this->trust === 0) {
            return 'untrusted';
        } elseif ($this->trust === 1) {
            return 'trusted';
        }
    }
}