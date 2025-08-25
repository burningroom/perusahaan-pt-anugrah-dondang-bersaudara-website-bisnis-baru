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
        Schema::create('soap_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('path');
            $table->string('method', 10);
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('soap_action')->nullable();
            $table->string('operation')->nullable(); // e.g. entryPKK, SetSpkPandu
            $table->boolean('is_wsdl')->default(false);
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->integer('took_ms')->nullable();
            $table->json('headers')->nullable();
            $table->longText('request_xml')->nullable();
            $table->longText('response_xml')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soap_logs');
    }
};
