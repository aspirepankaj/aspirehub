<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use App\Modules\CRM\Maintenance\Models\MaintenanceReportPlugin;
use App\Modules\CRM\Maintenance\Models\MaintenanceReportAttachment;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.staff')]
class StaffCreateMaintenanceReport extends Component
{
    use WithFileUploads;

    // 1. Basic Info
    public ?int $client_id = null;
    public ?int $website_id = null;
    public ?int $developer_id = null;
    public string $maintenance_month = '';
    public string $month_select = '';
    public string $year_select = '';
    public string $maintenance_date = '';
    public string $status = 'draft';





    public function updatedMonthSelect($value)
    {
        $this->maintenance_month = $this->year_select . '-' . sprintf('%02d', $value);
    }

    public function updatedYearSelect($value)
    {
        $this->maintenance_month = $value . '-' . sprintf('%02d', $this->month_select);
    }

    // 2. WordPress Info
    public string $wp_version_current = '';
    public string $wp_version_latest = '';
    public bool $wp_updated = false;
    public string $wp_notes = '';

    // 3. PHP
    public string $php_version_current = '';
    public string $php_version_recommended = '';
    public bool $php_updated = false;
    public string $php_notes = '';

    // 4. Theme
    public string $theme_name = '';
    public string $theme_version = '';
    public bool $theme_updated = false;
    public string $theme_notes = '';

    // 5. Plugins
    public array $plugins = [];

    // 6. Security
    public string $security_malware_scan = 'completed';
    public string $security_firewall_status = 'active';
    public string $security_plugin_status = 'active';
    public string $security_ssl_status = 'valid';
    public string $security_notes = '';
    public string $security_health = 'Excellent';

    // 7. Health
    public ?int $health_score = 100;
    public ?int $health_critical_issues = 0;
    public ?int $health_warnings = 0;
    public ?int $health_passed_tests = 0;
    public string $health_notes = '';

    // 8. Performance
    public ?int $performance_desktop = 90;
    public ?int $performance_mobile = 80;
    public string $performance_core_web_vitals = 'passed';
    public string $performance_notes = '';

    // 9. Backup
    public bool $backup_completed = true;
    public string $backup_date = '';
    public string $backup_location = 'Remote Cloud Storage';
    public string $backup_notes = '';

    // 10. Support Summary
    public int $support_tickets_completed = 0;
    public int $support_tickets_pending = 0;
    public string $support_work_summary = '';
    public string $support_time_spent = '';
    public string $support_completion_date = '';

    // 11. Notes
    public string $developer_notes = '';
    public string $client_summary = '';

    // 12. Attachments
    public array $attachments = [];
    public $pastedImages = [];
    public bool $attachments_visible_to_client = false;

    public function mount()
    {
        $this->developer_id = auth()->id();
        $this->maintenance_date = date('Y-m-d');
        $this->month_select = date('m');
        $this->year_select = date('Y');
        $this->maintenance_month = date('Y-m');
        $this->addPluginField();
    }

    public function updatedWebsiteId($value)
    {
        if (!$value) {
            return;
        }

        $latestReport = MaintenanceReport::with('plugins')
            ->where('website_id', $value)
            ->latest('maintenance_date')
            ->first();

        if ($latestReport) {
            $this->wp_version_current = $latestReport->wp_version_current ?? '';
            $this->wp_version_latest = $latestReport->wp_version_latest ?? '';
            $this->wp_updated = $latestReport->wp_updated;
            $this->wp_notes = $latestReport->wp_notes ?? '';

            $this->php_version_current = $latestReport->php_version_current ?? '';
            $this->php_version_recommended = $latestReport->php_version_recommended ?? '';
            $this->php_updated = $latestReport->php_updated;
            $this->php_notes = $latestReport->php_notes ?? '';

            $this->theme_name = $latestReport->theme_name ?? '';
            $this->theme_version = $latestReport->theme_version ?? '';
            $this->theme_updated = $latestReport->theme_updated;
            $this->theme_notes = $latestReport->theme_notes ?? '';

            $this->security_malware_scan = $latestReport->security_malware_scan ?? 'completed';
            $this->security_firewall_status = $latestReport->security_firewall_status ?? 'active';
            $this->security_plugin_status = $latestReport->security_plugin_status ?? 'active';
            $this->security_ssl_status = $latestReport->security_ssl_status ?? 'valid';
            $this->security_notes = $latestReport->security_notes ?? '';
            $this->security_health = $latestReport->security_health ?? 'Excellent';

            $this->health_score = $latestReport->health_score;
            $this->health_critical_issues = $latestReport->health_critical_issues;
            $this->health_warnings = $latestReport->health_warnings;
            $this->health_passed_tests = $latestReport->health_passed_tests;
            $this->health_notes = $latestReport->health_notes ?? '';

            $this->performance_desktop = $latestReport->performance_desktop;
            $this->performance_mobile = $latestReport->performance_mobile;
            $this->performance_core_web_vitals = $latestReport->performance_core_web_vitals ?? 'passed';
            $this->performance_notes = $latestReport->performance_notes ?? '';

            $this->backup_completed = $latestReport->backup_completed;
            $this->backup_location = $latestReport->backup_location ?? 'Remote Cloud Storage';
            $this->backup_notes = $latestReport->backup_notes ?? '';

            $this->plugins = [];
            if ($latestReport->plugins->isNotEmpty()) {
                foreach ($latestReport->plugins as $plugin) {
                    $this->plugins[] = [
                        'plugin_name' => $plugin->plugin_name,
                        'old_version' => $plugin->old_version,
                        'new_version' => $plugin->new_version,
                        'status' => $plugin->status,
                        'notes' => $plugin->notes,
                    ];
                }
            } else {
                $this->addPluginField();
            }
        }
    }

    public function updatedClientId($value)
    {
        $this->resetWebsiteData();
    }

    private function resetWebsiteData()
    {
        $this->website_id = null;
        $this->wp_version_current = '';
        $this->wp_version_latest = '';
        $this->wp_updated = false;
        $this->wp_notes = '';

        $this->php_version_current = '';
        $this->php_version_recommended = '';
        $this->php_updated = false;
        $this->php_notes = '';

        $this->theme_name = '';
        $this->theme_version = '';
        $this->theme_updated = false;
        $this->theme_notes = '';

        $this->security_malware_scan = 'completed';
        $this->security_firewall_status = 'active';
        $this->security_plugin_status = 'active';
        $this->security_ssl_status = 'valid';
        $this->security_notes = '';
        $this->security_health = 'Excellent';

        $this->health_score = 100;
        $this->health_critical_issues = 0;
        $this->health_warnings = 0;
        $this->health_passed_tests = 0;
        $this->health_notes = '';

        $this->performance_desktop = 90;
        $this->performance_mobile = 80;
        $this->performance_core_web_vitals = 'passed';
        $this->performance_notes = '';

        $this->backup_completed = true;
        $this->backup_date = '';
        $this->backup_location = 'Remote Cloud Storage';
        $this->backup_notes = '';

        $this->plugins = [];
        $this->addPluginField();
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:adspv_clients,id',
            'website_id' => 'required|exists:adspv_websites,id',
            'developer_id' => 'required|exists:users,id',
            'maintenance_month' => 'required|string',
            'maintenance_date' => 'required|date',
            'status' => 'required|in:draft,completed',

            'wp_version_current' => 'nullable|string',
            'wp_version_latest' => 'nullable|string',
            'wp_updated' => 'boolean',
            'wp_notes' => 'nullable|string',

            'php_version_current' => 'nullable|string',
            'php_version_recommended' => 'nullable|string',
            'php_updated' => 'boolean',
            'php_notes' => 'nullable|string',

            'theme_name' => 'nullable|string',
            'theme_version' => 'nullable|string',
            'theme_updated' => 'boolean',
            'theme_notes' => 'nullable|string',

            'plugins' => 'nullable|array',
            'plugins.*.plugin_name' => 'required|string',
            'plugins.*.status' => 'required|in:updated,failed,license_required',

            'security_malware_scan' => 'nullable|string',
            'security_firewall_status' => 'nullable|string',
            'security_plugin_status' => 'nullable|string',
            'security_ssl_status' => 'nullable|string',
            'security_notes' => 'nullable|string',
            'security_health' => 'nullable|string',

            'health_score' => 'nullable|integer|min:0|max:100',
            'health_critical_issues' => 'nullable|integer|min:0',
            'health_warnings' => 'nullable|integer|min:0',
            'health_passed_tests' => 'nullable|integer|min:0',
            'health_notes' => 'nullable|string',

            'performance_desktop' => 'nullable|integer|min:0|max:100',
            'performance_mobile' => 'nullable|integer|min:0|max:100',
            'performance_core_web_vitals' => 'nullable|string',
            'performance_notes' => 'nullable|string',

            'backup_completed' => 'boolean',
            'backup_date' => 'nullable|date',
            'backup_location' => 'nullable|string',
            'backup_notes' => 'nullable|string',

            'support_tickets_completed' => 'nullable|integer|min:0',
            'support_tickets_pending' => 'nullable|integer|min:0',
            'support_work_summary' => 'nullable|string',
            'support_time_spent' => 'nullable|string',
            'support_completion_date' => 'nullable|date',

            'developer_notes' => 'nullable|string',
            'client_summary' => 'nullable|string',
            'attachments_visible_to_client' => 'boolean',
        ];
    }

    #[On('media-selected')]
    public function setMedia($path, $field, $name = null, $mime_type = null, $size = null): void
    {
        if ($field === 'attachments') {
            $this->attachments[] = [
                'path' => $path,
                'name' => $name ?? basename($path)
            ];
        }
    }


    public function updatedPastedImages()
    {
        $this->validate([
            'pastedImages.*' => 'image|max:10240', // 10MB max
        ]);

        foreach ($this->pastedImages as $image) {
            $media = \App\Modules\CRM\Media\Models\Media::uploadFile($image);
            if ($media) {
                $this->attachments[] = [
                    'path' => $media->file_path,
                    'name' => $media->file_name
                ];
            }
        }
        $this->pastedImages = [];
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function addPluginField()
    {
        $this->plugins[] = [
            'plugin_name' => '',
            'old_version' => '',
            'new_version' => '',
            'status' => 'updated',
            'notes' => '',
        ];
    }

    public function removePluginField($index)
    {
        unset($this->plugins[$index]);
        $this->plugins = array_values($this->plugins);
    }

    public function saveReport()
    {
        $this->validate();

        DB::transaction(function () {
            $formattedMonth = $this->maintenance_month;
            try {
                if (preg_match('/^\d{4}-\d{2}$/', $this->maintenance_month)) {
                    $formattedMonth = \Carbon\Carbon::parse($this->maintenance_month . '-01')->format('F Y');
                }
            } catch (\Exception $e) {
                // fallback
            }

            $report = MaintenanceReport::create([
                'client_id' => $this->client_id,
                'website_id' => $this->website_id,
                'developer_id' => $this->developer_id,
                'maintenance_month' => $formattedMonth,
                'maintenance_date' => $this->maintenance_date,
                'status' => $this->status,

                'wp_version_current' => $this->wp_version_current,
                'wp_version_latest' => $this->wp_version_latest,
                'wp_updated' => $this->wp_updated,
                'wp_notes' => $this->wp_notes,

                'php_version_current' => $this->php_version_current,
                'php_version_recommended' => $this->php_version_recommended,
                'php_updated' => $this->php_updated,
                'php_notes' => $this->php_notes,

                'theme_name' => $this->theme_name,
                'theme_version' => $this->theme_version,
                'theme_updated' => $this->theme_updated,
                'theme_notes' => $this->theme_notes,

                'security_malware_scan' => $this->security_malware_scan,
                'security_firewall_status' => $this->security_firewall_status,
                'security_plugin_status' => $this->security_plugin_status,
                'security_ssl_status' => $this->security_ssl_status,
                'security_notes' => $this->security_notes,
                'security_health' => $this->security_health,

                'health_score' => $this->health_score ?: 0,
                'health_critical_issues' => $this->health_critical_issues ?: 0,
                'health_warnings' => $this->health_warnings ?: 0,
                'health_passed_tests' => $this->health_passed_tests ?: 0,
                'health_notes' => $this->health_notes,

                'performance_desktop' => $this->performance_desktop ?: 0,
                'performance_mobile' => $this->performance_mobile ?: 0,
                'performance_core_web_vitals' => $this->performance_core_web_vitals,
                'performance_notes' => $this->performance_notes,

                'backup_completed' => $this->backup_completed,
                'backup_date' => $this->backup_date ?: null,
                'backup_location' => $this->backup_location,
                'backup_notes' => $this->backup_notes,

                'support_tickets_completed' => $this->support_tickets_completed ?: 0,
                'support_tickets_pending' => $this->support_tickets_pending ?: 0,
                'support_work_summary' => $this->support_work_summary,
                'support_time_spent' => $this->support_time_spent,
                'support_completion_date' => $this->support_completion_date ?: null,

                'developer_notes' => $this->developer_notes,
                'client_summary' => $this->client_summary,
                'attachments_visible_to_client' => $this->attachments_visible_to_client,
            ]);

            foreach ($this->plugins as $pluginData) {
                if (!empty($pluginData['plugin_name'])) {
                    $report->plugins()->create($pluginData);
                }
            }

            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $report->attachments()->create([
                        'file_path' => $file['path'],
                        'file_name' => $file['name'],
                    ]);
                }
            }
        });

        session()->flash('success', 'Maintenance Report Created Successfully');
        return redirect()->route('staff.maintenance');
    }

    public function toggleAttachmentsVisibility()
    {
        $this->attachments_visible_to_client = !$this->attachments_visible_to_client;
    }

    public function render()
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $clients = Client::with('user')->whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->orderBy('company_name')->get();
        
        $websites = [];
        if ($this->client_id) {
            $websites = Website::where('client_id', $this->client_id)->orderBy('site_name')->get();
        }

        $developers = User::where('id', auth()->id())->get();

        return view('modules.crm.staff.portal.create-maintenance', [
            'clients' => $clients,
            'websites' => $websites,
            'developers' => $developers,
        ])->layoutData(['title' => 'Create Maintenance Report - Staff Portal']);
    }
}
