<?php

namespace App\Modules\CRM\Clients\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Traits\LoadsMarketingReports;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

#[Layout('layouts.admin')]
class AdminClientReport extends Component
{
    use LoadsMarketingReports;

    public int $clientId = 0;
    #[Url]
    public int $websiteId = 0;
    public string $integration = '';

    public array $activeReportData = [];
    public string $activeReportIntegrationId = '';
    public string $activeReportPropertyId = '';

    public ?Client $client = null;
    public ?Website $website = null;
    public string $clientName = '';
    public string $websiteName = '';

    public function mount(int $id, string $integration): void
    {
        $this->clientId = $id;
        $this->integration = $integration;
        $this->activeReportIntegrationId = $integration;
        $this->websiteId = (int) request('website', 0);

        // Dates are handled by #[Url] now, but we can keep defaults if empty
        if (empty($this->dateFrom)) $this->dateFrom = request('from', Carbon::now()->subDays(28)->format('Y-m-d'));
        if (empty($this->dateTo)) $this->dateTo = request('to', Carbon::now()->subDays(1)->format('Y-m-d'));
        if (empty($this->compareDateFrom)) $this->compareDateFrom = request('cfrom', '');
        if (empty($this->compareDateTo)) $this->compareDateTo = request('cto', '');
        if (empty($this->compareFormat)) $this->compareFormat = request('format', 'percentage');

        $this->loadReport();
    }

    public function updatedDateFrom(): void { $this->loadReport(); }
    public function updatedDateTo(): void { $this->loadReport(); }
    public function updatedCompareDateFrom(): void { $this->loadReport(); }
    public function updatedCompareDateTo(): void { $this->loadReport(); }
    public function updatedCompareFormat(): void { } // re-render only

    public function loadReport(): void
    {
        try {
            $this->client = Client::with('user')->findOrFail($this->clientId);
            $this->clientName = $this->client->user->name ?? 'Client';

            if ($this->websiteId) {
                $this->website = Website::where('client_id', $this->clientId)->where('id', $this->websiteId)->first();
            }
            if (!$this->website) {
                $websites = Website::where('client_id', $this->clientId)->get();
                $this->website = $websites->first();
            }

            if ($this->website) {
                $this->websiteName = $this->website->site_name ?? '';
                $integrations = json_decode($this->website->integrations ?? '[]', true);
                foreach ($integrations as $intg) {
                    if (($intg['id'] ?? '') === $this->integration && !empty($intg['property_id'])) {
                        $this->activeReportPropertyId = $intg['property_id'] ?? '';
                        break;
                    }
                }
            }

            if (!$this->website) return;

            $userName = Str::slug(Str::lower($this->client->user->name ?? 'client'));
            $emailParts = explode('@', $this->client->user->email ?? '');
            $emailPrefix = Str::slug(Str::lower($emailParts[0] ?? ''));
            $clientFolder = "{$userName}-{$emailPrefix}";

            $websiteFolder = Str::slug(Str::lower($this->website->site_name ?? ''));
            if (empty($websiteFolder)) {
                $websiteFolder = 'site-' . $this->website->id;
            }

            $this->activeReportData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, $this->integration, $this->dateFrom, $this->dateTo);

            if (!empty($this->compareDateFrom) && !empty($this->compareDateTo)) {
                $compareData = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, $this->integration, $this->compareDateFrom, $this->compareDateTo);
                $this->activeReportData['compare_summary'] = $compareData['overall_summary'] ?? ($compareData['summary'] ?? []);
                $this->activeReportData['compare_data'] = $compareData;
            }

            $this->syncDefaultReportModalData();

        } catch (\Exception $e) {
            Log::error('AdminClientReport: Error loading report: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('modules.crm.clients.client-report', [
            'client' => $this->client,
            'website' => $this->website,
            'pageTitle' => $this->clientName . ' — ' . strtoupper($this->integration) . ' Report',
        ]);
    }
}
