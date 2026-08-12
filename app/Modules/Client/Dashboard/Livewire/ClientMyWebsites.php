<?php
namespace App\Modules\Client\Dashboard\Livewire;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
#[Layout('layouts.client-portal')]
class ClientMyWebsites extends Component
{


    public function render()
    {
        // Current Login User
        $userId = Auth::id();

        // Client Record
        $client = DB::table('adspv_clients')
            ->where('user_id', $userId)
            ->first();

        abort_if(!$client, 404, 'Client not found.');

        // Get assigned staff name
        $assignedStaffName = null;
        $staffUser = DB::table('adspv_client_staff as cs')
            ->join('adspv_staff as s', 's.id', '=', 'cs.staff_id')
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->where('cs.client_id', $client->id)
            ->select('u.name')
            ->first();
        if ($staffUser) {
            $assignedStaffName = $staffUser->name;
        }

        // Websites + Latest Maintenance Report
        $websites = DB::table('adspv_websites as w')
            ->leftJoin('adspv_maintenance_reports as mr', function ($join) {
                $join->on('mr.website_id', '=', 'w.id')
                    ->whereRaw('mr.id = (
                            SELECT MAX(id)
                            FROM adspv_maintenance_reports
                            WHERE website_id = w.id
                    )');
            })
            ->leftJoin('adspv_staff as s', 's.id', '=', 'mr.developer_id')
            ->where('w.client_id', $client->id)
            ->select(

                // Website
                'w.*',

                // Maintenance
                'mr.id as report_id',
                'mr.maintenance_month',
                'mr.maintenance_date',
                'mr.status as maintenance_status',

                // WordPress
                'mr.wp_version_current',
                'mr.wp_version_latest',
                'mr.wp_updated',
                'mr.wp_notes',

                // PHP
                'mr.php_version_current',
                'mr.php_version_recommended',
                'mr.php_updated',
                'mr.php_notes',

                // Theme
                'mr.theme_name',
                'mr.theme_version',
                'mr.theme_updated',
                'mr.theme_notes',

                // Security
                'mr.security_malware_scan',
                'mr.security_firewall_status',
                'mr.security_plugin_status',
                'mr.security_health',
                'mr.security_notes',

                // Health
                'mr.health_score',
                'mr.health_critical_issues',
                'mr.health_warnings',
                'mr.health_passed_tests',

                // Performance
                'mr.performance_desktop',
                'mr.performance_mobile',
                'mr.performance_core_web_vitals',
                'mr.performance_notes',

                // Backup
                'mr.backup_completed',
                'mr.backup_date',
                'mr.backup_location',

                // Support
                'mr.support_tickets_completed',
                'mr.support_tickets_pending',
                'mr.support_work_summary',
                'mr.support_time_spent',

                // Developer
                'mr.developer_id',
                'mr.developer_notes',
                's.company_name as developer_name',
            )
            ->orderBy('w.site_name')
            ->get();

        return view('modules.client.dashboard.client-my-websites', [
            'client'            => $client,
            'websites'          => $websites,
            'assignedStaffName' => $assignedStaffName,
        ]);
    }
}