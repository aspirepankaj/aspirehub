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
        Schema::table('adspv_maintenance_report_attachments', function (Blueprint $table) {
            $table->boolean('is_visible_to_client')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_maintenance_report_attachments', function (Blueprint $table) {
            $table->dropColumn('is_visible_to_client');
        });
    }
};
