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
        Schema::create('agents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();
            $table->string('nama_kapal')->nullable();//
            $table->string('nomor_pkk')->nullable();//
            $table->dateTime('waktu_penggolongan')->nullable();//
            $table->text('keterangan')->nullable();//
            $table->text('lokasi_awal')->nullable();//
            $table->text('lokasi_akhir')->nullable();//
            $table->string('nama_perusahaan')->nullable();//
            $table->string('npwp')->nullable();//
            $table->string('tanda_pendaftaran_kapal')->nullable();//
            $table->string('nahkoda')->nullable();//
            $table->integer('drt')->nullable();//
            $table->integer('grt')->nullable();//
            $table->integer('loa')->nullable();//
            $table->string('jenis_kapal')->nullable();//
            $table->year('tahun_pembuatan')->nullable();//
            $table->integer('lebar_kapal')->nullable();//
            $table->integer('draft_max')->nullable();//
            $table->integer('draft_depan')->nullable();//
            $table->integer('draft_belakang')->nullable();//
            $table->integer('draft_tengah')->nullable();//
            $table->string('jenis_trayek')->nullable();//
            $table->string('bendera')->nullable();//
            $table->string('call_sign')->nullable();//
            $table->string('imo_number')->nullable();//
            $table->dateTime('tanggal_eta')->nullable();//
            $table->dateTime('tanggal_etd')->nullable();//
            $table->string('kode_pelabuhan_asal')->nullable();//
            $table->string('pelabuhan_asal')->nullable();//
            $table->string('kode_pelabuhan_tujuan')->nullable();//
            $table->string('pelabuhan_tujuan')->nullable();//
            $table->string('dermaga_nama')->nullable();
            $table->string('status_bm')->nullable();
            $table->integer('penumpang_naik_turun_bongkar')->nullable();
            $table->integer('penumpang_naik_turun_muat')->nullable();
            $table->string('jenis_barang')->nullable();
            $table->integer('container_bongkar_isi_40')->nullable();
            $table->integer('container_bongkar_isi_20')->nullable();
            $table->integer('container_bongkar_isi_40_empty')->nullable();
            $table->integer('container_bongkar_isi_20_empty')->nullable();
            $table->integer('container_muat_isi_40')->nullable();
            $table->integer('container_muat_isi_20')->nullable();
            $table->integer('container_muat_isi_40_empty')->nullable();
            $table->integer('container_muat_isi_20_empty')->nullable();
            $table->integer('cargo_barang_campur_bongkar')->nullable();
            $table->integer('cargo_barang_campur_muat')->nullable();
            $table->integer('cargo_barang_karung_bongkar')->nullable();
            $table->integer('cargo_barang_karung_muat')->nullable();
            $table->integer('cargo_barang_curah_bongkar')->nullable();
            $table->integer('cargo_barang_curah_muat')->nullable();
            $table->integer('cargo_barang_cair_bongkar')->nullable();
            $table->integer('cargo_barang_cair_muat')->nullable();
            $table->integer('cargo_barang_berbahaya_bongkar')->nullable();
            $table->integer('cargo_barang_berbahaya_muat')->nullable();
            $table->string('jenis_barang_lain')->nullable();
            $table->integer('jenis_barang_lain_bongkar')->nullable();
            $table->integer('jenis_barang_lain_muat')->nullable();
            $table->integer('jumlah_bongkar')->nullable();
            $table->integer('jumlah_muat')->nullable();
            $table->integer('hewan_naik_turun_bongkar')->nullable();
            $table->integer('hewan_naik_turun_muat')->nullable();
            $table->string('port_code')->nullable();
            $table->string('status')->default('terkirim')->nullable(); //['permintaan', 'proses', 'berhasil']
            $table->string('kode_pelabuhan_muat')->nullable();
            $table->string('pelabuhan_muat')->nullable();
            $table->string('kode_tujuan_akhir_pelabuhan')->nullable();
            $table->string('pelabuhan_tujuan_akhir')->nullable();
            $table->string('nomor_trayek')->nullable();
            $table->string('noinmarsat')->nullable();
            $table->string('npwp_principal')->nullable();
            $table->string('nama_principal')->nullable();
            $table->string('negara_principal')->nullable();
            $table->string('status_but')->nullable();
            $table->string('no_pmku_pandu')->nullable();
            $table->string('no_npwp_pandu')->nullable();
            $table->string('nama_pandu')->nullable();
            $table->string('kode_dermaga')->nullable();
            $table->string('mmsi')->nullable();
            $table->string('status_window')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
