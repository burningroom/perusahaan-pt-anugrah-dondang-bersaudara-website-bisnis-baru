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
        Schema::create('rpkros', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pkk_id')->nullable();
            $table->foreignUuid('ppk_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('user_id')->nullable();
            $table->string('ppk_number')->nullable();
            $table->string('rpkro_number');
            $table->string('rpkro_type');
            $table->dateTime('plan_time');
            $table->string('destination_port_name');
            $table->string('status')->default('permintaan')->nullable(); //['permintaan', 'proses', 'setuju']
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpkros');
    }
};
