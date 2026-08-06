<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adspv_website_service_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('adspv_websites')->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained('adspv_service_types')->onDelete('cascade');
            $table->timestamps();
        });

        // Copy existing data from adspv_websites to the pivot table
        $websites = DB::table('adspv_websites')->select('id', 'service_type_id')->whereNotNull('service_type_id')->get();
        foreach ($websites as $web) {
            DB::table('adspv_website_service_type')->insert([
                'website_id' => $web->id,
                'service_type_id' => $web->service_type_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the service_type_id column from adspv_websites
        Schema::table('adspv_websites', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']);
            $table->dropColumn('service_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('adspv_websites', function (Blueprint $table) {
            $table->unsignedBigInteger('service_type_id')->nullable();
        });

        // Copy back one relationship if available in the pivot
        $pivots = DB::table('adspv_website_service_type')->get()->groupBy('website_id');
        foreach ($pivots as $websiteId => $records) {
            DB::table('adspv_websites')
                ->where('id', $websiteId)
                ->update(['service_type_id' => $records->first()->service_type_id]);
        }

        // Re-add foreign key constraint
        Schema::table('adspv_websites', function (Blueprint $table) {
            $table->foreign('service_type_id')->references('id')->on('adspv_service_types')->onDelete('restrict');
        });

        Schema::dropIfExists('adspv_website_service_type');
    }
};
