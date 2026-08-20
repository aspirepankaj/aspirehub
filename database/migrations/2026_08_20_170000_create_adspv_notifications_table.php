<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adspv_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()->constrained('adspv_support_tickets')->onDelete('cascade');
            $table->string('ticket_number')->nullable();
            $table->string('title');
            $table->text('message');
            $table->string('sender_name')->nullable();
            $table->string('sender_type')->default('system'); // client, staff, admin, system
            $table->string('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adspv_notifications');
    }
};
