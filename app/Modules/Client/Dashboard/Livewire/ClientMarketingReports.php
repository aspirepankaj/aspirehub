<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[Layout('layouts.client-portal')]
class ClientMarketingReports extends Component
{
    public ?int $selectedWebsiteId = null;
    public string $activeReportIntegrationId = 'overview';
    public string $selectedMonth = '';
    
    // Loaded reports
    public array $activeReportData = [];
    public array $ga4Data = [];
    public array $gscData = [];
    
    // Dropdown list holders
    public $websites = [];
    public $availableMonths = [];
    public $integrations = [];

    public function mount()
    {
        $client = Client::where('user_id', Auth::id())->first();
        if (!$client) {
            return;
        }

        $this->websites = Website::where('client_id', $client->id)
            ->orderBy('site_name')
            ->get();

        if ($this->websites->isNotEmpty()) {
            $this->selectedWebsiteId = $this->websites->first()->id;
            $this->loadIntegrationsAndMonths();
        }
    }

    public function updatedSelectedWebsiteId()
    {
        $this->loadIntegrationsAndMonths();
    }

    public function updatedActiveReportIntegrationId()
    {
        $this->loadMonthsForActiveIntegration();
    }

    public function updatedSelectedMonth()
    {
        $this->loadReportData();
    }

    protected function loadIntegrationsAndMonths()
    {
        if (!$this->selectedWebsiteId) {
            return;
        }

        $this->integrations = WebsiteIntegration::where('website_id', $this->selectedWebsiteId)
            ->get()
            ->keyBy('integration_type')
            ->toArray();

        // Overview is default. If no overview or not selected, fallback.
        $this->activeReportIntegrationId = 'overview';

        $this->loadMonthsForActiveIntegration();
    }

    protected function loadMonthsForActiveIntegration()
    {
        $this->availableMonths = $this->getAvailableReportMonths();
        
        if (!empty($this->availableMonths)) {
            $this->selectedMonth = $this->availableMonths[0]['value'];
        } else {
            $this->selectedMonth = date('Y') . '-' . Str::lower(date('F'));
        }

        $this->loadReportData();
    }

    public function loadReportData(): void
    {
        $this->activeReportData = [];
        $this->ga4Data = [];
        $this->gscData = [];

        if (!$this->selectedWebsiteId || !$this->selectedMonth) {
            return;
        }

        // Split "2026-august"
        $parts = explode('-', $this->selectedMonth);
        $year = $parts[0] ?? date('Y');
        $month = $parts[1] ?? Str::lower(date('F'));

        try {
            $clientDetails = Client::where('user_id', Auth::id())->first();
            $website = Website::findOrFail($this->selectedWebsiteId);

            if (!$clientDetails || !$website) {
                return;
            }

            $userName = Str::slug(Str::lower($clientDetails->user->name ?? 'client'));
            $emailParts = explode('@', $clientDetails->user->email ?? '');
            $emailPrefix = Str::slug(Str::lower($emailParts[0] ?? ''));
            $clientFolder = "{$userName}-{$emailPrefix}";

            $websiteFolder = Str::slug(Str::lower($website->site_name));
            if (empty($websiteFolder)) {
                $websiteFolder = 'site-' . $website->id;
            }

            // Load GA4 data
            $ga4Path = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/ga4/{$year}/{$month}.json");
            if (file_exists($ga4Path)) {
                $this->ga4Data = json_decode(file_get_contents($ga4Path), true) ?? [];
            }

            // Load GSC data
            $gscPath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/gsc/{$year}/{$month}.json");
            if (file_exists($gscPath)) {
                $this->gscData = json_decode(file_get_contents($gscPath), true) ?? [];
            }

            // Set activeReportData if looking at specific tab
            if ($this->activeReportIntegrationId === 'ga4') {
                $this->activeReportData = $this->ga4Data;
            } elseif ($this->activeReportIntegrationId === 'gsc') {
                $this->activeReportData = $this->gscData;
            }
        } catch (\Exception $e) {
            Log::error('Error loading client marketing report JSON: ' . $e->getMessage());
        }
    }

    public function getAvailableReportMonths(): array
    {
        if (!$this->selectedWebsiteId) {
            return [];
        }

        try {
            $clientDetails = Client::where('user_id', Auth::id())->first();
            $website = Website::findOrFail($this->selectedWebsiteId);

            if (!$clientDetails || !$website) {
                return [];
            }

            $userName = Str::slug(Str::lower($clientDetails->user->name ?? 'client'));
            $emailParts = explode('@', $clientDetails->user->email ?? '');
            $emailPrefix = Str::slug(Str::lower($emailParts[0] ?? ''));
            $clientFolder = "{$userName}-{$emailPrefix}";

            $websiteFolder = Str::slug(Str::lower($website->site_name));
            if (empty($websiteFolder)) {
                $websiteFolder = 'site-' . $website->id;
            }

            // Scan both ga4 and gsc directories to find all available months
            $options = [];
            $types = ['ga4', 'gsc'];

            foreach ($types as $typeId) {
                $integrationPath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/{$typeId}");
                if (!file_exists($integrationPath)) {
                    continue;
                }

                $years = array_filter(glob($integrationPath . '/*'), 'is_dir');
                foreach ($years as $yearPath) {
                    $year = basename($yearPath);
                    $files = glob($yearPath . '/*.json');
                    
                    foreach ($files as $filePath) {
                        $monthFile = basename($filePath, '.json');
                        $value = "{$year}-{$monthFile}";
                        $options[$value] = [
                            'value' => $value,
                            'label' => ucfirst($monthFile) . " {$year}"
                        ];
                    }
                }
            }

            $options = array_values($options);

            usort($options, function ($a, $b) {
                $timeA = strtotime(str_replace('-', ' ', $a['value']));
                $timeB = strtotime(str_replace('-', ' ', $b['value']));
                return $timeB <=> $timeA;
            });

            return $options;

        } catch (\Exception $e) {
            return [];
        }
    }

    public function selectIntegration(string $typeId)
    {
        $this->activeReportIntegrationId = $typeId;
        $this->loadReportData();
    }

    public function render()
    {
        return view('modules.client.dashboard.client-marketing-reports')->layoutData(['title' => 'Marketing Reports']);
    }
}
