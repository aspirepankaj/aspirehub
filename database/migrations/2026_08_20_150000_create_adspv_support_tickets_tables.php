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
        Schema::create('adspv_support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('client_id')->constrained('adspv_clients')->onDelete('cascade');
            $table->foreignId('website_id')->constrained('adspv_websites')->onDelete('cascade');
            $table->string('subject');
            $table->enum('category', ['technical', 'billing', 'maintenance', 'general'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('assigned_staff_id')->nullable()->constrained('adspv_staff')->onDelete('set null');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamps();
        });

        Schema::create('adspv_support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('adspv_support_tickets')->onDelete('cascade');
            $table->enum('sender_type', ['client', 'staff', 'admin'])->default('client');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adspv_support_ticket_messages');
        Schema::dropIfExists('adspv_support_tickets');
    }
};
