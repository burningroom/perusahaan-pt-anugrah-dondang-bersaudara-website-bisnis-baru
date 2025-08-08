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
        Schema::create('ports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pkk_id')->nullable();
            $table->string('port_type')->nullable();//
            $table->string('port_code')->nullable();
            $table->string('port_name')->nullable();
            $table->string('port_origin_code')->nullable();
            $table->string('origin_port')->nullable();
            $table->string('load_port_code')->nullable();
            $table->string('load_port_name')->nullable();
            $table->string('destination_port_code')->nullable();
            $table->string('destination_port_name')->nullable();
            $table->string('final_destination_port_code')->nullable();
            $table->string('final_destination_port_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
