<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pkk_id')->nullable();
            $table->integer('load_20_filled')->nullable();
            $table->integer('load_40_filled')->nullable();
            $table->integer('load_20_empty')->nullable();//
            $table->integer('load_40_empty')->nullable();//
            $table->integer('unload_20_filled')->nullable();
            $table->integer('unload_40_filled')->nullable();
            $table->integer('unload_20_empty')->nullable();//
            $table->integer('unload_40_empty')->nullable();//
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
