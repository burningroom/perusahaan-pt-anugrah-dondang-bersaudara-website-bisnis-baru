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
        Schema::create('rkbms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pkk_id')->nullable();
            $table->foreignUuid('rpkro_id')->nullable();
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->string('item_name')->nullable()->comment('namaBarang (Nama barang)');
            $table->string('danger')->nullable()->comment('bahaya (“Y” atau “N”)');
            $table->string('bother')->nullable()->comment('ganggu (“Y” atau “N”)');
            $table->string('activity')->nullable()->comment('kegiatan (“BONGKAR” atau “MUAT”)');
            $table->string('kade')->nullable()->comment('Nama Kade');
            $table->integer('work_group')->nullable()->comment('Jumlah Gang (Kelompok Kerja)');
            $table->integer('palka')->nullable()->comment('Palka');
            $table->date('unloading_plan')->nullable()->comment('rencanaBongkar');
            $table->date('loading_plan')->nullable()->comment('rencanamuat');
            $table->date('tanggal_tiba')->nullable();
            $table->date('tanggal_berangkat')->nullable();
            $table->date('tanggal_mulai_muat')->nullable();
            $table->date('tanggal_selesai_muat')->nullable();
            $table->integer('jumlah_gang_kerja_total')->nullable();
            $table->string('dermaga_kode')->nullable();
            $table->string('assignment_file')->nullable();
            $table->integer('amount_of_goods')->nullable();
            $table->integer('amount_of_docs')->nullable();
            $table->integer('status_submit')->nullable();
            $table->integer('total_documents')->nullable();
            $table->string('status')->default('permintaan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkbms');
    }
};
