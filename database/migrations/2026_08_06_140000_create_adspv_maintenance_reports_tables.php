<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adspv_maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('adspv_clients')->onDelete('restrict');
            $table->foreignId('website_id')->constrained('adspv_websites')->onDelete('restrict');
            $table->foreignId('developer_id')->constrained('users')->onDelete('restrict');
            
            // Basic Info
            $table->string('maintenance_month'); // e.g., "August 2026"
            $table->date('maintenance_date');
            $table->string('status')->default('draft'); // 'draft', 'completed'

            // WordPress Information
            $table->string('wp_version_current')->nullable();
            $table->string('wp_version_latest')->nullable();
            $table->boolean('wp_updated')->default(false);
            $table->text('wp_notes')->nullable();

            // PHP
            $table->string('php_version_current')->nullable();
            $table->string('php_version_recommended')->nullable();
            $table->boolean('php_updated')->default(false);
            $table->text('php_notes')->nullable();

            // Theme
            $table->string('theme_name')->nullable();
            $table->string('theme_version')->nullable();
            $table->boolean('theme_updated')->default(false);
            $table->text('theme_notes')->nullable();

            // Security
            $table->string('security_malware_scan')->nullable();
            $table->string('security_firewall_status')->nullable();
            $table->string('security_plugin_status')->nullable();
            $table->string('security_ssl_status')->nullable();
            $table->text('security_notes')->nullable();

            // Website Health
            $table->integer('health_score')->nullable();
            $table->integer('health_critical_issues')->nullable();
            $table->integer('health_warnings')->nullable();
            $table->integer('health_passed_tests')->nullable();
            $table->text('health_notes')->nullable();

            // Performance
            $table->integer('performance_desktop')->nullable();
            $table->integer('performance_mobile')->nullable();
            $table->string('performance_core_web_vitals')->nullable();
            $table->text('performance_notes')->nullable();

            // Backup
            $table->boolean('backup_completed')->default(false);
            $table->date('backup_date')->nullable();
            $table->string('backup_location')->nullable();
            $table->text('backup_notes')->nullable();

            // Support Summary
            $table->integer('support_tickets_completed')->default(0);
            $table->integer('support_tickets_pending')->default(0);
            $table->text('support_work_summary')->nullable();
            $table->string('support_time_spent')->nullable();
            $table->date('support_completion_date')->nullable();

            // Notes
            $table->text('developer_notes')->nullable();
            $table->text('client_summary')->nullable();

            $table->timestamps();
        });

        Schema::create('adspv_maintenance_report_plugins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('adspv_maintenance_reports')->onDelete('cascade');
            $table->string('plugin_name');
            $table->string('old_version')->nullable();
            $table->string('new_version')->nullable();
            $table->string('status'); // 'updated', 'failed', 'license_required'
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('adspv_maintenance_report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('adspv_maintenance_reports')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adspv_maintenance_report_attachments');
        Schema::dropIfExists('adspv_maintenance_report_plugins');
        Schema::dropIfExists('adspv_maintenance_reports');
    }
};
