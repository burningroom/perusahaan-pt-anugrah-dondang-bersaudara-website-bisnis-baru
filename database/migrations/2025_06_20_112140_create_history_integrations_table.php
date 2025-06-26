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
        Schema::create('history_integrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id');
            $table->foreignUuid('created_by_id')->nullable();
            $table->string('type');
            $table->string('document_number')->nullable();
            $table->string('status');
            $table->text('description');
            $table->boolean('is_read')->default(0)->nullable();
            $table->boolean('is_deleted')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_integrations');
    }
};
