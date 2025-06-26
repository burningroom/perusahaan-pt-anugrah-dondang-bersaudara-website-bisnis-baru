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
        Schema::create('spk_pandus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('pkk_id')->nullable();
            $table->foreignUuid('agent_id')->nullable();
            $table->foreignUuid('company_id')->nullable();//
            $table->string('nomor_pkk')->nullable();
            $table->string('nomor_ppk')->nullable();
            $table->string('nomor_nota')->nullable();
            $table->double('panjang_kapal')->nullable();
            $table->double('lebar_kapal')->nullable();
            $table->string('gt_kapal')->nullable();
            $table->string('no_spk_pandu')->nullable();
            $table->string('petugas_pandu')->nullable();//
            $table->dateTime('waktu_pandu')->nullable();
            $table->integer('kapal_pandu')->nullable();
            $table->integer('kapal_tunda')->nullable();
            $table->string('lokasi_awal')->nullable();
            $table->string('lokasi_akhir')->nullable();
            $table->string('jenis_pandu')->nullable();
            $table->string('keperluan')->nullable();
            $table->dateTime('waktu_gerak')->nullable();
            $table->string('movements_from_to')->nullable();
            $table->time('pilot_on_board')->nullable();
            $table->time('pilotage_finished')->nullable();
            $table->string('tag_boad_code')->nullable();
            $table->string('signature')->nullable();
            $table->string('document')->nullable();
            $table->string('file_ship_service')->nullable();
            $table->string('status')->default('permintaan')->nullable(); //['permintaan', 'proses', 'setuju', 'selesai']
            $table->boolean('is_process_by_finance')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_pandus');
    }
};
