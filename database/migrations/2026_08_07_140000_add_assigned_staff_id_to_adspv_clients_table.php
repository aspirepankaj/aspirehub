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
        Schema::table('adspv_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_staff_id')->nullable()->after('user_id');
            
            $table->foreign('assigned_staff_id')
                ->references('id')
                ->on('adspv_staff')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_clients', function (Blueprint $table) {
            $table->dropForeign(['assigned_staff_id']);
            $table->dropColumn('assigned_staff_id');
        });
    }
};
