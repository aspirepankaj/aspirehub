<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Traits\LoadsMarketingReports;

#[Layout('layouts.client-portal')]
class ClientMarketingReports extends Component
{
    use LoadsMarketingReports;

    public ?int $selectedWebsiteId = null;
    public ?int $websiteId = null;

    public ?string $activeReportIntegrationId = 'overview';
    public ?string $activeReportPropertyId = null;
    
    // Loaded reports (protected so Livewire does NOT serialize 500KB+ JSON state across requests)
    protected array $activeReportData = [];
    protected array $ga4Data = [];
    protected array $gscData = [];
    protected array $youtubeData = [];
    protected array $keywordData = [];
    protected array $gtmData = [];
    protected array $gbpData = [];
    protected array $gadsData = [];
    protected array $facebookData = [];
    protected array $metaAdsData = [];
    protected array $linkedinData = [];
    
    // Comparison reports
    protected array $compareGa4Data = [];
    protected array $compareGscData = [];
    protected array $compareYoutubeData = [];
    protected array $compareKeywordData = [];
    protected array $compareGbpData = [];
    protected array $compareGadsData = [];
    protected array $compareFacebookData = [];
    protected array $compareMetaAdsData = [];
    
    // Dropdown list holders (protected to avoid bloating Livewire snapshot state)
    protected $websites = [];
    protected $integrations = [];
    
    // Legacy / active month properties
    public string $selectedMonth = '';
    public string $compareMonth = '';
    public array $availableMonths = [];

    public ?int $clientId = null;
    public bool $isEmbedded = false;

    public function boot(): void
    {
        $this->ensureWebsitesLoaded();
    }

    protected function ensureWebsitesLoaded(): void
    {
        if (empty($this->websites)) {
            if ($this->clientId) {
                $client = Client::find($this->clientId);
            } else {
                $client = Client::where('user_id', Auth::id())->first();
            }

            if ($client) {
                $this->websites = Website::where('client_id', $client->id)
                    ->where('status', 'active')
                    ->orderBy('site_name')
                    ->get();
            } else {
                $this->websites = collect();
            }
        }
    }

    public function mount($clientId = null, $isEmbedded = false)
    {
        $this->isEmbedded = $isEmbedded;
        $this->clientId = $clientId;
        $this->initDateRange();
        $this->ensureWebsitesLoaded();

        if ($this->clientId) {
            $client = Client::find($this->clientId);
        } else {
            $client = Client::where('user_id', Auth::id())->first();
        }

        if (!$client) {
            return;
        }

        $this->websites = Website::where('client_id', $client->id)
            ->where('status', 'active')
            ->orderBy('site_name')
            ->get();

        if ($this->websites->isNotEmpty()) {
            $this->selectedWebsiteId = $this->websites->first()->id;
            $this->websiteId = $this->selectedWebsiteId;
            
            $tab = request()->query('tab');
            // When embedded in admin/staff detail page, '?tab=marketing' is the parent page tab, not the integration report tab!
            if ($tab && $tab !== 'marketing') {
                $this->activeReportIntegrationId = $tab;
            } else {
                $this->activeReportIntegrationId = 'overview'; // Default to overview dashboard
            }

            $this->loadIntegrationsAndMonths();
        }
    }

    public function updatedSelectedWebsiteId()
    {
        $this->websiteId = $this->selectedWebsiteId;
        $this->loadIntegrationsAndMonths();
    }

    public function updatedActiveReportIntegrationId()
    {
        $this->updateActivePropertyId();
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

    protected function updateActivePropertyId(): void
    {
        if ($this->activeReportIntegrationId !== 'overview' && isset($this->integrations[$this->activeReportIntegrationId])) {
            $int = $this->integrations[$this->activeReportIntegrationId];
            $this->activeReportPropertyId = $int['auth_credentials']['property_id'] ?? ($int['api_credentials']['organization_id'] ?? ($int['account_identifier'] ?? null));
        } else {
            $this->activeReportPropertyId = null;
        }
    }

    protected function loadIntegrationsAndMonths()
    {
        if (!$this->selectedWebsiteId) {
            return;
        }

        $integrationsCollection = WebsiteIntegration::where('website_id', $this->selectedWebsiteId)
            ->where('status', 'connected')
            ->get()
            ->filter(function ($item) {
                // Integrations that require a specific property / page / site must have property_id selected
                $requiresProperty = in_array($item->integration_type, ['ga4', 'gsc', 'youtube', 'keyword', 'gbp', 'gads', 'linkedin']);
                if ($requiresProperty) {
                    $propId = $item->auth_credentials['property_id'] ?? ($item->api_credentials['organization_id'] ?? ($item->account_identifier ?? null));
                    if (empty($propId)) {
                        return false;
                    }
                }
                return true;
            })
            ->keyBy('integration_type')
            ->toArray();

        // If Facebook/Meta is connected, automatically make Meta Ads available
        if (isset($integrationsCollection['facebook'])) {
            $fbInt = $integrationsCollection['facebook'];
            $integrationsCollection['meta_ads'] = [
                'id' => $fbInt['id'] ?? null,
                'website_id' => $this->selectedWebsiteId,
                'integration_type' => 'meta_ads',
                'status' => 'connected',
                'api_credentials' => $fbInt['api_credentials'] ?? [],
                'auth_credentials' => $fbInt['auth_credentials'] ?? [],
                'account_identifier' => 'Meta Marketing API',
            ];
        }

        $this->integrations = $integrationsCollection;

        // Custom Alphabetical Order by visible Label (A to Z):
        // 1. Google Ads (G)
        // 2. Google Analytics 4 (G)
        // 3. Google Business Profile (G)
        // 4. Keyword.com (K)
        // 5. LinkedIn (L)
        // 6. Meta (M)
        // 7. Meta Ads (M)
        // 8. Search Console (S)
        // 9. Tag Manager (T)
        // 10. YouTube (Y)
        $alphabeticalOrder = ['gads', 'ga4', 'gbp', 'keyword', 'linkedin', 'facebook', 'meta_ads', 'gsc', 'gtm', 'youtube'];
        
        $orderedIntegrations = [];
        foreach ($alphabeticalOrder as $key) {
            if (isset($this->integrations[$key])) {
                $orderedIntegrations[$key] = $this->integrations[$key];
            }
        }
        // Include any remaining integrations that weren't in the list
        foreach ($this->integrations as $key => $val) {
            if (!isset($orderedIntegrations[$key])) {
                $orderedIntegrations[$key] = $val;
            }
        }
        $this->integrations = $orderedIntegrations;

        $requestedTab = request()->query('tab');
        if ($requestedTab === 'marketing') {
            $requestedTab = null;
        }
        $requestedTab = $requestedTab ?: $this->activeReportIntegrationId;
        if (in_array($requestedTab, ['overview', 'seo', 'social'])) {
            $this->activeReportIntegrationId = $requestedTab;
        } elseif ($requestedTab && isset($this->integrations[$requestedTab])) {
            $this->activeReportIntegrationId = $requestedTab;
        } elseif (!empty($this->integrations)) {
            // Default to overview dashboard when no specific valid integration is selected
            $this->activeReportIntegrationId = 'overview';
        } else {
            $this->activeReportIntegrationId = 'overview';
        }

        $this->updateActivePropertyId();
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
        $this->facebookData = [];
        $this->metaAdsData = [];
        $this->linkedinData = [];

        if (!$this->selectedWebsiteId) {
            return;
        }

        try {
            if ($this->clientId) {
                $clientDetails = Client::find($this->clientId);
            } else {
                $clientDetails = Client::where('user_id', Auth::id())->first();
            }
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

            // Optimize loading:
            // 1. If a specific tab is clicked, ONLY load that tab's data and completely skip all other integrations.
            // 2. If overview/seo/social is active, only load the relevant connected integrations.
            if (in_array($this->activeReportIntegrationId, ['overview', 'seo', 'social'])) {
                $connected = array_keys($this->integrations);

                $allowedIntegrations = match($this->activeReportIntegrationId) {
                    'seo' => ['ga4', 'gsc', 'keyword'],
                    'social' => ['facebook', 'linkedin', 'youtube'],
                    default => $connected,
                };
                
                if (in_array('ga4', $allowedIntegrations) && in_array('ga4', $connected)) {
                    $this->ga4Data = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'ga4', $this->dateFrom, $this->dateTo);
                }
                if (in_array('gsc', $allowedIntegrations) && in_array('gsc', $connected)) {
                    $this->gscData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gsc', $this->dateFrom, $this->dateTo);
                }
                if (in_array('youtube', $allowedIntegrations) && in_array('youtube', $connected)) {
                    $this->youtubeData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'youtube', $this->dateFrom, $this->dateTo);
                }
                if (in_array('keyword', $allowedIntegrations) && in_array('keyword', $connected)) {
                    $this->keywordData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'keyword', $this->dateFrom, $this->dateTo);
                }
                if (in_array('gbp', $allowedIntegrations) && in_array('gbp', $connected)) {
                    $this->gbpData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gbp', $this->dateFrom, $this->dateTo);
                }
                if (in_array('gads', $allowedIntegrations) && in_array('gads', $connected)) {
                    $this->gadsData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gads', $this->dateFrom, $this->dateTo);
                }
                if (in_array('facebook', $allowedIntegrations) && in_array('facebook', $connected)) {
                    $this->facebookData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'facebook', $this->dateFrom, $this->dateTo);
                }
                if (in_array('meta_ads', $allowedIntegrations) && in_array('meta_ads', $connected)) {
                    $this->metaAdsData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'meta_ads', $this->dateFrom, $this->dateTo);
                }
                if (in_array('linkedin', $allowedIntegrations) && in_array('linkedin', $connected)) {
                    $this->linkedinData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'linkedin', $this->dateFrom, $this->dateTo);
                }
                if (in_array('gtm', $allowedIntegrations) && in_array('gtm', $connected)) {
                    $this->gtmData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gtm', $this->dateFrom, $this->dateTo);
                }
                $this->activeReportData = [];
            } else {
                $tab = $this->activeReportIntegrationId;
                
                // Reset all other integrations data to keep Livewire state lightweight and fast
                $this->ga4Data = [];
                $this->gscData = [];
                $this->youtubeData = [];
                $this->keywordData = [];
                $this->gbpData = [];
                $this->gadsData = [];
                $this->facebookData = [];
                $this->metaAdsData = [];
                $this->linkedinData = [];
                $this->gtmData = [];

                $hasCompareDates = !empty($this->compareDateFrom) && !empty($this->compareDateTo);
                $activeData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, $tab, $this->dateFrom, $this->dateTo, true);

                if ($hasCompareDates) {
                    $compareData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, $tab, $this->compareDateFrom, $this->compareDateTo, false);
                    $activeData['compare_data'] = $compareData;
                }

                $this->activeReportData = $activeData;

                if ($tab === 'ga4') $this->ga4Data = $activeData;
                elseif ($tab === 'gsc') $this->gscData = $activeData;
                elseif ($tab === 'youtube') $this->youtubeData = $activeData;
                elseif ($tab === 'keyword') $this->keywordData = $activeData;
                elseif ($tab === 'gbp') $this->gbpData = $activeData;
                elseif ($tab === 'gads') $this->gadsData = $activeData;
                elseif ($tab === 'facebook') $this->facebookData = $activeData;
                elseif ($tab === 'meta_ads') $this->metaAdsData = $activeData;
                elseif ($tab === 'linkedin') $this->linkedinData = $activeData;
                elseif ($tab === 'gtm') $this->gtmData = $activeData;
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
            if ($this->clientId) {
                $clientDetails = Client::find($this->clientId);
            } else {
                $clientDetails = Client::where('user_id', Auth::id())->first();
            }
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
            $types = ['ga4', 'gsc', 'youtube', 'keyword', 'gtm', 'facebook', 'linkedin'];

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
        if ($this->activeReportIntegrationId === $typeId) {
            return;
        }

        $this->activeReportIntegrationId = $typeId;
        $this->updateActivePropertyId();
        
        $this->datePreset = 'last_28';
        $this->dateFrom = \Carbon\Carbon::now()->subDays(28)->format('Y-m-d');
        $this->dateTo = \Carbon\Carbon::now()->subDays(1)->format('Y-m-d');
        
        $this->compareDateFrom = '';
        $this->compareDateTo = '';
        
        session()->forget([
            'marketing_date_from',
            'marketing_date_to',
            'marketing_compare_date_from',
            'marketing_compare_date_to',
            'marketing_date_preset'
        ]);

        if (!$this->isEmbedded) {
            $this->js("
                let u = new URL(window.location.href); 
                if ('{$typeId}' === 'overview') {
                    u.searchParams.delete('tab');
                } else {
                    u.searchParams.set('tab', '{$typeId}');
                }
                u.searchParams.delete('compareDateFrom'); 
                u.searchParams.delete('compareDateTo'); 
                u.searchParams.delete('dateFrom'); 
                u.searchParams.delete('dateTo'); 
                u.searchParams.delete('datePreset'); 
                window.history.replaceState({}, '', u.toString());
            ");
        }

        $this->loadReportData();
    }

    public function render()
    {
        $this->ensureWebsitesLoaded();
        if (empty($this->integrations) && $this->selectedWebsiteId) {
            $this->loadIntegrationsAndMonths();
        }

        // If data hasn't been loaded in this request lifecycle, load it
        if (empty($this->activeReportData) && empty($this->ga4Data) && empty($this->facebookData) && empty($this->metaAdsData) && empty($this->linkedinData) && empty($this->gscData) && empty($this->youtubeData) && empty($this->keywordData) && empty($this->gbpData) && empty($this->gadsData)) {
            $this->loadReportData();
        }

        // Compute overview top statistics matching Client Dashboard
        $stats = [
            'websites' => 0,
            'linked_analytics' => 0,
            'active_services' => 0,
            'last_report_month' => 'No Report',
        ];

        // Determine current effective clientId (from property or authenticated user)
        $effectiveClientId = $this->clientId;
        if (!$effectiveClientId && Auth::check()) {
            $effectiveClientId = DB::table('adspv_clients')
                ->where('user_id', Auth::id())
                ->value('id');
        }

        // If still no clientId, fallback to the owner of the selected website
        if (!$effectiveClientId && $this->selectedWebsiteId) {
            $effectiveClientId = DB::table('adspv_websites')
                ->where('id', $this->selectedWebsiteId)
                ->value('client_id');
        }

        if ($effectiveClientId) {
            // Count ONLY active websites (excluding inactive or deleted)
            $websiteCount = DB::table('adspv_websites')
                ->where('client_id', $effectiveClientId)
                ->where('status', 'active')
                ->count();

            $activeServices = DB::table('adspv_client_plan')
                ->where('client_id', $effectiveClientId)
                ->count();

            // Active websites belonging to this client
            $websiteIds = DB::table('adspv_websites')
                ->where('client_id', $effectiveClientId)
                ->where('status', 'active')
                ->pluck('id');

            // Integrations connected to the selected website, or across active websites
            $connectedQuery = DB::table('adspv_website_integrations')
                ->where('status', 'connected');

            if ($this->selectedWebsiteId) {
                $connectedQuery->where('website_id', $this->selectedWebsiteId);
            } else {
                $connectedQuery->whereIn('website_id', $websiteIds);
            }

            $connectedTypes = $connectedQuery->pluck('integration_type')
                ->unique()
                ->toArray();

            // If Facebook is connected, Meta Ads is also automatically active/available
            if (in_array('facebook', $connectedTypes) && !in_array('meta_ads', $connectedTypes)) {
                $connectedTypes[] = 'meta_ads';
            }

            $lastReport = DB::table('adspv_maintenance_reports')
                ->where(function ($q) use ($effectiveClientId, $websiteIds) {
                    $q->where('client_id', $effectiveClientId);
                    if ($websiteIds->isNotEmpty()) {
                        $q->orWhereIn('website_id', $websiteIds);
                    }
                })
                ->where('status', 'completed')
                ->orderByDesc('maintenance_date')
                ->orderByDesc('id')
                ->first();

            if (!$lastReport) {
                $lastReport = DB::table('adspv_maintenance_reports')
                    ->where(function ($q) use ($effectiveClientId, $websiteIds) {
                        $q->where('client_id', $effectiveClientId);
                        if ($websiteIds->isNotEmpty()) {
                            $q->orWhereIn('website_id', $websiteIds);
                        }
                    })
                    ->orderByDesc('maintenance_date')
                    ->orderByDesc('id')
                    ->first();
            }

            $lastReportMonth = 'No Report';
            if ($lastReport) {
                if (!empty($lastReport->maintenance_month)) {
                    try {
                        $lastReportMonth = \Carbon\Carbon::parse($lastReport->maintenance_month)->format('F Y');
                    } catch (\Throwable $e) {
                        $lastReportMonth = $lastReport->maintenance_month;
                    }
                } elseif (!empty($lastReport->maintenance_date)) {
                    $lastReportMonth = \Carbon\Carbon::parse($lastReport->maintenance_date)->format('F Y');
                } elseif (!empty($lastReport->created_at)) {
                    $lastReportMonth = \Carbon\Carbon::parse($lastReport->created_at)->format('F Y');
                }
            }

            $stats = [
                'websites'          => $websiteCount,
                'active_services'   => $activeServices,
                'linked_analytics'  => count($connectedTypes),
                'last_report_month' => $lastReportMonth,
            ];
        }

        // Use separate view for Admin & Staff portal so client design is never mixed/messed up
        $user = Auth::user();
        $isAdminOrStaff = $this->isEmbedded || ($user && ($user->admin || $user->staff));
        $viewName = $isAdminOrStaff
            ? 'modules.client.dashboard.admin-marketing-reports'
            : 'modules.client.dashboard.client-marketing-reports';

        return view($viewName, [
            'isEmbedded' => $this->isEmbedded,
            'clientId' => $this->clientId,
            'websites' => $this->websites,
            'stats' => $stats,
            'integrations' => $this->integrations,
            'activeReportIntegrationId' => $this->activeReportIntegrationId,
            'activeReportPropertyId' => $this->activeReportPropertyId,
            'activeReportData' => $this->activeReportData,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'compareDateFrom' => $this->compareDateFrom,
            'compareDateTo' => $this->compareDateTo,
            'compareFormat' => $this->compareFormat,
            'ga4Data' => $this->ga4Data,
            'gscData' => $this->gscData,
            'youtubeData' => $this->youtubeData,
            'keywordData' => $this->keywordData,
            'gtmData' => $this->gtmData,
            'gbpData' => $this->gbpData,
            'gadsData' => $this->gadsData,
            'facebookData' => $this->facebookData,
            'metaAdsData' => $this->metaAdsData,
            'linkedinData' => $this->linkedinData,
        ])->layoutData(['title' => 'Marketing Reports']);
    }
}

