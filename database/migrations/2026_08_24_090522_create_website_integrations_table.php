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
        Schema::create('adspv_website_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('adspv_websites')->onDelete('cascade');
            $table->string('integration_type');
            $table->string('status')->default('disconnected');
            $table->longText('api_credentials')->nullable();
            $table->longText('auth_credentials')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();

            $table->unique(['website_id', 'integration_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adspv_website_integrations');
    }
};
