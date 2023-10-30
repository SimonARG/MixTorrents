<?php

use App\Models\User;
use App\Models\Upload;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id');
            $table->dateTimeTz('created_at');
            $table->dateTimeTz('upated_at')->nullable();
            $table->mediumText('comment');
            $table->foreignIdFor(Upload::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->foreignIdFor(User::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
