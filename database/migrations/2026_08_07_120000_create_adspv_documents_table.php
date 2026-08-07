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
        Schema::create('adspv_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('adspv_clients')->onDelete('cascade');
            $table->foreignId('website_id')->nullable()->constrained('adspv_websites')->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adspv_documents');
    }
};
