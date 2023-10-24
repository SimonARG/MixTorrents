<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Upload;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        Gate::define('messWith-upload', function (User $user, Upload $upload) {
            return $user->id === $upload->user_id;
        });

        Gate::define('messWith-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id;
        });

        Gate::define('messWith-user', function (User $user) {
            return auth()->user()->id === $user->id;
        });

        Gate::define('new-upload', function (User $user) {
            return ( !$user->hasRole('restricted'));
        });

        Gate::define('new-comment', function (User $user) {
            return (! $user->hasRole('restricted'));
        });
    }
}
