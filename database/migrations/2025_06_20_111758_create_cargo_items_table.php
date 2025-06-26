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
        Schema::create('cargo_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rkbm_id');
            $table->string('item_name');
            $table->string('shipper_npwp');
            $table->integer('number_of_hatches');
            $table->string('distribution_system');
            $table->string('bil_number')->nullable();
            $table->integer('number_of_units');
            $table->double('weight_in_tons');
            $table->double('volume_in_cubic_meters');
            $table->integer('number_of_workers');
            $table->string('bahaya')->nullable();
            $table->string('ganggu')->nullable();
            $table->string('kegiatan')->nullable();
            $table->string('consignee')->nullable();
            $table->integer('status_submit')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_items');
    }
};
