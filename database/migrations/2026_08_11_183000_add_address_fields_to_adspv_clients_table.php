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
            $table->text('address')->nullable()->after('notes');
            $table->string('landmark')->nullable()->after('address');
            $table->string('state')->nullable()->after('landmark');
            $table->string('country')->nullable()->after('state');
            $table->string('region')->nullable()->after('country');
            $table->string('zip_code')->nullable()->after('region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_clients', function (Blueprint $table) {
            $table->dropColumn(['address', 'landmark', 'state', 'country', 'region', 'zip_code']);
        });
    }
};
