<?php

namespace App\Modules\CRM\Reports\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Support\Facades\Log;

#[\Livewire\Attributes\Title('Marketing Reports')]
class ManageMarketingReports extends Component
{
    #[\Livewire\Attributes\Url]
    public $clientId = null;
    
    #[\Livewire\Attributes\Url]
    public $websiteId = null;
    
    public $activeTab = null;

    public $compareDateFrom = '';
    
    public $compareDateTo = '';

    public function mount()
    {
        if (empty($this->clientId)) {
            $this->compareDateFrom = '';
            $this->compareDateTo = '';
        }

        if ($this->websiteId && empty($this->activeTab)) {
            $website = Website::find($this->websiteId);
            if ($website && $website->integrations()->count() > 0) {
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
                $firstIntegration = $website->integrations->sortBy(function($intg) use ($names) {
                    return $names[$intg->integration_type] ?? ucfirst($intg->integration_type);
                })->first();
                
                if ($firstIntegration) {
                    $this->activeTab = $firstIntegration->integration_type;
                }
            }
        }
    }

    public function updatedClientId($value)
    {
        $this->websiteId = null;
        $this->activeTab = null;
        $this->js("
            let u = new URL(window.location.href); 
            u.searchParams.delete('compareDateFrom'); 
            u.searchParams.delete('compareDateTo'); 
            u.searchParams.delete('cfrom'); 
            u.searchParams.delete('cto'); 
            window.history.replaceState({}, '', u.toString());
        ");
        
        if ($value) {
            $firstWebsite = Website::where('client_id', $value)->where('status', 'active')->whereHas('integrations')->first();
            if ($firstWebsite) {
                $this->websiteId = $firstWebsite->id;
                $this->updatedWebsiteId($this->websiteId);
            }
        }
    }

    public function updatedWebsiteId($value)
    {
        $this->activeTab = null;
        $this->js("
            let u = new URL(window.location.href); 
            u.searchParams.delete('compareDateFrom'); 
            u.searchParams.delete('compareDateTo'); 
            u.searchParams.delete('cfrom'); 
            u.searchParams.delete('cto'); 
            window.history.replaceState({}, '', u.toString());
        ");
        
        if ($value) {
            $website = Website::find($value);
            if ($website && $website->integrations()->count() > 0) {
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
                $firstIntegration = $website->integrations->sortBy(function($intg) use ($names) {
                    return $names[$intg->integration_type] ?? ucfirst($intg->integration_type);
                })->first();
                
                if ($firstIntegration) {
                    $this->activeTab = $firstIntegration->integration_type;
                }
            }
        }
    }

    public function resetSelection()
    {
        $this->clientId = null;
        $this->websiteId = null;
        $this->activeTab = null;
        $this->js("
            let u = new URL(window.location.href); 
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
        
        $this->datePreset = 'last_30';
        $this->dateFrom = \Carbon\Carbon::now()->subDays(29)->format('Y-m-d');
        $this->dateTo = \Carbon\Carbon::now()->format('Y-m-d');
        
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
                $integrations = $website->integrations->sortBy(function($intg) use ($names) {
                    return $names[$intg->integration_type] ?? ucfirst($intg->integration_type);
                });
            }
        }

        $layout = request()->is('staffadspnl*') ? 'layouts.staff' : 'layouts.admin';
        return view('modules.crm.reports.livewire.manage-marketing-reports', [
            'clients' => $clients,
            'websites' => $websites,
            'integrations' => collect($integrations)
        ])->layout($layout);
    }
}
