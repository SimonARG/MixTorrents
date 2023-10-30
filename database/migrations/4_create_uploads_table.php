<?php

use App\Models\User;
use App\Models\Subcat;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id('id');
            $table->string('title')->nullable();
            $table->string('name');
            $table->string('filename');
            $table->string('path');
            $table->string('magnet', 500);
            $table->dateTimeTz('created_at');
            $table->dateTimeTz('updated_at')->nullable();
            $table->string('size');
            $table->string('seeders');
            $table->string('leechers');
            $table->string('downloads');
            $table->string('hash');
            $table->string('info', 50)->nullable();
            $table->string('comment', 50)->nullable();
            $table->string('description', 10000)->nullable();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Subcat::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
