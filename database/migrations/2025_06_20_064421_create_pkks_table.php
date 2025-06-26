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
        Schema::create('pkks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('agent_id')->nullable();
            $table->foreignUuid('user_id')->nullable();
            $table->nullableUuidMorphs('requestable');
            $table->foreignUuid('company_id')->nullable();
            $table->string('pkk_number');
            $table->string('route_type')->nullable();
            $table->string('route_number')->nullable();
            $table->string('bm_status')->nullable();
            $table->string('goods_type')->nullable();//
            $table->integer('total_unload')->nullable();
            $table->integer('total_load')->nullable();
            $table->string('port_code')->nullable();
            $table->string('item_type')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('tanggalEta')->nullable();
            $table->dateTime('tanggalEtd')->nullable();
            $table->string('pmku_pandu_number')->nullable()->comment('noPmkuPandu (No PMKU Pandu Setempat)');
            $table->string('npwp_pandu_number')->nullable()->comment('noNpwpPandu (No NPWP Pandu Setempat)');
            $table->string('pandu_name')->nullable()->comment('namaPandu (Nama Pandu Setempat)');
            $table->string('mmsi')->nullable()->comment('mmsi (Nomor MMSI)');
            $table->string('status_window')->nullable()->comment('statusWindow (Status Window)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkks');
    }
};
