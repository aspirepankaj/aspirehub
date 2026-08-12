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
        Schema::table('adspv_plans', function (Blueprint $table) {
            $table->string('duration')->default('monthly')->after('color'); // monthly, quarterly, yearly, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_plans', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
