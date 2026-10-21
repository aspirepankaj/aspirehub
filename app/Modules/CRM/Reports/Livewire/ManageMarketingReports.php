<?php

namespace App\Modules\CRM\Reports\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Services\AiMarketingReportService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Traits\LoadsMarketingReports;

#[\Livewire\Attributes\Title('Marketing Reports')]
class ManageMarketingReports extends Component
{
    use LoadsMarketingReports;

    #[\Livewire\Attributes\Url]
    public $clientId = null;
    
    #[\Livewire\Attributes\Url]
    public $websiteId = null;
    
    public $activeTab = 'overview';

    // Overview data containers (protected to avoid bulky Livewire state)
    protected array $ga4Data = [];
    protected array $gscData = [];
    protected array $youtubeData = [];
    protected array $keywordData = [];
    protected array $gtmData = [];
    protected array $gbpData = [];
    protected array $gadsData = [];
    protected array $facebookData = [];
    protected array $linkedinData = [];

    // AI Marketing Report State (hidden by default as requested, can be toggled without removing any code)
    public bool $showAiMarketingReport = false;
    public string $aiPrompt = '';
    public bool $isGeneratingAiReport = false;
    public ?array $aiReport = null;
    public ?string $aiGeneratedHtml = null;
    public bool $showAiHtmlReport = false;
    public array $aiTabs = [];
    public ?string $activeAiTab = null;
    public array $aiReportsByPlatform = [];
    public string $aiError = '';
    public bool $showAiReportPanel = false;

    public function mount()
    {
        $this->dateFrom = request('from', session('marketing_date_from', \Carbon\Carbon::now()->subDays(28)->format('Y-m-d')));
        $this->dateTo = request('to', session('marketing_date_to', \Carbon\Carbon::now()->subDays(1)->format('Y-m-d')));

        if (empty($this->clientId)) {
            $this->compareDateFrom = '';
            $this->compareDateTo = '';
        }

        $tab = request()->query('tab');
        if ($tab && in_array($tab, ['overview', 'ga4', 'gsc', 'youtube', 'keyword', 'gbp', 'gads', 'facebook', 'linkedin', 'gtm'])) {
            $this->activeTab = $tab;
        } elseif ($this->websiteId && empty($this->activeTab)) {
            $this->activeTab = 'overview';
        }
    }

    private function getValidIntegrations(Website $website)
    {
        $names = [
            'gbp' => 'Business Profile',
            'facebook' => 'Facebook',
            'gads' => 'Google Ads',
            'ga4' => 'Google Analytics 4',
            'keyword' => 'Keyword.com',
            'linkedin' => 'LinkedIn',
            'gsc' => 'Search Console',
            'gtm' => 'Tag Manager',
            'youtube' => 'YouTube'
        ];

        return $website->integrations
            ->filter(function ($item) {
                if ($item->status !== 'connected') {
                    return false;
                }
                $requiresProperty = in_array($item->integration_type, ['ga4', 'gsc', 'youtube', 'keyword', 'gbp', 'gads', 'linkedin']);
                if ($requiresProperty) {
                    $propId = $item->auth_credentials['property_id'] 
                        ?? ($item->api_credentials['organization_id'] 
                        ?? ($item->account_identifier ?? null));
                    if (empty($propId)) {
                        return false;
                    }
                }
                return true;
            })
            ->sortBy(function($intg) use ($names) {
                return $names[$intg->integration_type] ?? ucfirst($intg->integration_type);
            });
    }

    public function selectClient($id)
    {
        $this->clientId = (int)$id;
        $this->updatedClientId($this->clientId);
    }

    public function selectWebsite($id)
    {
        $this->websiteId = (int)$id;
        $this->updatedWebsiteId($this->websiteId);
    }

    public function updatedClientId($value)
    {
        $this->websiteId = null;
        $this->activeTab = null;
        
        if ($value) {
            $firstWebsite = Website::where('client_id', $value)->where('status', 'active')->whereHas('integrations')->first();
            if ($firstWebsite) {
                $this->websiteId = $firstWebsite->id;
                $this->js("
                    let u = new URL(window.location.href); 
                    u.searchParams.set('clientId', '{$value}');
                    u.searchParams.set('websiteId', '{$this->websiteId}');
                    u.searchParams.delete('compareDateFrom'); 
                    u.searchParams.delete('compareDateTo'); 
                    u.searchParams.delete('cfrom'); 
                    u.searchParams.delete('cto'); 
                    window.history.replaceState({}, '', u.toString());
                ");
                $this->updatedWebsiteId($this->websiteId);
            } else {
                $this->js("
                    let u = new URL(window.location.href); 
                    u.searchParams.set('clientId', '{$value}');
                    u.searchParams.delete('websiteId');
                    u.searchParams.delete('compareDateFrom'); 
                    u.searchParams.delete('compareDateTo'); 
                    u.searchParams.delete('cfrom'); 
                    u.searchParams.delete('cto'); 
                    window.history.replaceState({}, '', u.toString());
                ");
            }
        } else {
            $this->js("
                let u = new URL(window.location.href); 
                u.searchParams.delete('clientId');
                u.searchParams.delete('websiteId');
                u.searchParams.delete('compareDateFrom'); 
                u.searchParams.delete('compareDateTo'); 
                u.searchParams.delete('cfrom'); 
                u.searchParams.delete('cto'); 
                window.history.replaceState({}, '', u.toString());
            ");
        }
    }

    public function updatedWebsiteId($value)
    {
        $this->activeTab = 'overview';
        
        if ($value) {
            $this->js("
                let u = new URL(window.location.href); 
                u.searchParams.set('websiteId', '{$value}');
                u.searchParams.delete('compareDateFrom'); 
                u.searchParams.delete('compareDateTo'); 
                u.searchParams.delete('cfrom'); 
                u.searchParams.delete('cto'); 
                window.history.replaceState({}, '', u.toString());
            ");
        }
    }

    public function resetSelection()
    {
        $this->clientId = null;
        $this->websiteId = null;
        $this->activeTab = null;
        $this->aiGeneratedHtml = null;
        $this->showAiHtmlReport = false;
        $this->aiTabs = [];
        $this->activeAiTab = null;
        $this->aiReportsByPlatform = [];
        $this->aiError = '';
        $this->aiPrompt = '';
        $this->js("
            let u = new URL(window.location.href); 
            u.searchParams.delete('clientId');
            u.searchParams.delete('websiteId');
            u.searchParams.delete('compareDateFrom'); 
            u.searchParams.delete('compareDateTo'); 
            u.searchParams.delete('cfrom'); 
            u.searchParams.delete('cto'); 
            window.history.replaceState({}, '', u.toString());
        ");
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        
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

        $this->js("
            let u = new URL(window.location.href); 
            u.searchParams.delete('compareDateFrom'); 
            u.searchParams.delete('compareDateTo'); 
            u.searchParams.delete('cfrom'); 
            u.searchParams.delete('cto'); 
            
            // Delete date parameters so they don't stick
            u.searchParams.delete('dateFrom'); 
            u.searchParams.delete('dateTo'); 
            u.searchParams.delete('datePreset'); 

            window.history.replaceState({}, '', u.toString());
        ");
    }

    public function generateAiReport(AiMarketingReportService $service, ?string $presetPrompt = null)
    {
        $this->aiError = '';
        if (!empty($presetPrompt)) {
            $this->aiPrompt = $presetPrompt;
        }

        $prompt = trim($this->aiPrompt);
        if (empty($prompt)) {
            if ($this->clientId && $this->websiteId) {
                $website = Website::find($this->websiteId);
                $prompt = "Executive marketing performance report for " . ($website->site_name ?? 'website');
                $this->aiPrompt = $prompt;
            } else {
                $this->aiError = 'Please select a client and website or enter a prompt to generate an AI report.';
                return;
            }
        }

        $this->isGeneratingAiReport = true;

        try {
            $parsed = $service->parsePrompt($prompt, $this->clientId, $this->websiteId);

            if (!$parsed['client']) {
                $this->aiError = 'Client could not be identified. Please choose a client from the dropdown above or mention the client/business name in your prompt.';
                $this->isGeneratingAiReport = false;
                return;
            }

            if (!$parsed['website']) {
                $this->aiError = 'Website could not be identified. Please choose a website from the dropdown above or mention the website domain in your prompt.';
                $this->isGeneratingAiReport = false;
                return;
            }

            // Sync component state with resolved client & website
            $this->clientId = $parsed['client']->id;
            $this->websiteId = $parsed['website']->id;
            $website = $parsed['website'];

            // 1. Resolve Target Platform & Validate Integration
            $validIntegrations = $this->getValidIntegrations($website);
            $platformDisplayNames = [
                'gads'     => 'Google Ads',
                'ga4'      => 'Google Analytics 4',
                'gsc'      => 'Search Console',
                'facebook' => 'Facebook',
                'linkedin' => 'LinkedIn',
                'youtube'  => 'YouTube',
                'keyword'  => 'Keyword.com',
                'gbp'      => 'Google Business Profile',
                'gtm'      => 'Google Tag Manager',
            ];

            $siteLabel = $website->site_name . ($website->url ? ' (' . (parse_url($website->url, PHP_URL_HOST) ?: $website->url) . ')' : '');

            // Check if website has ANY connected integrations
            if ($validIntegrations->isEmpty()) {
                $this->aiError = "No marketing integrations are connected for {$siteLabel}.";
                $this->activeTab = null;
                $this->isGeneratingAiReport = false;
                $this->js("
                    let u = new URL(window.location.href); 
                    u.searchParams.set('clientId', '{$this->clientId}');
                    u.searchParams.set('websiteId', '{$this->websiteId}');
                    window.history.replaceState({}, '', u.toString());
                ");
                return;
            }

            // 1. Resolve Target Platforms & Validate Integrations
            $connectedTypes = $validIntegrations->pluck('integration_type')->toArray();
            $requestedPlatforms = !empty($parsed['platforms']) ? $parsed['platforms'] : [];
            if (empty($requestedPlatforms) && !empty($parsed['primary_platform'])) {
                $requestedPlatforms = [$parsed['primary_platform']];
            }

            // Check if any specifically requested platform is NOT connected for this website
            if (!empty($requestedPlatforms)) {
                $missing = [];
                foreach ($requestedPlatforms as $rp) {
                    if (!in_array($rp, $connectedTypes)) {
                        $missing[] = $platformDisplayNames[$rp] ?? strtoupper($rp);
                    }
                }

                if (!empty($missing)) {
                    $missingStr = implode(', ', $missing);
                    $avail = $validIntegrations->map(fn($i) => $platformDisplayNames[$i->integration_type] ?? strtoupper($i->integration_type))->implode(', ');
                    $this->aiError = "{$missingStr} is not connected for {$siteLabel}. Available connected integrations: {$avail}.";
                    $this->activeTab = null;
                    $this->aiTabs = [];
                    $this->activeAiTab = null;
                    $this->aiReportsByPlatform = [];
                    $this->showAiHtmlReport = false;
                    $this->isGeneratingAiReport = false;
                    $this->js("
                        let u = new URL(window.location.href); 
                        u.searchParams.set('clientId', '{$this->clientId}');
                        u.searchParams.set('websiteId', '{$this->websiteId}');
                        window.history.replaceState({}, '', u.toString());
                    ");
                    return;
                }

                // If user wrote "etc." or "all_platforms", include all other connected integrations too
                if (!empty($parsed['all_platforms'])) {
                    $platformsToRun = array_values(array_unique(array_merge($requestedPlatforms, $connectedTypes)));
                } else {
                    $platformsToRun = array_values(array_unique($requestedPlatforms));
                }
            } else {
                // If user didn't mention specific platforms, use all connected integrations
                $platformsToRun = $connectedTypes;
            }

            // Set AI tabs
            $this->aiTabs = $platformsToRun;
            $this->activeAiTab = $platformsToRun[0] ?? null;
            $this->activeTab = $this->activeAiTab;
            $this->aiReportsByPlatform = [];

            // 2. Set the exact requested Date Range from prompt
            $this->dateFrom = $parsed['date_from'];
            $this->dateTo = $parsed['date_to'];
            $this->compareDateFrom = '';
            $this->compareDateTo = '';

            session([
                'marketing_date_from' => $this->dateFrom,
                'marketing_date_to'   => $this->dateTo,
            ]);
            session()->forget([
                'marketing_compare_date_from',
                'marketing_compare_date_to',
                'marketing_date_preset',
            ]);

            $this->aiReport = null;
            $this->showAiReportPanel = false;

            // 3. Update browser URL so client, website, and dates persist
            $this->js("
                let u = new URL(window.location.href); 
                u.searchParams.set('clientId', '{$this->clientId}');
                u.searchParams.set('websiteId', '{$this->websiteId}');
                u.searchParams.set('from', '{$this->dateFrom}');
                u.searchParams.set('to', '{$this->dateTo}');
                u.searchParams.delete('compareDateFrom'); 
                u.searchParams.delete('compareDateTo'); 
                u.searchParams.delete('cfrom'); 
                u.searchParams.delete('cto'); 
                window.history.replaceState({}, '', u.toString());
            ");

            // 4. Generate AI HTML report for the active tab from real telemetry
            $activePlatform = $this->activeAiTab;
            $parsedForActive = array_merge($parsed, [
                'primary_platform' => $activePlatform,
                'platforms'        => [$activePlatform],
            ]);
            $metrics = $service->gatherMetrics($parsed['client'], $website, [$activePlatform], $this->dateFrom, $this->dateTo);
            $activeHtml = $service->generateHtmlReport($prompt, $parsedForActive, $metrics);

            $this->aiReportsByPlatform[$activePlatform] = $activeHtml;
            $this->aiGeneratedHtml = $activeHtml;
            $this->showAiHtmlReport = true;
        } catch (\Throwable $e) {
            Log::error('AI Marketing Report Filter Exception: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (Str::contains($msg, ['Incorrect API key', 'invalid_api_key', 'Unauthorized', '401', 'api_key', 'authentication'])) {
                $this->aiError = "OpenAI API Error: Invalid API key provided. Please verify your OPENAI_API_KEY in .env.";
            } elseif (Str::contains($msg, ['quota', 'exceeded', '429', 'rate_limit', 'billing'])) {
                $this->aiError = "OpenAI API Error: Quota exceeded or rate limit reached. Please check your OpenAI billing plan.";
            } else {
                $this->aiError = 'OpenAI API Error: ' . $msg;
            }
        } finally {
            $this->isGeneratingAiReport = false;
        }
    }

    public function switchAiTab(string $tab, AiMarketingReportService $service)
    {
        if (!in_array($tab, $this->aiTabs)) {
            return;
        }

        $this->activeAiTab = $tab;
        $this->activeTab = $tab;

        // If report for this tab is already generated, switch immediately!
        if (!empty($this->aiReportsByPlatform[$tab])) {
            $this->aiGeneratedHtml = $this->aiReportsByPlatform[$tab];
            return;
        }

        // Generate on demand for the newly selected tab
        $this->isGeneratingAiReport = true;
        $this->aiError = '';

        try {
            $website = Website::find($this->websiteId);
            $client = Client::find($this->clientId);
            $parsedForTab = [
                'client'           => $client,
                'website'          => $website,
                'primary_platform' => $tab,
                'platforms'        => [$tab],
                'date_from'        => $this->dateFrom,
                'date_to'          => $this->dateTo,
                'days'             => \Carbon\Carbon::parse($this->dateFrom)->diffInDays(\Carbon\Carbon::parse($this->dateTo)) + 1,
            ];
            $metrics = $service->gatherMetrics($client, $website, [$tab], $this->dateFrom, $this->dateTo);
            $html = $service->generateHtmlReport($this->aiPrompt, $parsedForTab, $metrics);

            $this->aiReportsByPlatform[$tab] = $html;
            $this->aiGeneratedHtml = $html;
        } catch (\Throwable $e) {
            Log::error('AI Tab Report Generation Error: ' . $e->getMessage());
            $this->aiError = 'Error generating report for ' . strtoupper($tab) . ': ' . $e->getMessage();
        } finally {
            $this->isGeneratingAiReport = false;
        }
    }

    public function closeAiHtmlReport()
    {
        $this->showAiHtmlReport = false;
    }

    public function toggleAiReportPanel()
    {
        $this->showAiReportPanel = !$this->showAiReportPanel;
    }

    public function clearAiReport()
    {
        $this->aiReport = null;
        $this->aiGeneratedHtml = null;
        $this->showAiHtmlReport = false;
        $this->aiTabs = [];
        $this->activeAiTab = null;
        $this->aiReportsByPlatform = [];
        $this->showAiReportPanel = false;
        $this->aiPrompt = '';
        $this->aiError = '';
    }

    public function render()
    {
        if (request()->is('staffadspnl*') && auth()->user() && auth()->user()->staff) {
            $clients = auth()->user()->staff->clients()->where('status', 'active')->whereHas('websites.integrations')->with(['user', 'websites'])->get();
        } else {
            $clients = Client::where('status', 'active')->whereHas('websites.integrations')->with(['user', 'websites'])->get();
        }
        $websites = [];
        $integrations = collect();

        if ($this->clientId) {
            $websites = Website::where('client_id', $this->clientId)->where('status', 'active')->whereHas('integrations')->get();
        }

        if ($this->websiteId) {
            $website = Website::find($this->websiteId);
            if ($website) {
                $integrations = $this->getValidIntegrations($website);
                if ($this->activeTab !== 'overview' && $this->activeTab && !$integrations->contains('integration_type', $this->activeTab)) {
                    $this->activeTab = 'overview';
                }

                // When activeTab is 'overview', load overview summary data for connected integrations
                if ($this->activeTab === 'overview') {
                    $client = Client::with('user')->find($this->clientId);
                    if ($client) {
                        $userName = Str::slug(Str::lower($client->user->name ?? 'client'));
                        $emailParts = explode('@', $client->user->email ?? '');
                        $emailPrefix = Str::slug(Str::lower($emailParts[0] ?? ''));
                        $clientFolder = "{$userName}-{$emailPrefix}";

                        $websiteFolder = Str::slug(Str::lower($website->site_name ?? ''));
                        if (empty($websiteFolder)) {
                            $websiteFolder = 'site-' . $website->id;
                        }

                        $connectedTypes = $integrations->pluck('integration_type')->toArray();

                        if (in_array('ga4', $connectedTypes)) {
                            $this->ga4Data = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'ga4', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('gsc', $connectedTypes)) {
                            $this->gscData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gsc', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('youtube', $connectedTypes)) {
                            $this->youtubeData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'youtube', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('keyword', $connectedTypes)) {
                            $this->keywordData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'keyword', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('gbp', $connectedTypes)) {
                            $this->gbpData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gbp', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('gads', $connectedTypes)) {
                            $this->gadsData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gads', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('facebook', $connectedTypes)) {
                            $this->facebookData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'facebook', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('linkedin', $connectedTypes)) {
                            $this->linkedinData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'linkedin', $this->dateFrom, $this->dateTo);
                        }
                        if (in_array('gtm', $connectedTypes)) {
                            $this->gtmData = $this->loadIntegrationOverviewSummary($clientFolder, $websiteFolder, 'gtm', $this->dateFrom, $this->dateTo);
                        }
                    }
                }
            }
        }

        $layout = request()->is('staffadspnl*') ? 'layouts.staff' : 'layouts.admin';
        return view('modules.crm.reports.livewire.manage-marketing-reports', [
            'clients'              => $clients,
            'websites'             => $websites,
            'integrations'         => collect($integrations),
            'clientId'             => $this->clientId,
            'websiteId'            => $this->websiteId,
            'activeTab'            => $this->activeTab,
            'dateFrom'             => $this->dateFrom,
            'dateTo'               => $this->dateTo,
            'ga4Data'              => $this->ga4Data,
            'gscData'              => $this->gscData,
            'youtubeData'          => $this->youtubeData,
            'keywordData'          => $this->keywordData,
            'gtmData'              => $this->gtmData,
            'gbpData'              => $this->gbpData,
            'gadsData'             => $this->gadsData,
            'facebookData'         => $this->facebookData,
            'linkedinData'         => $this->linkedinData,
            'showAiMarketingReport'=> $this->showAiMarketingReport,
            'aiReport'             => $this->aiReport,
            'aiGeneratedHtml'      => $this->aiGeneratedHtml,
            'showAiHtmlReport'     => $this->showAiHtmlReport,
            'showAiReportPanel'    => $this->showAiReportPanel,
            'aiError'              => $this->aiError,
            'aiPrompt'             => $this->aiPrompt,
            'isGeneratingAiReport' => $this->isGeneratingAiReport,
            'aiTabs'               => $this->aiTabs,
            'activeAiTab'          => $this->activeAiTab,
            'aiReportsByPlatform'  => $this->aiReportsByPlatform,
        ])->layout($layout);
    }
}
