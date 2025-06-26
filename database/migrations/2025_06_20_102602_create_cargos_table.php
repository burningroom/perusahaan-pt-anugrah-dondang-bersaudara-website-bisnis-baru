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
        Schema::create('cargos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pkk_id')->nullable();
            $table->integer('mixed_cargo_unload')->nullable();
            $table->integer('mixed_cargo_load')->nullable();
            $table->integer('sack_cargo_unload')->nullable();//
            $table->integer('sack_cargo_load')->nullable();//
            $table->integer('bulk_cargo_unload')->nullable();//
            $table->integer('bulk_cargo_load')->nullable();//
            $table->integer('liquid_cargo_unload')->nullable();
            $table->integer('liquid_cargo_load')->nullable();
            $table->integer('hazardous_cargo_unload')->nullable();//
            $table->integer('hazardous_cargo_load')->nullable();//
            $table->integer('other_cargo_unload')->nullable();//
            $table->integer('other_cargo_load')->nullable();//
            $table->integer('dangerous_good_cargo_unload')->nullable();
            $table->integer('dangerous_good_cargo_load')->nullable();
            $table->integer('unload_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
