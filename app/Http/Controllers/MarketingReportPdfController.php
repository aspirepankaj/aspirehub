<?php

namespace App\Http\Controllers;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Services\MarketingReportPdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketingReportPdfController extends Controller
{
    protected MarketingReportPdfService $reportService;

    public function __construct(MarketingReportPdfService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Stream PDF in browser for inline preview or iframe.
     */
    public function previewPdf(Request $request, int $clientId, int $websiteId)
    {
        $client = Client::with('user')->findOrFail($clientId);
        $website = Website::where('client_id', $clientId)->findOrFail($websiteId);

        $dateFrom = $request->filled('from') ? $request->query('from') : Carbon::now()->subDays(28)->format('Y-m-d');
        $dateTo = $request->filled('to') ? $request->query('to') : Carbon::now()->subDays(1)->format('Y-m-d');
        $compareDateFrom = $request->filled('cfrom') ? $request->query('cfrom') : null;
        $compareDateTo = $request->filled('cto') ? $request->query('cto') : null;

        $pdf = $this->reportService->generatePdf($client, $website, $dateFrom, $dateTo, $compareDateFrom, $compareDateTo);

        $siteSlug = Str::slug($website->site_name ?: 'Website');
        $dateSlug = Carbon::parse($dateFrom)->format('M-Y');

        return $pdf->stream("Monthly-SEO-Report-{$siteSlug}-{$dateSlug}.pdf");
    }

    /**
     * Download the in-memory PDF without storing on disk.
     */
    public function downloadPdf(Request $request, int $clientId, int $websiteId)
    {
        $client = Client::with('user')->findOrFail($clientId);
        $website = Website::where('client_id', $clientId)->findOrFail($websiteId);

        $dateFrom = $request->filled('from') ? $request->query('from') : Carbon::now()->subDays(28)->format('Y-m-d');
        $dateTo = $request->filled('to') ? $request->query('to') : Carbon::now()->subDays(1)->format('Y-m-d');
        $compareDateFrom = $request->filled('cfrom') ? $request->query('cfrom') : null;
        $compareDateTo = $request->filled('cto') ? $request->query('cto') : null;

        $pdf = $this->reportService->generatePdf($client, $website, $dateFrom, $dateTo, $compareDateFrom, $compareDateTo);

        $siteSlug = Str::slug($website->site_name ?: 'Website');
        $dateSlug = Carbon::parse($dateFrom)->format('M-Y');

        return $pdf->download("Monthly-SEO-Report-{$siteSlug}-{$dateSlug}.pdf");
    }
}
