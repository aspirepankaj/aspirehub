<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adspv_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->default('indigo');
            $table->timestamps();
        });

        // Seed default plans
        $defaultPlans = [
            ['name' => 'Starter', 'color' => 'emerald', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Growth', 'color' => 'indigo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Enterprise', 'color' => 'pink', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('adspv_plans')->insert($defaultPlans);

        Schema::create('adspv_client_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('adspv_clients')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('adspv_plans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adspv_client_plan');
        Schema::dropIfExists('adspv_plans');
    }
};
