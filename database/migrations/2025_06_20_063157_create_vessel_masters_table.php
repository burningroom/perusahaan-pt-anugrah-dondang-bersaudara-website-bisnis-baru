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
        Schema::create('vessel_masters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->string('registration_sign')->nullable()->comment('Tanda Pendaftaran Kapal');
            $table->string('name')->nullable()->comment('Nama Kapal');
            $table->string('code')->nullable()->comment('Kode Kapal');
            $table->enum('type', ['TB', 'BG', 'LCT'])->comment('Tipe kapal (TB/BG/LCT)');
            $table->integer('drt')->nullable();
            $table->integer('grt')->nullable();
            $table->double('loa')->nullable();
            $table->string('kind')->nullable()->comment('Jenis Kapal');
            $table->double('width')->nullable()->comment('Lebar Kapal');
            $table->double('max_draft')->nullable();
            $table->double('front_draft')->nullable();
            $table->double('back_draft')->nullable();
            $table->double('central_draft')->nullable();
            $table->string('route_type')->nullable()->comment('Jenis Trayek');
            $table->string('flag')->nullable()->comment('Bendera');
            $table->string('call_sign')->nullable();
            $table->string('imo_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessel_masters');
    }
};
