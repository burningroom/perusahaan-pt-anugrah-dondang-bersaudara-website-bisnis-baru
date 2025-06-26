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
        Schema::create('request_arrivals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();
            $table->string('nama_kapal')->nullable();//
            $table->string('nomor_pkk')->nullable();//
            $table->string('vessel_tb')->nullable();//
            $table->string('vessel_bg')->nullable();//
            $table->string('rkbm_loading_number')->nullable();//
            $table->string('rkbm_unloading_number')->nullable();//
            $table->string('loading_type')->nullable()->comment('Di isi dengan Komoditi');//
            $table->string('loading')->nullable();//
            $table->dateTime('waktu_pengolongan')->nullable();//
            $table->string('jenis_pengolongan')->nullable();// masuk, keluar
            $table->text('lokasi_awal')->nullable();//
            $table->text('lokasi_akhir')->nullable();//
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
        Schema::dropIfExists('request_arrivals');
    }
};
