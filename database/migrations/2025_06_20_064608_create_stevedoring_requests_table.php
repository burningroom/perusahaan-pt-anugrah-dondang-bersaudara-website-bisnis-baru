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
        Schema::create('stevedoring_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();
            $table->string('type')->nullable(); // bongkar, muat, bongkar-muat
            $table->string('bg_name')->nullable();
            $table->string('pkk_number')->nullable();
            $table->integer('stevedoring_plan')->nullable()->comment('Rencana muat dengan satuan Metrik Ton');
            $table->dateTime('implementation')->nullable();
            $table->string('status')->default('permintaan')->nullable(); //['permintaan', 'proses', 'setuju']
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stevedoring_requests');
    }
};
