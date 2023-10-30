<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcats', function (Blueprint $table) {
            $table->id('id');
            $table->string('subcat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcats');
    }
};
