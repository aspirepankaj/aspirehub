<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adspv_websites', function (Blueprint $table) {
            $table->foreignId('service_type_id')->nullable()->constrained('adspv_service_types')->onDelete('restrict');
        });

        // Map existing site_type strings to the newly created service types IDs
        $typeMappings = [
            'maintenance'        => 'Maintenance',
            'design'             => 'Design',
            'development'        => 'Development',
            'speed_optimisation' => 'Speed Optimisation',
            'other'              => 'Other',
        ];

        foreach ($typeMappings as $siteTypeVal => $typeName) {
            $serviceType = DB::table('adspv_service_types')->where('name', $typeName)->first();
            if ($serviceType) {
                DB::table('adspv_websites')
                    ->where('site_type', $siteTypeVal)
                    ->update(['service_type_id' => $serviceType->id]);
            }
        }

        // For any remaining values or defaults, fallback to "Other"
        $otherType = DB::table('adspv_service_types')->where('name', 'Other')->first();
        if ($otherType) {
            DB::table('adspv_websites')
                ->whereNull('service_type_id')
                ->update(['service_type_id' => $otherType->id]);
        }

        // Alter service_type_id to be NOT NULL now that it is backfilled
        Schema::table('adspv_websites', function (Blueprint $table) {
            $table->unsignedBigInteger('service_type_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('adspv_websites', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']);
            $table->dropColumn('service_type_id');
        });
    }
};
