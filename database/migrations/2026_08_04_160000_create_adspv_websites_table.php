<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adspv_websites', function (Blueprint $table) {
            $table->id();

            // Client association
            $table->foreignId('client_id')
                ->constrained('adspv_clients')
                ->cascadeOnDelete();

            // Basic site details
            $table->string('site_name');
            $table->string('url');
            $table->enum('site_type', [
                'maintenance',
                'design',
                'development',
                'speed_optimisation',
                'other',
            ])->default('maintenance');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');

            // CMS / Admin login credentials (password stored encrypted)
            $table->string('admin_url')->nullable();
            $table->string('admin_username')->nullable();
            $table->text('admin_password')->nullable(); // stored encrypted via Crypt

            // Hosting details
            $table->string('hosting_provider')->nullable();
            $table->string('server_ip', 100)->nullable();

            // Notes
            $table->text('notes')->nullable();

            // Audit columns
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adspv_websites');
    }
};
