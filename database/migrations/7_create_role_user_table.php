<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignIdFor(User::class)
            ->constrained()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            $table->foreignIdFor(Role::class)
            ->constrained()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('role_user');
    }
};
