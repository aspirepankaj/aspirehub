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
        Schema::create('adspv_website_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('adspv_websites')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('adspv_plans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adspv_website_plan');
    }
};
