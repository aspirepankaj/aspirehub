<?php

namespace App\Modules\CRM\Clients\Livewire;

use Livewire\Component;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Support\Facades\Log;

use App\Traits\LoadsMarketingReports;
use Illuminate\Support\Str;

class AdminClientMarketingTab extends Component
{
    use LoadsMarketingReports;

    public int $clientId = 0;
    public ?int $selectedWebsiteId = null;
    public string $activeTab = 'overview';

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

    public function mount(int $clientId): void
    {
        $this->clientId = $clientId;
        $this->initDateRange();

        $client = Client::find($this->clientId);
        if (!$client) {
            return;
        }

        $websites = Website::where('client_id', $client->id)
            ->where('status', 'active')
            ->orderBy('site_name')
            ->get();

        if ($websites->isNotEmpty()) {
            $this->selectedWebsiteId = $websites->first()->id;
        }

        $tab = request()->query('tab');
        if ($tab && $tab !== 'marketing' && in_array($tab, ['overview', 'ga4', 'gsc', 'youtube', 'keyword', 'gbp', 'gads', 'facebook', 'linkedin', 'gtm'])) {
            $this->activeTab = $tab;
        } else {
            $this->activeTab = 'overview';
        }
    }

    public function updatedSelectedWebsiteId(): void
    {
        $this->activeTab = 'overview';
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;

        $this->datePreset = 'last_28';
        $this->dateFrom = \Carbon\Carbon::now()->subDays(28)->format('Y-m-d');
        $this->dateTo = \Carbon\Carbon::now()->subDays(1)->format('Y-m-d');
        $this->compareDateFrom = '';
        $this->compareDateTo = '';
        $this->compareEnabled = false;

        session()->forget([
            'marketing_date_from',
            'marketing_date_to',
            'marketing_compare_date_from',
            'marketing_compare_date_to',
            'marketing_compare_format',
            'marketing_date_preset'
        ]);

        $this->js("
            let u = new URL(window.location.href); 
            u.searchParams.delete('compareDateFrom'); 
            u.searchParams.delete('compareDateTo'); 
            u.searchParams.delete('cfrom'); 
            u.searchParams.delete('cto'); 
            u.searchParams.delete('dateFrom'); 
            u.searchParams.delete('dateTo'); 
            u.searchParams.delete('datePreset'); 
            window.history.replaceState({}, '', u.toString());
        ");
    }

    public function selectIntegration(string $tab): void
    {
        $this->switchTab($tab);
    }

    private function initActiveTab(): void
    {
        $this->activeTab = 'overview';
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

        return WebsiteIntegration::where('website_id', $website->id)
            ->where('status', 'connected')
            ->get()
            ->filter(function ($item) {
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

    public function render()
    {
        $websites = Website::where('client_id', $this->clientId)
            ->where('status', 'active')
            ->orderBy('site_name')
            ->get();

        $integrations = collect();
        $website = null;
        if ($this->selectedWebsiteId) {
            $website = Website::find($this->selectedWebsiteId);
            if ($website) {
                $integrations = $this->getValidIntegrations($website);
            }
        }

        // When activeTab is 'overview', load overview summary data for connected integrations
        if ($this->activeTab === 'overview' && $website) {
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

        return view('modules.crm.clients.livewire.admin-client-marketing-tab', [
            'clientId' => $this->clientId,
            'selectedWebsiteId' => $this->selectedWebsiteId,
            'activeTab' => $this->activeTab,
            'websites' => $websites,
            'integrations' => $integrations,
            'ga4Data' => $this->ga4Data,
            'gscData' => $this->gscData,
            'youtubeData' => $this->youtubeData,
            'keywordData' => $this->keywordData,
            'gtmData' => $this->gtmData,
            'gbpData' => $this->gbpData,
            'gadsData' => $this->gadsData,
            'facebookData' => $this->facebookData,
            'linkedinData' => $this->linkedinData,
        ]);
    }
}
