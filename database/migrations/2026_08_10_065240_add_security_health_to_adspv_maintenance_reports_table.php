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
        Schema::table('adspv_maintenance_reports', function (Blueprint $table) {
            $table->string('security_health')->nullable()->after('security_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_maintenance_reports', function (Blueprint $table) {
            $table->dropColumn('security_health');
        });
    }
};
