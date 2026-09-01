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
    public string $compareMonth = '';
    
    // Loaded reports
    public array $activeReportData = [];
    public array $ga4Data = [];
    public array $gscData = [];
    public array $youtubeData = [];
    public array $keywordData = [];
    
    // Comparison reports
    public array $compareGa4Data = [];
    public array $compareGscData = [];
    public array $compareYoutubeData = [];
    public array $compareKeywordData = [];
    
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

    public function updatedCompareMonth()
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
        
        $isCurrentMonthValid = false;
        if (!empty($this->selectedMonth) && !empty($this->availableMonths)) {
            foreach ($this->availableMonths as $monthOpt) {
                if ($monthOpt['value'] === $this->selectedMonth) {
                    $isCurrentMonthValid = true;
                    break;
                }
            }
        }

        if (!$isCurrentMonthValid) {
            if (!empty($this->availableMonths)) {
                $this->selectedMonth = $this->availableMonths[0]['value'];
            } else {
                $this->selectedMonth = date('Y') . '-' . Str::lower(date('F'));
            }
        }

        $this->loadReportData();
    }

    public function loadReportData(): void
    {
        $this->activeReportData = [];
        $this->ga4Data = [];
        $this->gscData = [];
        $this->youtubeData = [];
        $this->keywordData = [];

        $this->compareGa4Data = [];
        $this->compareGscData = [];
        $this->compareYoutubeData = [];
        $this->compareKeywordData = [];

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

            // Load YouTube data
            $youtubePath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/youtube/{$year}/{$month}.json");
            if (file_exists($youtubePath)) {
                $this->youtubeData = json_decode(file_get_contents($youtubePath), true) ?? [];
            }

            // Load Keyword data
            $keywordPath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/keyword/{$year}/{$month}.json");
            if (file_exists($keywordPath)) {
                $this->keywordData = json_decode(file_get_contents($keywordPath), true) ?? [];
            }

            // Load Comparison Data if selected
            if (!empty($this->compareMonth)) {
                $cParts = explode('-', $this->compareMonth);
                $cYear = $cParts[0] ?? date('Y');
                $cMonth = $cParts[1] ?? Str::lower(date('F'));

                $cGa4Path = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/ga4/{$cYear}/{$cMonth}.json");
                if (file_exists($cGa4Path)) {
                    $this->compareGa4Data = json_decode(file_get_contents($cGa4Path), true) ?? [];
                }

                $cGscPath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/gsc/{$cYear}/{$cMonth}.json");
                if (file_exists($cGscPath)) {
                    $this->compareGscData = json_decode(file_get_contents($cGscPath), true) ?? [];
                }

                $cYoutubePath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/youtube/{$cYear}/{$cMonth}.json");
                if (file_exists($cYoutubePath)) {
                    $this->compareYoutubeData = json_decode(file_get_contents($cYoutubePath), true) ?? [];
                }

                $cKeywordPath = storage_path("app/adscljson/{$clientFolder}/{$websiteFolder}/keyword/{$cYear}/{$cMonth}.json");
                if (file_exists($cKeywordPath)) {
                    $this->compareKeywordData = json_decode(file_get_contents($cKeywordPath), true) ?? [];
                }
            }

            // Set activeReportData if looking at specific tab
            if ($this->activeReportIntegrationId === 'ga4') {
                $this->activeReportData = $this->ga4Data;
            } elseif ($this->activeReportIntegrationId === 'gsc') {
                $this->activeReportData = $this->gscData;
            } elseif ($this->activeReportIntegrationId === 'youtube') {
                $this->activeReportData = $this->youtubeData;
            } elseif ($this->activeReportIntegrationId === 'keyword') {
                $this->activeReportData = $this->keywordData;
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
            $types = ['ga4', 'gsc', 'youtube', 'keyword'];

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
        if (empty($this->compareMonth)) {
            return '';
        }

        $p = (float)preg_replace('/[^0-9.]/', '', (string)$primaryVal);
        $c = (float)preg_replace('/[^0-9.]/', '', (string)$compareVal);

        if ($c <= 0 && $p <= 0) {
            return '';
        }

        if ($c <= 0) {
            $pctStr = '+100%';
            $isPos = true;
        } else {
            $pct = (($p - $c) / $c) * 100;
            $pctStr = ($pct >= 0 ? '+' : '') . number_format($pct, 1) . '%';
            $isPos = $higherIsBetter ? ($pct >= 0) : ($pct <= 0);
        }

        $badgeClass = $isPos ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 dark:text-emerald-400' : 'text-rose-600 bg-rose-50 dark:bg-rose-950/40 dark:text-rose-400';
        $icon = $isPos ? '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>' : '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>';
        $formattedCompare = is_numeric($compareVal) ? number_format((float)$compareVal) : $compareVal;

        return '<div class="mt-2.5 pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs">
            <span class="text-slate-400 dark:text-slate-500 text-[10px] font-medium">vs ' . $formattedCompare . '</span>
            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold ' . $badgeClass . '">' . $icon . $pctStr . '</span>
        </div>';
    }

    public function selectIntegration(string $typeId)
    {
        $this->activeReportIntegrationId = $typeId;
        $this->compareMonth = '';
        
        $this->availableMonths = $this->getAvailableReportMonths();
        if (!empty($this->availableMonths)) {
            $this->selectedMonth = $this->availableMonths[0]['value'];
        } else {
            $this->selectedMonth = date('Y') . '-' . Str::lower(date('F'));
        }

        $this->loadReportData();
    }

    public function render()
    {
        return view('modules.client.dashboard.client-marketing-reports')->layoutData(['title' => 'Marketing Reports']);
    }
}
