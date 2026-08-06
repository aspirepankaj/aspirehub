<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adspv_service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->default('indigo'); // Tailwind color name like emerald, pink, indigo, etc.
            $table->timestamps();
        });

        // Seed initial values to match current site types
        $defaultTypes = [
            ['name' => 'Maintenance', 'color' => 'emerald', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Design', 'color' => 'pink', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Development', 'color' => 'indigo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Speed Optimisation', 'color' => 'amber', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'color' => 'slate', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('adspv_service_types')->insert($defaultTypes);
    }

    public function down(): void
    {
        Schema::dropIfExists('adspv_service_types');
    }
};
