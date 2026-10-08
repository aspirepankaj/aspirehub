<?php

namespace App\Modules\CRM\Reports\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Support\Facades\Log;

class ManageMarketingReports extends Component
{
    #[\Livewire\Attributes\Url]
    public $clientId = null;
    
    #[\Livewire\Attributes\Url]
    public $websiteId = null;
    
    #[\Livewire\Attributes\Url]
    public $activeTab = null;

    #[\Livewire\Attributes\Url(except: '')]
    public $compareDateFrom = '';
    
    #[\Livewire\Attributes\Url(except: '')]
    public $compareDateTo = '';

    public function mount()
    {
        if (empty($this->clientId)) {
            $this->compareDateFrom = '';
            $this->compareDateTo = '';
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
            $firstWebsite = Website::where('client_id', $value)->first();
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
                $this->activeTab = $website->integrations()->first()->integration_type;
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
        $this->js("
            let u = new URL(window.location.href); 
            u.searchParams.delete('compareDateFrom'); 
            u.searchParams.delete('compareDateTo'); 
            u.searchParams.delete('cfrom'); 
            u.searchParams.delete('cto'); 
            window.history.replaceState({}, '', u.toString());
        ");
    }

    public function render()
    {
        if (request()->is('staffadspnl*') && auth()->user() && auth()->user()->staff) {
            $clients = auth()->user()->staff->clients()->where('status', 'active')->with(['user', 'websites'])->get();
        } else {
            $clients = Client::where('status', 'active')->with(['user', 'websites'])->get();
        }
        $websites = [];
        $integrations = collect();

        if ($this->clientId) {
            $websites = Website::where('client_id', $this->clientId)->where('status', 'active')->get();
        }

        if ($this->websiteId) {
            $website = Website::find($this->websiteId);
            if ($website) {
                $integrations = $website->integrations;
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
