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
        Schema::create('adspv_staff_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('adspv_staff')->onDelete('cascade');
            $table->string('phone');
            $table->string('label')->nullable()->default('Work'); // e.g. Work, Mobile, Home
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adspv_staff_phones');
    }
};
