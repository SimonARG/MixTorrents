<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('password');
            $table->string('email');
            $table->string('pic')->nullable();
            $table->dateTimeTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTimeTz('updated_at')->nullable();
            $table->rememberToken();
            $table->text('about');
            $table->softDeletes();
            $table->boolean('trust')->nullable()->default;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};