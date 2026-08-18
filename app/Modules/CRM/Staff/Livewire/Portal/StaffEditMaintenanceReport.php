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
class StaffEditMaintenanceReport extends Component
{
    use WithFileUploads;

    public int $reportId;

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
    public string $security_malware_scan = '';
    public string $security_firewall_status = '';
    public string $security_plugin_status = '';
    public string $security_ssl_status = '';
    public string $security_notes = '';
    public string $security_health = 'Excellent';

    // 7. Health
    public ?int $health_score = null;
    public ?int $health_critical_issues = null;
    public ?int $health_warnings = null;
    public ?int $health_passed_tests = null;
    public string $health_notes = '';

    // 8. Performance
    public ?int $performance_desktop = null;
    public ?int $performance_mobile = null;
    public string $performance_core_web_vitals = '';
    public string $performance_notes = '';

    // 9. Backup
    public bool $backup_completed = false;
    public string $backup_date = '';
    public string $backup_location = '';
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
    public array $existingAttachments = [];
    public array $newAttachments = [];
    public bool $attachments_visible_to_client = false;

    public function mount(int $id)
    {
        $staffId = auth()->user()->staff->id ?? 0;
        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        })->pluck('id')->toArray();

        $report = MaintenanceReport::with(['plugins', 'attachments'])
            ->whereIn('client_id', $assignedClientIds)
            ->findOrFail($id);

        $this->reportId = $report->id;
        $this->client_id = $report->client_id;
        $this->website_id = $report->website_id;
        $this->developer_id = $report->developer_id;
        $this->attachments_visible_to_client = (bool) $report->attachments_visible_to_client;
        try {
            $parsedDate = \Carbon\Carbon::parse($report->maintenance_month);
            $this->maintenance_month = $parsedDate->format('Y-m');
            $this->month_select = $parsedDate->format('m');
            $this->year_select = $parsedDate->format('Y');
        } catch (\Exception $e) {
            $this->maintenance_month = '';
            $this->month_select = date('m');
            $this->year_select = date('Y');
        }
        $this->maintenance_date = $report->maintenance_date ? $report->maintenance_date->format('Y-m-d') : '';
        $this->status = $report->status;

        $this->wp_version_current = $report->wp_version_current ?? '';
        $this->wp_version_latest = $report->wp_version_latest ?? '';
        $this->wp_updated = $report->wp_updated;
        $this->wp_notes = $report->wp_notes ?? '';

        $this->php_version_current = $report->php_version_current ?? '';
        $this->php_version_recommended = $report->php_version_recommended ?? '';
        $this->php_updated = $report->php_updated;
        $this->php_notes = $report->php_notes ?? '';

        $this->theme_name = $report->theme_name ?? '';
        $this->theme_version = $report->theme_version ?? '';
        $this->theme_updated = $report->theme_updated;
        $this->theme_notes = $report->theme_notes ?? '';

        $this->security_malware_scan = $report->security_malware_scan ?? 'completed';
        $this->security_firewall_status = $report->security_firewall_status ?? 'active';
        $this->security_plugin_status = $report->security_plugin_status ?? 'active';
        $this->security_ssl_status = $report->security_ssl_status ?? 'valid';
        $this->security_notes = $report->security_notes ?? '';
        $this->security_health = $report->security_health ?? 'Excellent';

        $this->health_score = $report->health_score;
        $this->health_critical_issues = $report->health_critical_issues;
        $this->health_warnings = $report->health_warnings;
        $this->health_passed_tests = $report->health_passed_tests;
        $this->health_notes = $report->health_notes ?? '';

        $this->performance_desktop = $report->performance_desktop;
        $this->performance_mobile = $report->performance_mobile;
        $this->performance_core_web_vitals = $report->performance_core_web_vitals ?? 'passed';
        $this->performance_notes = $report->performance_notes ?? '';

        $this->backup_completed = $report->backup_completed;
        $this->backup_date = $report->backup_date ? $report->backup_date->format('Y-m-d') : '';
        $this->backup_location = $report->backup_location ?? '';
        $this->backup_notes = $report->backup_notes ?? '';

        $this->support_tickets_completed = $report->support_tickets_completed;
        $this->support_tickets_pending = $report->support_tickets_pending;
        $this->support_work_summary = $report->support_work_summary ?? '';
        $this->support_time_spent = $report->support_time_spent ?? '';
        $this->support_completion_date = $report->support_completion_date ? $report->support_completion_date->format('Y-m-d') : '';

        $this->developer_notes = $report->developer_notes ?? '';
        $this->client_summary = $report->client_summary ?? '';
        $this->existingAttachments = $report->attachments->toArray();

        foreach ($report->plugins as $pl) {
            $this->plugins[] = [
                'plugin_name' => $pl->plugin_name,
                'old_version' => $pl->old_version,
                'new_version' => $pl->new_version,
                'status' => $pl->status,
                'notes' => $pl->notes,
            ];
        }

        if (empty($this->plugins)) {
            $this->addPluginField();
        }
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
            'newAttachments' => 'nullable|array',
        ];
    }

    #[On('media-selected')]
    public function setMedia($path, $field, $name = null, $mime_type = null, $size = null): void
    {
        if ($field === 'attachments') {
            $this->newAttachments[] = [
                'path' => $path,
                'name' => $name ?? basename($path)
            ];
        }
    }

    public function removeNewAttachment($index)
    {
        unset($this->newAttachments[$index]);
        $this->newAttachments = array_values($this->newAttachments);
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

    public function deleteAttachment(int $id)
    {
        $attachment = MaintenanceReportAttachment::findOrFail($id);
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        $this->existingAttachments = array_filter($this->existingAttachments, fn($item) => $item['id'] !== $id);
    }

    public function updateReport()
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

            $report = MaintenanceReport::findOrFail($this->reportId);
            $report->update([
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

            $report->plugins()->delete();
            foreach ($this->plugins as $pluginData) {
                if (!empty($pluginData['plugin_name'])) {
                    $report->plugins()->create($pluginData);
                }
            }

            // Save new attachments
            if (!empty($this->newAttachments)) {
                foreach ($this->newAttachments as $file) {
                    $report->attachments()->create([
                        'file_path' => $file['path'],
                        'file_name' => $file['name'],
                    ]);
                }
            }
        });

        session()->flash('success', 'Maintenance Report Updated Successfully');
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

        return view('modules.crm.staff.portal.edit-maintenance', [
            'clients' => $clients,
            'websites' => $websites,
            'developers' => $developers,
        ])->layoutData(['title' => 'Edit Maintenance Report - Staff Portal']);
    }
}
