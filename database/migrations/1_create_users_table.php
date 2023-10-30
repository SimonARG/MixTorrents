<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('password');
            $table->string('email');
            $table->string('pic')->nullable();
            $table->text('about');
            $table->dateTimeTz('created_at');
            $table->dateTimeTz('updated_at')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->boolean('trust')->nullable()->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};