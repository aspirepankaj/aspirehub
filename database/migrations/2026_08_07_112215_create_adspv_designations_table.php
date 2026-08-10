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
        Schema::create('adspv_designations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamps();
        });

        // Insert initial designations
        $roles = [
            'Account Manager', 'SEO Specialist', 'Developer', 'Designer',
            'Support Lead', 'Marketing Manager', 'DevOps', 'Content Writer',
            'Sales Executive', 'HR Manager', 'Project Manager', 'Quality Analyst',
        ];

        foreach ($roles as $role) {
            DB::table('adspv_designations')->insert([
                'name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adspv_designations');
    }
};
