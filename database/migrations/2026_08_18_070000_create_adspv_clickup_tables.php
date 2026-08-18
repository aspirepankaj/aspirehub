<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adspv_clickup_spaces', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('color')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('adspv_clickup_folders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('clickup_space_id');
            $table->string('name');
            $table->integer('task_count')->default(0);
            $table->boolean('archived')->default(false);
            
            $table->foreignId('client_id')->nullable()->constrained('adspv_clients')->nullOnDelete();
            $table->foreignId('website_id')->nullable()->constrained('adspv_websites')->nullOnDelete();
            
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->foreign('clickup_space_id')->references('id')->on('adspv_clickup_spaces')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adspv_clickup_folders');
        Schema::dropIfExists('adspv_clickup_spaces');
    }
};
