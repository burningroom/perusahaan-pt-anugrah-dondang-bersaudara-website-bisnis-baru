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
        Schema::create('rpkro_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rpkro_id');
            $table->string('rkbm_unloading_number')->nullable();
            $table->string('rkbm_loading_number')->nullable();
            $table->string('ppkb_number');
            $table->string('komoditi')->nullable();
            $table->double('unloading')->nullable();
            $table->double('loading')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('finish_time');
            $table->double('initial_meter_code')->nullable();
            $table->double('final_meter_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpkro_details');
    }
};
