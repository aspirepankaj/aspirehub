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
        Schema::table('adspv_staff_phones', function (Blueprint $table) {
            // Drop the wrong foreign key (was pointing to adspv_clients)
            $table->dropForeign(['staff_id']);
        });

        Schema::table('adspv_staff_phones', function (Blueprint $table) {
            // Add the correct foreign key pointing to adspv_staff
            $table->foreign('staff_id')
                  ->references('id')->on('adspv_staff')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_staff_phones', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::table('adspv_staff_phones', function (Blueprint $table) {
            $table->foreign('staff_id')
                  ->references('id')->on('adspv_clients')
                  ->onDelete('cascade');
        });
    }
};
