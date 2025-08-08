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
        Schema::create('ships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pkk_id')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('name')->nullable();
            $table->string('captain_name')->nullable();
            $table->integer('drt')->nullable();
            $table->integer('grt')->nullable();
            $table->double('loa')->nullable();
            $table->string('ship_type')->nullable();
            $table->integer('year_build')->nullable();
            $table->double('width')->nullable();
            $table->double('max_draft')->nullable();
            $table->double('front_draft')->nullable();
            $table->double('rear_draft')->nullable();
            $table->double('midship_draft')->nullable();
            $table->string('call_sign')->nullable();
            $table->string('flag')->nullable();
            $table->string('imo_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ships');
    }
};
