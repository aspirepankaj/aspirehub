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
        Schema::table('adspv_support_ticket_messages', function (Blueprint $table) {
            $table->boolean('is_read_by_client')->default(true)->after('attachments');
            $table->boolean('is_read_by_staff')->default(true)->after('is_read_by_client');
            $table->boolean('is_read_by_admin')->default(true)->after('is_read_by_staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_support_ticket_messages', function (Blueprint $table) {
            $table->dropColumn(['is_read_by_client', 'is_read_by_staff', 'is_read_by_admin']);
        });
    }
};
