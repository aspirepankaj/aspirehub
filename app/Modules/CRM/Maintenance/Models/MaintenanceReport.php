<?php

namespace App\Modules\CRM\Maintenance\Models;

use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Core\Activity\Traits\LogsActivity;

class MaintenanceReport extends Model
{
    use LogsActivity;

    protected $table = 'adspv_maintenance_reports';

    protected $fillable = [
        'client_id',
        'website_id',
        'developer_id',
        'maintenance_month',
        'maintenance_date',
        'status',
        
        // WordPress Information
        'wp_version_current',
        'wp_version_latest',
        'wp_updated',
        'wp_notes',

        // PHP
        'php_version_current',
        'php_version_recommended',
        'php_updated',
        'php_notes',

        // Theme
        'theme_name',
        'theme_version',
        'theme_updated',
        'theme_notes',

        // Security
        'security_malware_scan',
        'security_firewall_status',
        'security_plugin_status',
        'security_ssl_status',
        'security_notes',
        'security_health',

        // Website Health
        'health_score',
        'health_critical_issues',
        'health_warnings',
        'health_passed_tests',
        'health_notes',

        // Performance
        'performance_desktop',
        'performance_mobile',
        'performance_core_web_vitals',
        'performance_notes',

        // Backup
        'backup_completed',
        'backup_date',
        'backup_location',
        'backup_notes',

        // Support Summary
        'support_tickets_completed',
        'support_tickets_pending',
        'support_work_summary',
        'support_time_spent',
        'support_completion_date',

        // Notes
        'developer_notes',
        'client_summary',
        'last_sent_at',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'wp_updated' => 'boolean',
        'php_updated' => 'boolean',
        'theme_updated' => 'boolean',
        'backup_completed' => 'boolean',
        'backup_date' => 'date',
        'support_completion_date' => 'date',
        'last_sent_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function plugins(): HasMany
    {
        return $this->hasMany(MaintenanceReportPlugin::class, 'report_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MaintenanceReportAttachment::class, 'report_id');
    }

    public function getActivityDescription(string $event): string
    {
        $siteName = $this->website->site_name ?? 'Website';
        return match ($event) {
            'created' => "Maintenance report generated for '{$siteName}' ({$this->maintenance_month})",
            'updated' => "Maintenance report modified for '{$siteName}' ({$this->maintenance_month})",
            'deleted' => "Maintenance report deleted for '{$siteName}' ({$this->maintenance_month})",
            default   => "Maintenance report event '{$event}' occurred on '{$siteName}'",
        };
    }
}
