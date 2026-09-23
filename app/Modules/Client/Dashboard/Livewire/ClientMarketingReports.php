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

use App\Traits\LoadsMarketingReports;

#[Layout('layouts.client-portal')]
class ClientMarketingReports extends Component
{
    use LoadsMarketingReports;

    public ?int $selectedWebsiteId = null;
    public string $activeReportIntegrationId = 'overview';
    
    // Loaded reports
    public array $activeReportData = [];
    public array $ga4Data = [];
    public array $gscData = [];
    public array $youtubeData = [];
    public array $keywordData = [];
    public array $gtmData = [];
    public array $gbpData = [];
    public array $gadsData = [];
    
    // Comparison reports
    public array $compareGa4Data = [];
    public array $compareGscData = [];
    public array $compareYoutubeData = [];
    public array $compareKeywordData = [];
    public array $compareGbpData = [];
    public array $compareGadsData = [];
    
    // Dropdown list holders
    public $websites = [];
    public $integrations = [];
    
    // Legacy / active month properties
    public string $selectedMonth = '';
    public string $compareMonth = '';
    public array $availableMonths = [];

    public function mount()
    {
        $this->initDateRange();

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
        $this->loadReportData();
    }

    public function updatedDateFrom()
    {
        $this->loadReportData();
    }

    public function updatedDateTo()
    {
        $this->loadReportData();
    }

    public function updatedCompareDateFrom()
    {
        $this->loadReportData();
    }

    public function updatedCompareDateTo()
    {
        $this->loadReportData();
    }

    public function updatedCompareFormat()
    {
        // triggers re-render automatically
    }

    public function applyDateFilter($dateFrom, $dateTo, $compareFrom, $compareTo, $includeToday, $format)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->compareDateFrom = $compareFrom;
        $this->compareDateTo = $compareTo;
        $this->includeToday = filter_var($includeToday, FILTER_VALIDATE_BOOLEAN);
        $this->compareFormat = $format;
        
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

        $this->activeReportIntegrationId = 'overview';
        $this->loadReportData();
    }

    public function loadReportData(): void
    {
        $this->activeReportData = [];
        $this->ga4Data = [];
        $this->gscData = [];
        $this->youtubeData = [];
        $this->keywordData = [];
        $this->gtmData = [];
        $this->gbpData = [];
        $this->gadsData = [];

        if (!$this->selectedWebsiteId) {
            return;
        }

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

            // Load data filtered by date range
            $this->ga4Data = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'ga4', $this->dateFrom, $this->dateTo);
            $this->gscData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gsc', $this->dateFrom, $this->dateTo);
            $this->youtubeData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'youtube', $this->dateFrom, $this->dateTo);
            $this->keywordData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'keyword', $this->dateFrom, $this->dateTo);
            $this->gbpData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gbp', $this->dateFrom, $this->dateTo);
            $this->gadsData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gads', $this->dateFrom, $this->dateTo);

            // Load comparison data
            if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) {
                $this->compareGa4Data = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'ga4', $this->compareDateFrom, $this->compareDateTo);
                $this->compareGscData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gsc', $this->compareDateFrom, $this->compareDateTo);
                $this->compareYoutubeData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'youtube', $this->compareDateFrom, $this->compareDateTo);
                $this->compareKeywordData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'keyword', $this->compareDateFrom, $this->compareDateTo);
                $this->compareGbpData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gbp', $this->compareDateFrom, $this->compareDateTo);
                $this->compareGadsData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, 'gads', $this->compareDateFrom, $this->compareDateTo);
            } else {
                $this->compareGa4Data = [];
                $this->compareGscData = [];
                $this->compareYoutubeData = [];
                $this->compareKeywordData = [];
                $this->compareGbpData = [];
                $this->compareGadsData = [];
            }

            // Set activeReportData if looking at specific tab
            if ($this->activeReportIntegrationId === 'ga4' || $this->activeReportIntegrationId === 'overview') {
                $this->activeReportData = $this->ga4Data;
                if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) $this->activeReportData['compare_data'] = $this->compareGa4Data;
            } elseif ($this->activeReportIntegrationId === 'gsc') {
                $this->activeReportData = $this->gscData;
                if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) $this->activeReportData['compare_data'] = $this->compareGscData;
            } elseif ($this->activeReportIntegrationId === 'youtube') {
                $this->activeReportData = $this->youtubeData;
                if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) $this->activeReportData['compare_data'] = $this->compareYoutubeData;
            } elseif ($this->activeReportIntegrationId === 'keyword') {
                $this->activeReportData = $this->keywordData;
                if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) $this->activeReportData['compare_data'] = $this->compareKeywordData;
            } elseif ($this->activeReportIntegrationId === 'gtm') {
                $this->activeReportData = $this->gtmData;
            } elseif ($this->activeReportIntegrationId === 'gbp') {
                $this->activeReportData = $this->gbpData;
                if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) $this->activeReportData['compare_data'] = $this->compareGbpData;
            } elseif ($this->activeReportIntegrationId === 'gads') {
                $this->activeReportData = $this->gadsData;
                if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) $this->activeReportData['compare_data'] = $this->compareGadsData;
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

            // Scan all directories to find all available months
            $options = [];
            $types = ['ga4', 'gsc', 'youtube', 'keyword', 'gtm'];

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

    public function renderCompareDiff($primaryVal, $compareVal, bool $higherIsBetter = true): string
    {
        if (empty($this->compareDateFrom)) {
            return '';
        }

        $p = (float)preg_replace('/[^0-9.-]/', '', (string)$primaryVal);
        $c = (float)preg_replace('/[^0-9.-]/', '', (string)$compareVal);

        if ($c <= 0 && $p <= 0) {
            return '';
        }

        $diff = $p - $c;
        $isPos = $higherIsBetter ? ($diff >= 0) : ($diff <= 0);

        if ($this->compareFormat === 'absolute') {
            $absStr = ($diff > 0 ? '+' : '') . number_format($diff, 1);
            $displayStr = $absStr;
        } else {
            if ($c <= 0) {
                $displayStr = '+100%';
                $isPos = true;
            } else {
                $pct = ($diff / $c) * 100;
                $displayStr = ($pct >= 0 ? '+' : '') . number_format($pct, 1) . '%';
            }
        }

        $badgeClass = $isPos ? 'text-teal-700 bg-teal-50 dark:bg-teal-900/40 dark:text-teal-400' : 'text-rose-600 bg-rose-50 dark:bg-rose-950/40 dark:text-rose-400';
        $icon = $isPos ? '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>' : '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>';
        $formattedCompare = is_numeric($compareVal) ? number_format((float)$compareVal) : $compareVal;

        return '<div class="mt-2.5 pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs">
            <span class="text-slate-400 dark:text-slate-500 text-[10px] font-medium">vs ' . $formattedCompare . '</span>
            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-sm text-[10px] font-bold ' . $badgeClass . '">' . $icon . $displayStr . '</span>
        </div>';
    }

    public function selectIntegration(string $typeId)
    {
        $this->activeReportIntegrationId = $typeId;
        $this->compareDateFrom = '';
        $this->compareDateTo = '';
        
        if ($typeId === 'overview') {
            $this->dateFrom = \Carbon\Carbon::now()->subDays(28)->format('Y-m-d');
            $this->dateTo = \Carbon\Carbon::now()->format('Y-m-d');
        }

        $this->loadReportData();
    }

    public function render()
    {
        return view('modules.client.dashboard.client-marketing-reports')->layoutData(['title' => 'Marketing Reports']);
    }
}

