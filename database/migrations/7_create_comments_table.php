<?php

use App\Models\User;
use App\Models\Upload;
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
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id');
            $table->dateTimeTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
