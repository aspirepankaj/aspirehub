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
        Schema::create('adspv_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('adspv_roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('adspv_permissions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adspv_role_permissions');
    }
};
