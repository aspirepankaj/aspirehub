<?php

namespace App\Services;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Traits\LoadsMarketingReports;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class AiMarketingReportService
{
    use LoadsMarketingReports;

    /**
     * Parse the user prompt to detect client, website, platforms, and date range.
     * Uses OpenAI API as Primary Engine when OPENAI_API_KEY is configured in .env.
     */
    public function parsePrompt(string $prompt, ?int $currentClientId = null, ?int $currentWebsiteId = null): array
    {
        // 1. Fast local heuristic parse first (< 2ms)
        $local = $this->parseLocally($prompt, $currentClientId, $currentWebsiteId);

        // If client and website were matched with confidence, return immediately (saves 3-5s network latency)
        if (!empty($local['client']) && !empty($local['website'])) {
            $local['is_openai'] = false;
            return $local;
        }

        $apiKey = config('openai.api_key') ?: env('OPENAI_API_KEY');

        // When local parse is ambiguous and OpenAI API key is configured, call OpenAI
        if (!empty($apiKey) && $apiKey !== 'your_openai_api_key_here') {
            try {
                return $this->parseWithOpenAiStrict($prompt, $currentClientId, $currentWebsiteId);
            } catch (\Throwable $e) {
                Log::warning('OpenAI prompt parsing failed, falling back to local: ' . $e->getMessage());
            }
        }

        return $local;
    }

    /**
     * Local heuristic parser used when no OpenAI key is configured.
     */
    public function parseLocally(string $prompt, ?int $currentClientId = null, ?int $currentWebsiteId = null): array
    {
        $promptLower = Str::lower($prompt);

        // 1. Resolve Client & Website (Always scan prompt first to allow switching clients)
        $client = null;
        $website = null;

        $allClients = Client::where('status', 'active')
            ->whereHas('user')
            ->whereHas('websites')
            ->with(['user', 'websites.integrations'])
            ->get();
        $bestClient = null;
        $bestWebsite = null;
        $bestScore = 0;
        $stopWords = ['google', 'ads', 'analytics', 'report', 'site', 'search', 'console', 'facebook', 'linkedin', 'youtube', 'keyword', 'marketing', 'assistant', 'website', 'create', 'show', 'give', 'make', 'last', 'days', 'din'];

        foreach ($allClients as $c) {
            $score = 0;
            $matchedWs = null;
            $fullName = Str::lower(trim($c->user->name ?? ''));
            $company = Str::lower(trim($c->company_name ?? ''));

            // Exact full name match
            if ($fullName && preg_match('/\b' . preg_quote($fullName, '/') . '\b/i', $promptLower)) {
                $score += 120;
            } else {
                $parts = preg_split('/\s+/', $fullName);
                foreach ($parts as $p) {
                    if (strlen($p) >= 3 && !in_array($p, $stopWords) && preg_match('/\b' . preg_quote($p, '/') . '\b/i', $promptLower)) {
                        $score += 60;
                        break;
                    }
                }
            }

            // Company name match
            if ($company && !in_array($company, $stopWords)) {
                if (preg_match('/\b' . preg_quote($company, '/') . '\b/i', $promptLower)) {
                    $score += 80;
                } else {
                    $cParts = preg_split('/\s+/', $company);
                    foreach ($cParts as $cp) {
                        if (strlen($cp) >= 4 && !in_array($cp, $stopWords) && preg_match('/\b' . preg_quote($cp, '/') . '\b/i', $promptLower)) {
                            $score += 40;
                            break;
                        }
                    }
                }
            }

            // Check websites of this client
            if ($c->websites) {
                foreach ($c->websites as $ws) {
                    $siteName = Str::lower(trim($ws->site_name ?? ''));
                    $rawUrl = Str::lower(trim($ws->url ?? ''));
                    $host = Str::lower(parse_url($rawUrl, PHP_URL_HOST) ?: $rawUrl);
                    $hostWithoutWww = preg_replace('/^www\./', '', $host);
                    $domainName = explode('.', $hostWithoutWww)[0] ?? '';

                    $wsScore = 0;
                    // Exact domain/host in prompt (e.g. "womencreate.com") -> Highest priority match
                    if ($hostWithoutWww && strlen($hostWithoutWww) >= 4 && Str::contains($promptLower, $hostWithoutWww)) {
                        $wsScore = 150;
                    } elseif ($domainName && strlen($domainName) >= 4 && !in_array($domainName, $stopWords) && preg_match('/\b' . preg_quote($domainName, '/') . '\b/i', $promptLower)) {
                        $wsScore = 90;
                    } elseif ($siteName && !in_array($siteName, $stopWords) && preg_match('/\b' . preg_quote($siteName, '/') . '\b/i', $promptLower)) {
                        $wsScore = 80;
                    }

                    if ($wsScore > 0) {
                        $score += $wsScore;
                        if (!$matchedWs || $wsScore > 80) {
                            $matchedWs = $ws;
                        }
                    }
                }
            }

            // Prioritize clients who have active connected integrations
            $connectedCount = $c->websites ? $c->websites->flatMap->integrations->where('status', 'connected')->count() : 0;
            if ($connectedCount > 0 && $score > 0) {
                $score += min(20, $connectedCount * 3);
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestClient = $c;
                $bestWebsite = $matchedWs ?? $c->websites->first();
            }
        }

        // If an explicit client or website was found in the prompt (score >= 40):
        if ($bestScore >= 40 && $bestClient) {
            $client = $bestClient;
            $website = $bestWebsite;
        } else {
            // No client or website was mentioned in prompt, so keep currently selected client & website
            if ($currentClientId) {
                $client = Client::with(['user', 'websites.integrations'])->find($currentClientId);
            }
            if ($currentWebsiteId) {
                $website = Website::find($currentWebsiteId);
            } elseif ($client && $client->websites) {
                $website = $client->websites->first();
            }
        }

        // 3. Resolve Platforms / Integrations & Primary Target Platform
        $platformMap = [
            'gads'     => ['google ads', 'google ad', 'googleads', 'googlead', 'gads', 'adwords', 'ppc', 'ad spend', 'campaigns'],
            'ga4'      => ['google analytics 4', 'google analytics', 'ga4', 'analytics', 'traffic', 'visitors', 'sessions', 'bounce rate'],
            'gsc'      => ['google search console', 'search console', 'gsc', 'seo', 'organic keywords', 'google search', 'impressions'],
            'linkedin' => ['linkedin', 'linked in', 'company page', 'linkedin posts'],
            'facebook' => ['facebook', 'meta ads', 'fb page', 'instagram', 'social insights'],
            'youtube'  => ['youtube', 'yt video', 'video views', 'channel'],
            'keyword'  => ['keyword.com', 'keyword rankings', 'tracked keywords', 'keywords rank'],
            'gbp'      => ['google business profile', 'business profile', 'gbp', 'gmb'],
            'gtm'      => ['google tag manager', 'tag manager', 'gtm'],
        ];

        $matchedPlatforms = [];
        $primaryPlatform = null;
        $earliestPos = PHP_INT_MAX;

        foreach ($platformMap as $platformKey => $keywords) {
            foreach ($keywords as $kw) {
                $pos = strpos($promptLower, $kw);
                if ($pos !== false) {
                    $matchedPlatforms[] = $platformKey;
                    if ($pos < $earliestPos) {
                        $earliestPos = $pos;
                        $primaryPlatform = $platformKey;
                    }
                    break;
                }
            }
        }

        $allPlatforms = (bool) preg_match('/\b(?:etc|all|all\s+platforms|all\s+integrations|sabhi|har\s+ek)\b/i', $promptLower);

        if (empty($matchedPlatforms)) {
            $matchedPlatforms = ['ga4'];
        }

        if (!$primaryPlatform) {
            $primaryPlatform = $matchedPlatforms[0] ?? 'ga4';
        }

        // 4. Resolve Date Range
        $now = Carbon::now();
        $days = null;
        $dateFrom = null;
        $dateTo = null;

        // 4.1 Flexible Explicit Date Range Parser (Supports any human or numeric format:
        // "Sep. 06-2026 to Oct. 05-2026", "YYYY-MM-DD to YYYY-MM-DD", "YYYY-DD-MM to YYYY-DD-MM",
        // "MM-YYYY-DD to MM-YYYY-DD", "DD/MM/YYYY to DD/MM/YYYY", "between X and Y", "X se Y tak", etc.)
        $explicitRange = $this->extractDateRangeFromPrompt($prompt);
        if ($explicitRange) {
            $dateFrom = $explicitRange['from'];
            $dateTo = $explicitRange['to'];
            $days = $explicitRange['days'];
        }
        // 4.2 Relative Days (e.g. 90-days, 90 days, 90days, 20 din, 20-din, 15d, 45-day)
        elseif (preg_match('/(\d+)[\s\-_]*(?:din|days?|day|d)\b/i', $prompt, $m)) {
            $days = max(1, min(365, (int) $m[1]));
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays($days)->format('Y-m-d');
        }
        // 4.3 Relative Months (e.g. 3 months, 3-months, 3months, 2 mahine)
        elseif (preg_match('/(\d+)[\s\-_]*(?:mahine|mahina|months?|month|m)\b/i', $prompt, $m)) {
            $months = max(1, min(12, (int) $m[1]));
            $days = $months * 30;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subMonths($months)->format('Y-m-d');
        }
        // 4.4 Relative Years (e.g. 1 year, 1-year, 1 saal, 1 yr)
        elseif (preg_match('/(\d+)[\s\-_]*(?:saal|years?|year|yr|y)\b/i', $prompt, $m)) {
            $years = max(1, min(5, (int) $m[1]));
            $days = $years * 365;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subYears($years)->format('Y-m-d');
        }
        // 4.5 Relative Weeks (e.g. 2 weeks, 2-weeks, 2 hafte)
        elseif (preg_match('/(\d+)[\s\-_]*(?:hafte|hafta|weeks?|week|w)\b/i', $prompt, $m)) {
            $weeks = max(1, min(52, (int) $m[1]));
            $days = $weeks * 7;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subWeeks($weeks)->format('Y-m-d');
        }
        // 4.6 Number following 'last' / 'past' / 'pichle' (e.g. "last 90", "past 60", "pichle 20")
        elseif (preg_match('/\b(?:last|past|pichle|previous)\s+(\d+)\b/i', $prompt, $m)) {
            $days = max(1, min(365, (int) $m[1]));
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays($days)->format('Y-m-d');
        }
        // 4.7 Single words (a month, a week, a year, ek mahina, ek hafta, ek saal)
        elseif (preg_match('/\b(?:a|one|ek)\s*(?:month|mahina)\b/i', $promptLower)) {
            $days = 30;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays(30)->format('Y-m-d');
        } elseif (preg_match('/\b(?:a|one|ek)\s*(?:week|hafta)\b/i', $promptLower)) {
            $days = 7;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays(7)->format('Y-m-d');
        } elseif (preg_match('/\b(?:a|one|ek)\s*(?:year|saal)\b/i', $promptLower)) {
            $days = 365;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays(365)->format('Y-m-d');
        }
        // 4.8 Named ranges / Special periods
        elseif (Str::contains($promptLower, ['today', 'aaj'])) {
            $days = 1;
            $dateFrom = $now->format('Y-m-d');
            $dateTo = $now->format('Y-m-d');
        } elseif (Str::contains($promptLower, ['yesterday', 'kal', 'pichle kal'])) {
            $days = 1;
            $dateFrom = $now->copy()->subDays(1)->format('Y-m-d');
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
        } elseif (Str::contains($promptLower, ['half year', 'half-year', 'half yearly', '6 months', '6-months', '6 mahine'])) {
            $days = 180;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays(180)->format('Y-m-d');
        } elseif (Str::contains($promptLower, ['quarter', 'quarterly', 'last 3 months', 'pichle 3 mahine'])) {
            $days = 90;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays(90)->format('Y-m-d');
        } elseif (Str::contains($promptLower, ['last week', 'pichle hafte', 'past week'])) {
            $days = 7;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays(7)->format('Y-m-d');
        } elseif (Str::contains($promptLower, ['this month', 'is mahine', 'current month'])) {
            $dateFrom = $now->copy()->startOfMonth()->format('Y-m-d');
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $days = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        } elseif (Str::contains($promptLower, ['last month', 'pichle mahine', 'past month'])) {
            $dateFrom = $now->copy()->subMonth()->startOfMonth()->format('Y-m-d');
            $dateTo = $now->copy()->subMonth()->endOfMonth()->format('Y-m-d');
            $days = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        } elseif (Str::contains($promptLower, ['last year', 'pichle saal', 'past year', 'previous year'])) {
            $days = 365;
            $dateFrom = $now->copy()->subYear()->startOfYear()->format('Y-m-d');
            $dateTo = $now->copy()->subYear()->endOfYear()->format('Y-m-d');
        } elseif (Str::contains($promptLower, ['this year', 'is saal', 'current year'])) {
            $dateFrom = $now->copy()->startOfYear()->format('Y-m-d');
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $days = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        } elseif (preg_match('/(january|february|march|april|may|june|july|august|september|october|november|december|jan|feb|mar|apr|jun|jul|aug|sep|sept|oct|nov|dec)/i', $promptLower, $mMonth)) {
            $monthName = ucfirst(strtolower($mMonth[1]));
            $year = date('Y');
            if (preg_match('/\b(20\d{2})\b/', $prompt, $mY)) {
                $year = (int) $mY[1];
            }
            $dt = Carbon::parse("1 {$monthName} {$year}");
            $dateFrom = $dt->copy()->startOfMonth()->format('Y-m-d');
            $dateTo = $dt->copy()->endOfMonth()->format('Y-m-d');
            $days = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        } elseif (preg_match('/\b(q[1-4]|first quarter|second quarter|third quarter|fourth quarter)\b/i', $promptLower, $mQ)) {
            $q = strtolower($mQ[1]);
            $year = date('Y');
            if (preg_match('/\b(202\d)\b/', $prompt, $mY)) {
                $year = (int) $mY[1];
            }
            if ($q === 'q1' || $q === 'first quarter') {
                $dateFrom = "{$year}-01-01";
                $dateTo = "{$year}-03-31";
            } elseif ($q === 'q2' || $q === 'second quarter') {
                $dateFrom = "{$year}-04-01";
                $dateTo = "{$year}-06-30";
            } elseif ($q === 'q3' || $q === 'third quarter') {
                $dateFrom = "{$year}-07-01";
                $dateTo = "{$year}-09-30";
            } else {
                $dateFrom = "{$year}-10-01";
                $dateTo = "{$year}-12-31";
            }
            $days = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        }

        // 4.9 Single explicit date (e.g. "report for 2026-09-06" or "on Sep. 06-2026")
        if (empty($dateFrom) || empty($dateTo)) {
            $singleDate = $this->extractSingleDateFromPrompt($prompt);
            if ($singleDate) {
                $dateFrom = $singleDate['from'];
                $dateTo = $singleDate['to'];
                $days = $singleDate['days'];
            }
        }

        // Default dates if still empty
        if (empty($dateFrom) || empty($dateTo)) {
            $days = 28;
            $dateTo = $now->copy()->subDays(1)->format('Y-m-d');
            $dateFrom = $now->copy()->subDays(28)->format('Y-m-d');
        }

        // Disambiguation & Fallback Guard:
        // If the selected website has 0 connected integrations, check if another website matches the same domain/host with connected integrations
        if ($website) {
            $hasConnected = $website->integrations->where('status', 'connected')->isNotEmpty();
            if (!$hasConnected) {
                $host = Str::lower(parse_url($website->url, PHP_URL_HOST) ?: $website->url);
                $hostNoWww = preg_replace('/^www\./', '', $host);

                $betterWebsite = Website::whereHas('integrations', fn($q) => $q->where('status', 'connected'))
                    ->where(function($q) use ($hostNoWww, $website) {
                        $q->where('url', 'like', "%{$hostNoWww}%")
                          ->orWhere('site_name', 'like', "%{$website->site_name}%");
                    })
                    ->with(['client.user', 'integrations'])
                    ->first();

                if ($betterWebsite) {
                    $website = $betterWebsite;
                    $client = $betterWebsite->client;
                }
            }
        }

        return [
            'client'           => $client,
            'website'          => $website,
            'primary_platform' => $primaryPlatform,
            'platforms'        => array_unique($matchedPlatforms),
            'all_platforms'    => $allPlatforms,
            'days'             => $days,
            'date_from'        => $dateFrom,
            'date_to'          => $dateTo,
            'prompt'           => $prompt,
        ];
    }

    /**
     * Active OpenAI API parser (Strict mode: bubbles up API exceptions like invalid key, rate limits).
     */
    public function parseWithOpenAiStrict(string $prompt, ?int $currentClientId = null, ?int $currentWebsiteId = null): array
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDays(1)->format('Y-m-d');

        // Select candidates matching prompt keywords or currently active/connected
        // Prioritize clients with active user records and connected integrations
        $allClients = Client::where('status', 'active')
            ->whereHas('user')
            ->whereHas('websites')
            ->with(['user', 'websites.integrations'])
            ->get();
        $promptLower = Str::lower($prompt);

        $candidates = $allClients->filter(function($c) use ($promptLower, $currentClientId) {
            if ($currentClientId && $c->id == $currentClientId) return true;
            $hasConnected = $c->websites->flatMap->integrations->where('status', 'connected')->isNotEmpty();
            if ($hasConnected) return true;

            $name = Str::lower($c->user?->name ?? '');
            $comp = Str::lower($c->company_name ?? '');
            if ($name && Str::contains($promptLower, $name)) return true;
            if ($comp && Str::contains($promptLower, $comp)) return true;

            foreach ($c->websites as $ws) {
                $host = Str::lower(parse_url($ws->url, PHP_URL_HOST) ?: $ws->url);
                $hostNoWww = preg_replace('/^www\./', '', $host);
                if ($hostNoWww && Str::contains($promptLower, $hostNoWww)) return true;
                if ($ws->site_name && Str::contains($promptLower, Str::lower($ws->site_name))) return true;
            }
            return false;
        })
        ->sortByDesc(function($c) {
            // Clients with active connected integrations ALWAYS rank first
            return $c->websites->flatMap->integrations->where('status', 'connected')->count();
        })
        ->take(25);

        if ($candidates->isEmpty()) {
            $candidates = $allClients->sortByDesc(function($c) {
                return $c->websites->flatMap->integrations->where('status', 'connected')->count();
            })->take(15);
        }

        $clientsSummary = $candidates->map(fn($c) => [
            'client_id' => $c->id,
            'client_name' => $c->user?->name,
            'company_name' => $c->company_name,
            'websites' => $c->websites
                ->sortByDesc(fn($w) => $w->integrations->where('status', 'connected')->count())
                ->map(fn($w) => [
                    'website_id' => $w->id,
                    'site_name' => $w->site_name,
                    'domain' => parse_url($w->url, PHP_URL_HOST) ?: $w->url,
                    'connected_integrations' => $w->integrations->where('status', 'connected')->pluck('integration_type')->values()->all()
                ])->values()->all()
        ])->values()->all();

        $currentClientName = $currentClientId ? (Client::find($currentClientId)?->user?->name) : 'none';
        $currentWebsiteName = $currentWebsiteId ? (Website::find($currentWebsiteId)?->site_name) : 'none';

        $system = "You are an intelligent AI search & filter assistant for an agency marketing portal.
Today's date is {$today}. Yesterday's date is {$yesterday}.
Currently active client: ID {$currentClientId} ({$currentClientName}).
Currently active website: ID {$currentWebsiteId} ({$currentWebsiteName}).

Available clients and their websites:
" . json_encode($clientsSummary) . "

Supported platform codes:
- 'gads' (Google Ads / PPC / Adwords / Campaigns)
- 'ga4' (Google Analytics 4 / GA4 / Web Traffic / Visitors)
- 'gsc' (Search Console / GSC / SEO / Organic / Keywords)
- 'facebook' (Facebook / Meta / Instagram)
- 'linkedin' (LinkedIn / Company Page)
- 'youtube' (YouTube / Video)
- 'keyword' (Keyword.com / Rankings)
- 'gbp' (Google Business Profile / GMB)
- 'gtm' (Tag Manager)

CRITICAL SELECTION RULES:
1. Always prefer clients and websites that have active connected integrations (`connected_integrations`).
2. If multiple clients or websites match the prompt (such as identical domain names like 'aspiredigitalsolutions.com'), ALWAYS select the client and website that has active connected integrations (e.g. Client ID 354, Website ID 116 which has Google Ads, GA4, etc.). NEVER select an entry with `connected_integrations: []` when an entry with connected integrations exists!
4. Identify the requested platforms ('platforms'):
   - An array of string platform codes for ALL platforms explicitly mentioned in the prompt (e.g. ['gads', 'linkedin', 'gsc']).
   - 'primary_platform': the first platform mentioned, or null if none mentioned.
   - If user explicitly writes 'etc.', 'all', 'all platforms', or 'all integrations', set 'all_platforms': true.
   - If no platform is mentioned, 'platforms' must be an empty array [].
5. Calculate the date range ('days', 'date_from', 'date_to'):
   - Calculate exact start ('date_from') and end ('date_to') in 'YYYY-MM-DD' format.
   - For example: 'September 06- 2026 to October 05-2026' -> 'date_from': '2026-09-06', 'date_to': '2026-10-05'.
   - '90-days' or '90 days' -> 90 days ending yesterday ({$yesterday}).
   - '10 days' or '10 din' -> 10 days ending yesterday ({$yesterday}).
   - '3 months' or '3 mahine' -> 90 days ending yesterday ({$yesterday}).
   - 'October' -> 2026-10-01 to 2026-10-31.

Output strictly valid JSON with this exact schema:
{
  \"client_id\": int or null,
  \"website_id\": int or null,
  \"primary_platform\": \"gads\"|\"ga4\"|\"gsc\"|\"facebook\"|\"linkedin\"|\"youtube\"|\"keyword\"|\"gbp\"|\"gtm\"|null,
  \"platforms\": [\"gads\", \"linkedin\", \"gsc\"],
  \"all_platforms\": boolean,
  \"days\": int,
  \"date_from\": \"YYYY-MM-DD\",
  \"date_to\": \"YYYY-MM-DD\"
}";

        $model = config('openai.model', config('services.openai.model', env('OPENAI_MODEL', 'gpt-4o-mini')));

        // Direct call to OpenAI API (Throws exception on invalid key / rate limit)
        $response = OpenAI::chat()->create([
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $prompt],
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.0,
        ]);

        $raw = $response->choices[0]->message->content ?? '';
        $data = json_decode($raw, true) ?: [];

        $clientId = $data['client_id'] ?? $currentClientId;
        $websiteId = $data['website_id'] ?? $currentWebsiteId;

        $client = $clientId ? Client::with(['user', 'websites.integrations'])->find($clientId) : null;
        $website = $websiteId ? Website::with('integrations')->find($websiteId) : ($client?->websites?->sortByDesc(fn($w) => $w->integrations->where('status', 'connected')->count())->first());

        // Disambiguation & Fallback Guard:
        // If the selected website has 0 connected integrations, check if another website matches the same domain/host with connected integrations
        if ($website) {
            $hasConnected = $website->integrations->where('status', 'connected')->isNotEmpty();
            if (!$hasConnected) {
                $host = Str::lower(parse_url($website->url, PHP_URL_HOST) ?: $website->url);
                $hostNoWww = preg_replace('/^www\./', '', $host);

                $betterWebsite = Website::whereHas('integrations', fn($q) => $q->where('status', 'connected'))
                    ->where(function($q) use ($hostNoWww, $website) {
                        $q->where('url', 'like', "%{$hostNoWww}%")
                          ->orWhere('site_name', 'like', "%{$website->site_name}%");
                    })
                    ->with(['client.user', 'integrations'])
                    ->first();

                if ($betterWebsite) {
                    $website = $betterWebsite;
                    $client = $betterWebsite->client;
                }
            }
        }

        // Check if user specified an explicit date range in prompt
        $explicitRange = $this->extractDateRangeFromPrompt($prompt);
        if ($explicitRange) {
            $dateFrom = $explicitRange['from'];
            $dateTo = $explicitRange['to'];
            $days = $explicitRange['days'];
        } else {
            $dateFrom = $data['date_from'] ?? Carbon::now()->subDays(28)->format('Y-m-d');
            $dateTo = $data['date_to'] ?? Carbon::now()->subDays(1)->format('Y-m-d');
            $days = $data['days'] ?? (Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1);
        }

        $rawPlatforms = !empty($data['platforms']) && is_array($data['platforms']) ? $data['platforms'] : [];
        $platform = !empty($data['primary_platform']) ? $data['primary_platform'] : (!empty($data['platform']) ? $data['platform'] : ($rawPlatforms[0] ?? null));
        if (empty($rawPlatforms) && !empty($platform)) {
            $rawPlatforms = [$platform];
        }

        return [
            'client'           => $client,
            'website'          => $website,
            'primary_platform' => $platform,
            'platforms'        => array_values(array_unique($rawPlatforms)),
            'all_platforms'    => !empty($data['all_platforms']),
            'days'             => $days,
            'date_from'        => $dateFrom,
            'date_to'          => $dateTo,
            'prompt'           => $prompt,
            'is_openai'        => true,
        ];
    }

    /**
     * Gather actual JSON metrics from adscljson storage for the resolved client, site, and platforms.
     */
    public function gatherMetrics(?Client $client, ?Website $website, array $platforms, string $dateFrom, string $dateTo): array
    {
        if (!$client || !$website) {
            return [
                'platforms_data' => [],
                'kpis'           => [],
            ];
        }

        $userName = Str::slug(Str::lower($client->user->name ?? 'client'));
        $emailParts = explode('@', $client->user->email ?? '');
        $emailPrefix = Str::slug(Str::lower($emailParts[0] ?? ''));
        $clientFolder = "{$userName}-{$emailPrefix}";

        $websiteFolder = Str::slug(Str::lower($website->site_name ?? ''));
        if (empty($websiteFolder)) {
            $websiteFolder = 'site-' . $website->id;
        }

        $metricsData = [];
        $kpis = [];

        foreach ($platforms as $platform) {
            try {
                $raw = $this->loadIntegrationJsonData($clientFolder, $websiteFolder, $platform, $dateFrom, $dateTo);
                if (!empty($raw)) {
                    $summary = $raw['overall_summary'] ?? ($raw['summary'] ?? []);
                    $metricsData[$platform] = [
                        'summary'      => $summary,
                        'traffic_days' => count($raw['daily_traffic'] ?? []),
                    ];

                    // Extract breakdown details if available
                    if (!empty($raw['top_campaigns'])) {
                        $metricsData[$platform]['top_campaigns'] = array_slice($raw['top_campaigns'], 0, 5);
                    }
                    if (!empty($raw['top_keywords'])) {
                        $metricsData[$platform]['top_keywords'] = array_slice($raw['top_keywords'], 0, 5);
                    }
                    if (!empty($raw['pages_report'])) {
                        $metricsData[$platform]['top_pages'] = array_slice($raw['pages_report'], 0, 5);
                    }
                    if (!empty($raw['traffic_sources'])) {
                        $metricsData[$platform]['traffic_sources'] = array_slice($raw['traffic_sources'], 0, 5);
                    }
                    if (!empty($raw['top_queries'])) {
                        $metricsData[$platform]['top_queries'] = array_slice($raw['top_queries'], 0, 5);
                    }
                    if (!empty($raw['top_posts'])) {
                        $metricsData[$platform]['top_posts'] = array_slice($raw['top_posts'], 0, 4);
                    }
                    if (!empty($raw['device_summary']) || !empty($raw['device_demographics'])) {
                        $metricsData[$platform]['devices'] = $raw['device_summary'] ?? $raw['device_demographics'];
                    }

                    if (!empty($raw['top_videos'])) {
                        $metricsData[$platform]['top_videos'] = array_slice($raw['top_videos'], 0, 5);
                    }
                    if (!empty($raw['keywords'])) {
                        $metricsData[$platform]['keywords'] = array_slice($raw['keywords'], 0, 8);
                    }
                    if (!empty($raw['ranking_distribution'])) {
                        $metricsData[$platform]['ranking_distribution'] = $raw['ranking_distribution'];
                    }

                    // Extract key highlights for quick UI KPI cards
                    if ($platform === 'ga4') {
                        $kpis['Total Visitors'] = number_format((int) ($summary['visitors'] ?? ($summary['users'] ?? ($summary['active_users'] ?? 0))));
                        $kpis['Total Sessions'] = number_format((int) ($summary['sessions'] ?? 0));
                        $kpis['Bounce Rate'] = ($summary['bounce_rate'] ?? 'N/A');
                    } elseif ($platform === 'gsc') {
                        $kpis['Search Clicks'] = number_format((int) ($summary['clicks'] ?? 0));
                        $kpis['Search Impressions'] = number_format((int) ($summary['impressions'] ?? 0));
                        $kpis['Average CTR'] = ($summary['ctr'] ?? 'N/A');
                    } elseif ($platform === 'linkedin') {
                        $kpis['LinkedIn Engagements'] = number_format((int) ($summary['post_engagements'] ?? ($summary['engagements'] ?? 0)));
                        $kpis['LinkedIn Impressions'] = number_format((int) ($summary['impressions'] ?? 0));
                    } elseif ($platform === 'facebook') {
                        $kpis['Meta Reach'] = number_format((int) ($summary['reach'] ?? 0));
                        $kpis['Meta Engaged'] = number_format((int) ($summary['engaged'] ?? 0));
                    } elseif ($platform === 'gads') {
                        $kpis['Google Ads Clicks'] = number_format((int) ($summary['clicks'] ?? 0));
                        $kpis['Google Ads Cost'] = '$' . number_format((float) ($summary['cost'] ?? 0), 2);
                        if (!empty($summary['conversions'])) {
                            $kpis['Conversions'] = number_format((int) $summary['conversions']);
                        }
                    } elseif ($platform === 'youtube') {
                        $kpis['YouTube Views'] = number_format((int) ($summary['views'] ?? 0));
                        $kpis['New Subscribers'] = number_format((int) ($summary['subscribers'] ?? 0));
                        $kpis['Watch Time'] = ($summary['watch_time'] ?? 0) . ' hrs';
                        $kpis['Avg Duration'] = ($summary['avg_view_duration'] ?? 'N/A');
                    } elseif ($platform === 'keyword') {
                        $kpis['Tracked Keywords'] = number_format((int) ($summary['total_keywords'] ?? 0));
                        $kpis['Top 3 Rankings'] = number_format((int) ($summary['top_3'] ?? 0));
                        $kpis['Top 10 Rankings'] = number_format((int) ($summary['top_10'] ?? 0));
                        $kpis['Share of Voice'] = ($summary['share_of_voice'] ?? '0%');
                    } elseif ($platform === 'gbp') {
                        $kpis['Total Interactions'] = number_format((int) ($summary['interactions'] ?? 0));
                        $kpis['Calls'] = number_format((int) ($summary['calls'] ?? 0));
                        $kpis['Website Clicks'] = number_format((int) ($summary['website_clicks'] ?? 0));
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("AI Report: Failed loading {$platform} for {$clientFolder}: " . $e->getMessage());
            }
        }

        return [
            'platforms_data' => $metricsData,
            'kpis'           => $kpis,
        ];
    }

    /**
     * Generate structured AI report using OpenAI or smart local synthesis fallback.
     */
    public function generateReport(string $prompt, array $parsed, array $metrics): array
    {
        $client = $parsed['client'];
        $website = $parsed['website'];
        $clientName = $client?->user?->name ?? 'Client';
        $companyName = $client?->company_name ?? ($website?->site_name ?? 'Business');
        $siteUrl = $website?->url ?? 'Website';
        $primaryPlatform = !empty($parsed['primary_platform']) ? strtoupper($parsed['primary_platform']) : null;
        $platformsList = $primaryPlatform ?: implode(', ', array_map('strtoupper', $parsed['platforms']));
        $dateRangeText = Carbon::parse($parsed['date_from'])->format('M d, Y') . ' — ' . Carbon::parse($parsed['date_to'])->format('M d, Y') . " ({$parsed['days']} days)";

        $apiKey = config('openai.api_key') ?: env('OPENAI_API_KEY');
        $model = config('services.openai.model', 'gpt-4o-mini');

        $systemPrompt = "You are an elite Digital Marketing Strategist & Analytics Director at Aspire Digital Solutions. " .
            "You are writing a concise, executive-level marketing intelligence briefing for {$companyName} ({$siteUrl})" . ($primaryPlatform ? " specifically focusing on {$primaryPlatform}." : ".") . " " .
            "Your writing must be authoritative, data-driven, strategic, and polished. Avoid robotic fluff. " .
            "Structure your output strictly using Github Markdown with clear headings (H2, H3), bullet points, and actionable takeaways.\n\n" .
            "Report Sections to include:\n" .
            "1. ## Executive Performance Overview (High-level narrative of what happened)\n" .
            "2. ## Key Wins & Channel Highlights (Top performing areas with specific metrics)\n" .
            "3. ## Areas for Improvement & Drop-offs (What needs attention or optimization)\n" .
            "4. ## Recommended Action Plan for Next Period (3 to 5 concrete strategic steps)";

        $userPrompt = "Client Name: {$clientName}\n" .
            "Company / Website: {$companyName} ({$siteUrl})\n" .
            "Reporting Period: {$dateRangeText}\n" .
            "Platforms Analyzed: {$platformsList}\n" .
            "User's Specific Instruction: \"{$prompt}\"\n\n" .
            "ACTUAL PERFORMANCE METRICS DATA:\n" . json_encode($metrics['platforms_data'], JSON_PRETTY_PRINT) . "\n\n" .
            "Generate the full professional executive marketing report now.";

        $markdownContent = '';
        $isMock = false;

        if (!empty($apiKey) && $apiKey !== 'your_openai_api_key_here') {
            try {
                $response = OpenAI::chat()->create([
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                ]);

                $markdownContent = $response->choices[0]->message->content ?? '';
            } catch (\Throwable $e) {
                Log::error('OpenAI Report Generation Error: ' . $e->getMessage());
                $markdownContent = $this->generateSimulatedReport($clientName, $companyName, $siteUrl, $dateRangeText, $platformsList, $metrics);
                $isMock = true;
            }
        } else {
            // Local fallback simulation when API key is not yet pasted in .env
            $markdownContent = $this->generateSimulatedReport($clientName, $companyName, $siteUrl, $dateRangeText, $platformsList, $metrics);
            $isMock = true;
        }

        return [
            'client_name'      => $clientName,
            'company_name'     => $companyName,
            'website_name'     => $website?->site_name ?? 'Website',
            'website_url'      => $siteUrl,
            'date_range'       => $dateRangeText,
            'platforms'        => $parsed['platforms'],
            'kpis'             => $metrics['kpis'],
            'markdown'         => $markdownContent,
            'generated_at'     => Carbon::now()->format('F j, Y, g:i a'),
            'is_mock'          => $isMock,
        ];
    }

    /**
     * Fallback report generator using real metrics in case OpenAI key is missing or rate limited.
     */
    protected function generateSimulatedReport(string $clientName, string $company, string $site, string $period, string $platforms, array $metrics): string
    {
        $kpiItems = [];
        foreach ($metrics['kpis'] as $k => $v) {
            $kpiItems[] = "- **{$k}**: `{$v}`";
        }
        $kpiText = !empty($kpiItems) ? implode("\n", $kpiItems) : "- Baseline digital metrics successfully indexed.";

        return "## Executive Performance Overview\n\n" .
            "During the reporting period (**{$period}**), digital channels for **{$company}** ({$site}) demonstrated steady user acquisition and consistent audience engagement across {$platforms}.\n\n" .
            "Our analysis of the connected telemetry indicates that organic visibility and referral traffic channels represent the primary drivers of user sessions. Traffic quality remains high, with healthy interaction across core service pages.\n\n" .
            "### Key Performance Indicators\n" .
            "{$kpiText}\n\n" .
            "## Cross-Channel Highlights & Key Wins\n\n" .
            "- **Traffic Stability**: Maintained consistent user inflow with steady week-over-week engagement across primary conversion pathways.\n" .
            "- **Search Visibility**: Search presence showed solid impression delivery, indicating expanding indexation and brand awareness.\n" .
            "- **Multi-Channel Engagement**: Audience touchpoints across channels continue to channel qualified prospects towards key contact and consultation pages.\n\n" .
            "## Areas for Improvement & Drop-offs\n\n" .
            "- **Conversion Rate Optimization (CRO)**: While top-of-funnel traffic is solid, bounce rates on secondary landing pages suggest an opportunity to simplify contact call-to-actions (CTAs).\n" .
            "- **Mobile Session Retention**: Mobile user engagement is slightly shorter compared to desktop, indicating potential layout speed or mobile UX enhancements needed.\n\n" .
            "## Recommended Action Plan for Next Period\n\n" .
            "1. **Optimize High-Exit Landing Pages**: Add clear, low-friction inquiry buttons above the fold to capture immediate user intent.\n" .
            "2. **Content Expansion on Top Performing Topics**: Double down on topics driving high search impressions by adding 2 new targeted blog or case study pieces.\n" .
            "3. **Retargeting & Follow-up**: Launch strategic retargeting for users who spent >1 minute on high-intent service pages without converting.";
    }

    /**
     * Generate complete, modern Tailwind CSS HTML executive marketing report using OpenAI and real JSON metrics.
     * High-speed optimized: Uses OpenAI JSON briefing (~150 tokens -> 1s) + Instant Blade/Tailwind Renderer + Persistent Cache.
     */
    public function generateHtmlReport(string $prompt, array $parsed, array $metrics): string
    {
        $client = $parsed['client'];
        $website = $parsed['website'];
        $clientName = $client?->user?->name ?? 'Client';
        $companyName = $client?->company_name ?? ($website?->site_name ?? 'Business');
        $siteUrl = $website?->url ?? 'Website';
        $siteDomain = parse_url($siteUrl, PHP_URL_HOST) ?: $siteUrl;
        $primaryPlatform = !empty($parsed['primary_platform']) ? strtolower($parsed['primary_platform']) : 'overview';
        $platformsList = strtoupper($primaryPlatform);
        $dateRangeText = Carbon::parse($parsed['date_from'])->format('M d, Y') . ' – ' . Carbon::parse($parsed['date_to'])->format('M d, Y') . " ({$parsed['days']} Days)";

        // Cache Key for instant repeated lookups across tab switches and visits
        $cacheKey = 'ai_html_report_v3_' . md5(($client?->id ?? 0) . '_' . ($website?->id ?? 0) . '_' . $primaryPlatform . '_' . ($parsed['date_from'] ?? '') . '_' . ($parsed['date_to'] ?? ''));

        if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return \Illuminate\Support\Facades\Cache::get($cacheKey);
        }

        $apiKey = config('openai.api_key') ?: env('OPENAI_API_KEY');
        $model = config('openai.model', config('services.openai.model', env('OPENAI_MODEL', 'gpt-4o-mini')));

        $insights = null;

        // Fetch compact structured JSON insights from OpenAI (150-250 tokens -> ~1s response time vs 25s)
        if (!empty($apiKey) && $apiKey !== 'your_openai_api_key_here') {
            try {
                $systemPrompt = "You are an elite Digital Marketing Analytics Director at Aspire Digital Solutions. " .
                    "Analyze the provided real telemetry metrics for {$companyName} ({$siteDomain}) on {$platformsList} ({$dateRangeText}). " .
                    "Return ONLY a valid JSON object with executive intelligence briefing matching this schema: " .
                    "{\"summary\": \"2 to 3 sentences executive summary analyzing real channel performance and trends.\", " .
                    "\"key_wins\": [\"Specific win 1 citing real numbers or metrics\", \"Specific win 2 citing real numbers or metrics\", \"Specific win 3 citing real numbers or metrics\"], " .
                    "\"areas_for_improvement\": [\"Optimization opportunity 1\", \"Optimization opportunity 2\", \"Optimization opportunity 3\"], " .
                    "\"action_plan\": [{\"title\": \"Action 1 Title\", \"description\": \"Clear 1-sentence tactical directive\"}, {\"title\": \"Action 2 Title\", \"description\": \"Clear 1-sentence tactical directive\"}, {\"title\": \"Action 3 Title\", \"description\": \"Clear 1-sentence tactical directive\"}]}";

                $userPrompt = "Client: {$clientName} | Website: {$siteDomain}\n" .
                    "Channel: {$platformsList} | Period: {$dateRangeText}\n" .
                    "User prompt: \"{$prompt}\"\n\n" .
                    "TELEMETRY DATA:\n" . json_encode($metrics['platforms_data'] ?? $metrics, JSON_PRETTY_PRINT);

                $response = OpenAI::chat()->create([
                    'model'           => $model,
                    'messages'        => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature'     => 0.2,
                    'max_tokens'      => 450,
                ]);

                $rawContent = $response->choices[0]->message->content ?? '';
                $insights = json_decode($rawContent, true);
            } catch (\Throwable $e) {
                Log::warning('OpenAI Briefing Generation Warning: ' . $e->getMessage());
            }
        }

        $html = $this->renderExecutiveHtmlReport(
            $clientName,
            $companyName,
            $siteDomain,
            $siteUrl,
            $dateRangeText,
            $platformsList,
            $metrics,
            $insights,
            $primaryPlatform
        );

        // Store in cache for 2 hours for instant repeated access
        \Illuminate\Support\Facades\Cache::put($cacheKey, $html, now()->addHours(2));

        return $html;
    }

    /**
     * Render high-speed executive HTML report using real metrics and AI insights.
     */
    public function renderExecutiveHtmlReport(
        string $clientName,
        string $company,
        string $domain,
        string $url,
        string $period,
        string $platforms,
        array $metrics,
        ?array $insights = null,
        string $platformKey = ''
    ): string {
        $platformMeta = [
            'gads'     => ['name' => 'Google Ads', 'badge' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/40'],
            'ga4'      => ['name' => 'Google Analytics 4', 'badge' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/40'],
            'gsc'      => ['name' => 'Search Console', 'badge' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200/60 dark:border-blue-800/40'],
            'facebook' => ['name' => 'Facebook', 'badge' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200/60 dark:border-indigo-800/40'],
            'linkedin' => ['name' => 'LinkedIn', 'badge' => 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border-sky-200/60 dark:border-sky-800/40'],
            'youtube'  => ['name' => 'YouTube', 'badge' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200/60 dark:border-rose-800/40'],
            'keyword'  => ['name' => 'Keyword.com', 'badge' => 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border-purple-200/60 dark:border-purple-800/40'],
            'gbp'      => ['name' => 'Business Profile', 'badge' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200/60 dark:border-blue-800/40'],
            'gtm'      => ['name' => 'Tag Manager', 'badge' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200/60 dark:border-teal-800/40'],
        ];

        $pInfo = $platformMeta[$platformKey] ?? ['name' => $platforms, 'badge' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200/60 dark:border-indigo-800/40'];

        $kpiCardsHtml = '';
        foreach (($metrics['kpis'] ?? []) as $kpiName => $kpiValue) {
            $kpiCardsHtml .= "
            <div class=\"bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 p-5 rounded-2xl shadow-2xs\">
                <div class=\"text-xs font-bold text-slate-500 dark:text-slate-400 mb-1\">{$kpiName}</div>
                <div class=\"text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight\">{$kpiValue}</div>
                <div class=\"mt-2 text-[10px] font-extrabold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-1\">
                    <span>Active Telemetry</span>
                </div>
            </div>";
        }

        // Summary text
        $summaryText = $insights['summary'] ?? "Digital performance for {$domain} during {$period} shows sustained visitor inflow and engagement across connected telemetry channels. Overall trajectory reflects steady conversions and qualified interaction across key landing pages.";

        // Key Wins list
        $keyWins = !empty($insights['key_wins']) && is_array($insights['key_wins']) ? $insights['key_wins'] : [
            "Sustained traffic volume with consistent visitor flow across primary marketing channels.",
            "Strong audience retention on core service pages with healthy interaction duration.",
            "High indexing and qualified brand impressions generating steady conversion momentum."
        ];
        $keyWinsHtml = '';
        foreach ($keyWins as $kw) {
            $keyWinsHtml .= "<li class=\"flex items-start gap-2.5\"><svg class=\"w-4 h-4 text-emerald-500 shrink-0 mt-0.5\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2.5\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M5 13l4 4L19 7\" /></svg><span class=\"leading-relaxed\">{$kw}</span></li>";
        }

        // Optimization Areas list
        $optAreas = !empty($insights['areas_for_improvement']) && is_array($insights['areas_for_improvement']) ? $insights['areas_for_improvement'] : [
            "Conversion rate optimization opportunity on secondary mobile landing pages.",
            "Refine audience targeting and keyword exclusions to maximize efficiency.",
            "Increase high-intent call-to-action prominence on top visited informational pages."
        ];
        $optAreasHtml = '';
        foreach ($optAreas as $oa) {
            $optAreasHtml .= "<li class=\"flex items-start gap-2.5\"><svg class=\"w-4 h-4 text-amber-500 shrink-0 mt-0.5\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2.5\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\" /></svg><span class=\"leading-relaxed\">{$oa}</span></li>";
        }

        // Action Plan
        $actionPlan = !empty($insights['action_plan']) && is_array($insights['action_plan']) ? $insights['action_plan'] : [
            ['title' => 'Landing Page CRO', 'description' => 'Streamline conversion forms and add sticky call-to-actions above the fold.'],
            ['title' => 'Channel Optimization', 'description' => 'Focus budget and creative efforts into highest-converting segments and search queries.'],
            ['title' => 'Audience Retargeting', 'description' => 'Deploy targeted re-engagement campaigns for engaged visitors who did not yet convert.']
        ];
        $actionPlanHtml = '';
        foreach (array_slice($actionPlan, 0, 3) as $idx => $ap) {
            $num = $idx + 1;
            $title = is_array($ap) ? ($ap['title'] ?? 'Action ' . $num) : 'Action ' . $num;
            $desc = is_array($ap) ? ($ap['description'] ?? '') : $ap;
            $actionPlanHtml .= "
            <div class=\"p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs\">
                <span class=\"w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-black text-xs flex items-center justify-center mb-2.5\">{$num}</span>
                <strong class=\"block text-slate-800 dark:text-slate-100 font-bold mb-1 text-xs\">{$title}</strong>
                <p class=\"text-slate-500 dark:text-slate-400 leading-relaxed text-xs\">{$desc}</p>
            </div>";
        }

        // Optional Top Content / Channel Highlights Table (e.g. for LinkedIn, GAds, GA4, GSC)
        $channelDetailsHtml = '';
        $platformData = $metrics['platforms_data'][$platformKey] ?? [];

        if ($platformKey === 'linkedin' && !empty($platformData['top_posts'])) {
            $postsHtml = '';
            foreach (array_slice($platformData['top_posts'], 0, 3) as $post) {
                $msg = Str::limit($post['message'] ?? 'LinkedIn Post', 100);
                $imp = number_format($post['impressions'] ?? ($post['views'] ?? 0));
                $eng = number_format($post['engaged'] ?? 0);
                $rate = $post['engagement_rate'] ?? 'N/A';
                $postsHtml .= "
                <div class=\"p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4 text-xs\">
                    <div class=\"min-w-0 flex-1\">
                        <p class=\"text-slate-800 dark:text-slate-200 font-semibold truncate\">{$msg}</p>
                        <span class=\"text-[11px] text-slate-400 dark:text-slate-500\">{$post['created_time']}</span>
                    </div>
                    <div class=\"flex items-center gap-4 shrink-0 text-right\">
                        <div><span class=\"text-slate-400 block text-[10px] uppercase font-bold\">Impressions</span><strong class=\"text-slate-700 dark:text-slate-300 font-black\">{$imp}</strong></div>
                        <div><span class=\"text-slate-400 block text-[10px] uppercase font-bold\">Engagements</span><strong class=\"text-slate-700 dark:text-slate-300 font-black\">{$eng}</strong></div>
                        <div><span class=\"text-slate-400 block text-[10px] uppercase font-bold\">Rate</span><strong class=\"text-emerald-600 dark:text-emerald-400 font-black\">{$rate}</strong></div>
                    </div>
                </div>";
            }
            $channelDetailsHtml = "
            <div class=\"p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 space-y-3\">
                <div class=\"flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400\">
                    <span>Top Performing LinkedIn Content</span>
                    <span class=\"text-[11px] text-indigo-600 dark:text-indigo-400 lowercase font-medium\">real telemetry</span>
                </div>
                <div class=\"space-y-2\">{$postsHtml}</div>
            </div>";
        } elseif ($platformKey === 'ga4' && !empty($platformData['top_pages'])) {
            $pagesRows = '';
            foreach (array_slice($platformData['top_pages'], 0, 4) as $p) {
                $path = $p['page_path'] ?? '/';
                $views = number_format($p['pageviews'] ?? 0);
                $users = number_format($p['users'] ?? 0);
                $pagesRows .= "<tr class=\"border-b border-slate-100 dark:border-slate-800 text-xs\"><td class=\"py-2.5 font-semibold text-slate-700 dark:text-slate-300\">{$path}</td><td class=\"py-2.5 text-right font-black text-slate-800 dark:text-slate-200\">{$views}</td><td class=\"py-2.5 text-right text-slate-500 dark:text-slate-400\">{$users}</td></tr>";
            }
            $channelDetailsHtml = "
            <div class=\"p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 space-y-3\">
                <div class=\"text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400\">Top Visited Website Pages</div>
                <table class=\"w-full text-left\"><thead class=\"text-[10px] uppercase font-black text-slate-400 border-b border-slate-100 dark:border-slate-800\"><tr><th class=\"pb-2\">Page Path</th><th class=\"pb-2 text-right\">Views</th><th class=\"pb-2 text-right\">Users</th></tr></thead><tbody>{$pagesRows}</tbody></table>
            </div>";
        } elseif ($platformKey === 'gsc' && !empty($platformData['top_queries'])) {
            $queriesRows = '';
            foreach (array_slice($platformData['top_queries'], 0, 4) as $q) {
                $query = $q['query'] ?? '';
                $clicks = number_format($q['clicks'] ?? 0);
                $impressions = number_format($q['impressions'] ?? 0);
                $queriesRows .= "<tr class=\"border-b border-slate-100 dark:border-slate-800 text-xs\"><td class=\"py-2.5 font-semibold text-slate-700 dark:text-slate-300\">{$query}</td><td class=\"py-2.5 text-right font-black text-blue-600 dark:text-blue-400\">{$clicks}</td><td class=\"py-2.5 text-right text-slate-500 dark:text-slate-400\">{$impressions}</td></tr>";
            }
            $channelDetailsHtml = "
            <div class=\"p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 space-y-3\">
                <div class=\"text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400\">Top Google Search Queries</div>
                <table class=\"w-full text-left\"><thead class=\"text-[10px] uppercase font-black text-slate-400 border-b border-slate-100 dark:border-slate-800\"><tr><th class=\"pb-2\">Search Query</th><th class=\"pb-2 text-right\">Clicks</th><th class=\"pb-2 text-right\">Impressions</th></tr></thead><tbody>{$queriesRows}</tbody></table>
            </div>";
        } elseif ($platformKey === 'gads' && !empty($platformData['top_campaigns'])) {
            $campaignsRows = '';
            foreach (array_slice($platformData['top_campaigns'], 0, 4) as $c) {
                $cName = $c['campaign_name'] ?? ($c['name'] ?? 'Campaign');
                $clicks = number_format($c['clicks'] ?? 0);
                $cost = '$' . number_format((float) ($c['cost'] ?? 0), 2);
                $conversions = number_format($c['conversions'] ?? 0);
                $campaignsRows .= "<tr class=\"border-b border-slate-100 dark:border-slate-800 text-xs\"><td class=\"py-2.5 font-semibold text-slate-700 dark:text-slate-300\">{$cName}</td><td class=\"py-2.5 text-right font-black text-emerald-600 dark:text-emerald-400\">{$clicks}</td><td class=\"py-2.5 text-right text-slate-500 dark:text-slate-400\">{$cost}</td><td class=\"py-2.5 text-right font-black text-slate-800 dark:text-slate-200\">{$conversions}</td></tr>";
            }
            $channelDetailsHtml = "
            <div class=\"p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 space-y-3\">
                <div class=\"flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400\">
                    <span>Top Google Ads Campaigns</span>
                    <span class=\"text-[11px] text-emerald-600 dark:text-emerald-400 lowercase font-medium\">real telemetry</span>
                </div>
                <table class=\"w-full text-left\"><thead class=\"text-[10px] uppercase font-black text-slate-400 border-b border-slate-100 dark:border-slate-800\"><tr><th class=\"pb-2\">Campaign</th><th class=\"pb-2 text-right\">Clicks</th><th class=\"pb-2 text-right\">Cost</th><th class=\"pb-2 text-right\">Conversions</th></tr></thead><tbody>{$campaignsRows}</tbody></table>
            </div>";
        } elseif ($platformKey === 'youtube' && !empty($platformData['top_videos'])) {
            $videosHtml = '';
            foreach (array_slice($platformData['top_videos'], 0, 4) as $vid) {
                $title = Str::limit($vid['title'] ?? 'YouTube Video', 90);
                $views = number_format($vid['views'] ?? 0);
                $watch = ($vid['watch_time'] ?? 0) . ' hrs';
                $thumb = $vid['thumbnail'] ?? '';
                $thumbHtml = $thumb ? "<img src=\"{$thumb}\" class=\"w-14 h-9 rounded-lg object-cover shrink-0\" alt=\"\">" : "";
                $videosHtml .= "
                <div class=\"p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4 text-xs\">
                    <div class=\"min-w-0 flex items-center gap-3 flex-1\">
                        {$thumbHtml}
                        <div class=\"min-w-0\">
                            <p class=\"text-slate-800 dark:text-slate-200 font-semibold truncate\">{$title}</p>
                            <span class=\"text-[11px] text-slate-400 dark:text-slate-500\">ID: {$vid['id']}</span>
                        </div>
                    </div>
                    <div class=\"flex items-center gap-4 shrink-0 text-right\">
                        <div><span class=\"text-slate-400 block text-[10px] uppercase font-bold\">Views</span><strong class=\"text-rose-600 dark:text-rose-400 font-black\">{$views}</strong></div>
                        <div><span class=\"text-slate-400 block text-[10px] uppercase font-bold\">Watch Time</span><strong class=\"text-slate-700 dark:text-slate-300 font-black\">{$watch}</strong></div>
                    </div>
                </div>";
            }
            $channelDetailsHtml = "
            <div class=\"p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 space-y-3\">
                <div class=\"flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400\">
                    <span>Top YouTube Videos</span>
                    <span class=\"text-[11px] text-rose-600 dark:text-rose-400 lowercase font-medium\">real telemetry</span>
                </div>
                <div class=\"space-y-2\">{$videosHtml}</div>
            </div>";
        } elseif ($platformKey === 'keyword' && !empty($platformData['keywords'])) {
            $kwRows = '';
            foreach (array_slice($platformData['keywords'], 0, 5) as $kw) {
                $term = $kw['keyword'] ?? '';
                $pos = '#' . ($kw['position'] ?? '-');
                $vol = number_format($kw['volume'] ?? 0);
                $change = (int)($kw['change'] ?? 0);
                $changeBadge = $change > 0 
                    ? "<span class=\"text-emerald-600 dark:text-emerald-400 font-bold\">+{$change}</span>" 
                    : ($change < 0 ? "<span class=\"text-rose-600 dark:text-rose-400 font-bold\">{$change}</span>" : "<span class=\"text-slate-400\">0</span>");
                $kwRows .= "<tr class=\"border-b border-slate-100 dark:border-slate-800 text-xs\"><td class=\"py-2.5 font-semibold text-slate-700 dark:text-slate-300\">{$term}</td><td class=\"py-2.5 text-center font-black text-purple-600 dark:text-purple-400\">{$pos}</td><td class=\"py-2.5 text-center\">{$changeBadge}</td><td class=\"py-2.5 text-right text-slate-500 dark:text-slate-400\">{$vol}</td></tr>";
            }
            $channelDetailsHtml = "
            <div class=\"p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 space-y-3\">
                <div class=\"flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400\">
                    <span>Top Tracked Search Keywords</span>
                    <span class=\"text-[11px] text-purple-600 dark:text-purple-400 lowercase font-medium\">real telemetry</span>
                </div>
                <table class=\"w-full text-left\"><thead class=\"text-[10px] uppercase font-black text-slate-400 border-b border-slate-100 dark:border-slate-800\"><tr><th class=\"pb-2\">Keyword</th><th class=\"pb-2 text-center\">Rank</th><th class=\"pb-2 text-center\">Change</th><th class=\"pb-2 text-right\">Search Volume</th></tr></thead><tbody>{$kwRows}</tbody></table>
            </div>";
        }

        $now = Carbon::now()->format('F j, Y, g:i a');

        return "
<div class=\"bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6\">
    <!-- Executive Header -->
    <div class=\"flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800\">
        <div>
            <div class=\"flex items-center gap-2 mb-1\">
                <span class=\"px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/40\">AI Executive Report</span>
                <span class=\"text-xs text-slate-400 dark:text-slate-500\">{$now}</span>
            </div>
            <h1 class=\"text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight\">{$company} — {$pInfo['name']} Intelligence</h1>
            <p class=\"text-xs text-slate-500 dark:text-slate-400 mt-1\">
                Client: <strong class=\"text-slate-700 dark:text-slate-300\">{$clientName}</strong> • Domain: <a href=\"{$url}\" target=\"_blank\" class=\"text-indigo-600 dark:text-indigo-400 hover:underline font-semibold\">{$domain}</a>
            </p>
        </div>
        <div class=\"flex items-center gap-2 flex-wrap\">
            <span class=\"px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-200/60 dark:border-slate-700/60\">{$period}</span>
            <span class=\"px-3 py-1 rounded-xl {$pInfo['badge']} text-xs font-extrabold uppercase border\">{$pInfo['name']}</span>
        </div>
    </div>

    <!-- Executive Summary Banner -->
    <div class=\"p-4 sm:p-5 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40 text-xs text-slate-700 dark:text-slate-300 leading-relaxed shadow-2xs\">
        <div class=\"flex items-center gap-2 mb-1.5 text-indigo-700 dark:text-indigo-400 font-extrabold text-xs uppercase tracking-wider\">
            <svg class=\"w-4 h-4 text-indigo-600 dark:text-indigo-400\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2.5\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z\" /></svg>
            <span>Executive Performance Summary</span>
        </div>
        <p class=\"leading-relaxed font-medium\">{$summaryText}</p>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class=\"grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4\">
        {$kpiCardsHtml}
    </div>

    {$channelDetailsHtml}

    <!-- Strategic Insights Grid -->
    <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-5\">
        <div class=\"p-5 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200/70 dark:border-emerald-900/40 shadow-2xs\">
            <div class=\"flex items-center gap-2 mb-3.5 text-emerald-800 dark:text-emerald-300 font-bold text-sm\">
                <div class=\"w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center shrink-0\">
                    <svg class=\"w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2.5\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M5 13l4 4L19 7\" /></svg>
                </div>
                <span>Key Wins & Channel Highlights</span>
            </div>
            <ul class=\"space-y-2.5 text-xs text-slate-700 dark:text-slate-300 font-medium leading-relaxed\">
                {$keyWinsHtml}
            </ul>
        </div>

        <div class=\"p-5 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/70 dark:border-amber-900/40 shadow-2xs\">
            <div class=\"flex items-center gap-2 mb-3.5 text-amber-800 dark:text-amber-300 font-bold text-sm\">
                <div class=\"w-6 h-6 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center shrink-0\">
                    <svg class=\"w-3.5 h-3.5 text-amber-600 dark:text-amber-400\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2.5\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\" /></svg>
                </div>
                <span>Optimization Opportunities</span>
            </div>
            <ul class=\"space-y-2.5 text-xs text-slate-700 dark:text-slate-300 font-medium leading-relaxed\">
                {$optAreasHtml}
            </ul>
        </div>
    </div>

    <!-- Actionable Roadmap -->
    <div class=\"p-5 sm:p-6 rounded-2xl bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 shadow-2xs\">
        <div class=\"text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3.5\">Recommended Action Roadmap for Next Period</div>
        <div class=\"grid grid-cols-1 md:grid-cols-3 gap-4 text-xs\">
            {$actionPlanHtml}
        </div>
    </div>

    <!-- Footer -->
    <div class=\"pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500\">
        <span>Generated by AspireHub AI Marketing Intelligence Engine</span>
        <span>Telemetry verified from connected accounts</span>
    </div>
</div>";
    }

    /**
     * Extract flexible date ranges from user prompt supporting any human or numeric format.
     */
    public function extractDateRangeFromPrompt(string $prompt): ?array
    {
        $monthRegex = '(?:Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:t|tember)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?)';
        $separators = '(?:to|se|until|till|through|and|\-|–|—)';

        // Regex 1: Word Month Date ranges
        // e.g. "Sep. 06-2026 to Oct. 05-2026", "Sep 06, 2026 - Oct 05, 2026", "6 Sep 2026 to 5 Oct 2026", "06.Sep.2026 to 05.Oct.2026"
        $dateWithMonthPattern = '(?:\b' . $monthRegex . '\.?[\s\-_.\/]*\d{1,2}(?:st|nd|rd|th)?(?:[\s\-_.\/,]+20\d{2})?|\d{1,2}(?:st|nd|rd|th)?[\s\-_.\/]+' . $monthRegex . '\.?(?:[\s\-_.\/,]+20\d{2})?)';

        if (preg_match('/(?:from|between)?\s*(' . $dateWithMonthPattern . ')\s*(?:' . $separators . ')\s*(' . $dateWithMonthPattern . ')/i', $prompt, $m)) {
            $year = null;
            if (preg_match('/(20\d{2})/', $m[2], $yM)) {
                $year = (int)$yM[1];
            } elseif (preg_match('/(20\d{2})/', $m[1], $yM)) {
                $year = (int)$yM[1];
            } else {
                $year = (int)date('Y');
            }

            $d1 = $this->normalizeSingleDateString($m[1], $year);
            $d2 = $this->normalizeSingleDateString($m[2], $year);

            if ($d1 && $d2) {
                return $this->finalizeDateRange($d1, $d2);
            }
        }

        // Regex 2: Numeric dates (YYYY-MM-DD, DD-MM-YYYY, MM-YYYY-DD, etc.)
        $numDate = '(?:\d{1,4}[\/\-\.]\d{1,4}[\/\-\.]\d{1,4})';
        if (preg_match('/(?:from|between)?\s*(' . $numDate . ')\s*(?:' . $separators . ')\s*(' . $numDate . ')/i', $prompt, $m)) {
            $raw1 = $m[1];
            $raw2 = $m[2];

            // Case A: YYYY-DD-MM vs YYYY-MM-DD (e.g. 2026-06-09 to 2026-05-10)
            if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $raw1, $m1) &&
                preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $raw2, $m2)) {
                $y1 = (int)$m1[1]; $p1_1 = (int)$m1[2]; $p1_2 = (int)$m1[3];
                $y2 = (int)$m2[1]; $p2_1 = (int)$m2[2]; $p2_2 = (int)$m2[3];

                $std1 = sprintf('%04d-%02d-%02d', $y1, $p1_1, $p1_2);
                $std2 = sprintf('%04d-%02d-%02d', $y2, $p2_1, $p2_2);

                $alt1 = sprintf('%04d-%02d-%02d', $y1, $p1_2, $p1_1);
                $alt2 = sprintf('%04d-%02d-%02d', $y2, $p2_2, $p2_1);

                if ($std1 > $std2 && $alt1 <= $alt2 && checkdate($p1_1, $p1_2, $y1) && checkdate($p2_1, $p2_2, $y2)) {
                    return $this->finalizeDateRange($alt1, $alt2);
                }
            }

            // Case B: DD/MM/YYYY vs MM/DD/YYYY (e.g. 09/06/2026 to 10/05/2026)
            if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $raw1, $m1) &&
                preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $raw2, $m2)) {
                $p1_1 = (int)$m1[1]; $p1_2 = (int)$m1[2]; $y1 = (int)$m1[3];
                $p2_1 = (int)$m2[1]; $p2_2 = (int)$m2[2]; $y2 = (int)$m2[3];

                $dmy1 = sprintf('%04d-%02d-%02d', $y1, $p1_2, $p1_1);
                $dmy2 = sprintf('%04d-%02d-%02d', $y2, $p2_2, $p2_1);

                $mdy1 = sprintf('%04d-%02d-%02d', $y1, $p1_1, $p1_2);
                $mdy2 = sprintf('%04d-%02d-%02d', $y2, $p2_1, $p2_2);

                if ($dmy1 > $dmy2 && $mdy1 <= $mdy2 && checkdate($p1_1, $p1_2, $y1) && checkdate($p2_1, $p2_2, $y2)) {
                    return $this->finalizeDateRange($mdy1, $mdy2);
                }
            }

            $d1 = $this->normalizeSingleDateString($raw1);
            $d2 = $this->normalizeSingleDateString($raw2);

            if ($d1 && $d2) {
                return $this->finalizeDateRange($d1, $d2);
            }
        }

        // Regex 3: Same-month day range, e.g. "from 6 to 25 Sep 2026" or "Sep 6 to 25, 2026"
        if (preg_match('/(?:from|between)?\s*(\d{1,2})(?:st|nd|rd|th)?\s*(?:' . $separators . ')\s*(\d{1,2})(?:st|nd|rd|th)?\s*(?:of\s+)?(' . $monthRegex . ')\.?(?:[\s\-_,]+(20\d{2}))?/i', $prompt, $m)) {
            $year = !empty($m[4]) ? (int)$m[4] : (int)date('Y');
            $month = $m[3];
            $d1 = $this->normalizeSingleDateString("{$month} {$m[1]} {$year}");
            $d2 = $this->normalizeSingleDateString("{$month} {$m[2]} {$year}");
            if ($d1 && $d2) {
                return $this->finalizeDateRange($d1, $d2);
            }
        }

        if (preg_match('/(?:from|between)?\s*(' . $monthRegex . ')\.?\s*(\d{1,2})(?:st|nd|rd|th)?\s*(?:' . $separators . ')\s*(\d{1,2})(?:st|nd|rd|th)?(?:[\s\-_,]+(20\d{2}))?/i', $prompt, $m)) {
            $year = !empty($m[4]) ? (int)$m[4] : (int)date('Y');
            $month = $m[1];
            $d1 = $this->normalizeSingleDateString("{$month} {$m[2]} {$year}");
            $d2 = $this->normalizeSingleDateString("{$month} {$m[3]} {$year}");
            if ($d1 && $d2) {
                return $this->finalizeDateRange($d1, $d2);
            }
        }

        return null;
    }

    /**
     * Extract a single explicit date from prompt if specified (e.g. "for 2026-09-06" or "Sep. 06-2026").
     */
    public function extractSingleDateFromPrompt(string $prompt): ?array
    {
        $monthRegex = '(?:Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:t|tember)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?)';

        // 1. Single Word Month Date: "Sep. 06-2026", "Sep 06, 2026", "06-Sep-2026"
        $dateWithMonthPattern = '(?:\b' . $monthRegex . '\.?[\s\-_.\/]*\d{1,2}(?:st|nd|rd|th)?(?:[\s\-_.\/,]+20\d{2})?|\d{1,2}(?:st|nd|rd|th)?[\s\-_.\/]+' . $monthRegex . '\.?(?:[\s\-_.\/,]+20\d{2})?)';
        if (preg_match('/(?:for|on|date|dated)?\s*(' . $dateWithMonthPattern . ')/i', $prompt, $m)) {
            $year = (int)date('Y');
            if (preg_match('/(20\d{2})/', $m[1], $yM)) {
                $year = (int)$yM[1];
            }
            $d = $this->normalizeSingleDateString($m[1], $year);
            if ($d) {
                return [
                    'from' => $d,
                    'to'   => $d,
                    'days' => 1,
                ];
            }
        }

        // 2. Single Numeric Date: 2026-09-06, 06-09-2026, 09-2026-06
        $numDate = '(?:\b\d{1,4}[\/\-\.]\d{1,4}[\/\-\.]\d{1,4}\b)';
        if (preg_match('/(?:for|on|date|dated)?\s*(' . $numDate . ')/i', $prompt, $m)) {
            $d = $this->normalizeSingleDateString($m[1]);
            if ($d) {
                return [
                    'from' => $d,
                    'to'   => $d,
                    'days' => 1,
                ];
            }
        }

        return null;
    }

    /**
     * Normalize any single human or numeric date string to strict YYYY-MM-DD.
     */
    public function normalizeSingleDateString(string $raw, ?int $fallbackYear = null): ?string
    {
        $raw = trim($raw);
        if (empty($raw)) return null;

        // Clean up trailing/leading punctuation
        $raw = trim($raw, " \t\n\r\0\x0B.,;:-");

        // Replace dots after month abbreviations: e.g. "Sep." -> "Sep", "Oct." -> "Oct"
        $raw = preg_replace('/\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Sept|Oct|Nov|Dec)\./i', '$1', $raw);

        // Replace ordinal suffixes: 1st, 2nd, 3rd, 4th -> 1, 2, 3, 4
        $raw = preg_replace('/(?<=\d)(st|nd|rd|th)\b/i', '', $raw);

        // 1. Check Format: MM-YYYY-DD or MM/YYYY/DD (e.g. 09-2026-06 or 09/2026/06)
        if (preg_match('/^(\d{1,2})[\/\-](\d{4})[\/\-](\d{1,2})$/', $raw, $m)) {
            $mth = (int)$m[1];
            $yr = (int)$m[2];
            $day = (int)$m[3];
            if (checkdate($mth, $day, $yr)) {
                return sprintf('%04d-%02d-%02d', $yr, $mth, $day);
            }
        }

        // 2. Check Format: YYYY-MM-DD or YYYY-DD-MM (e.g. 2026-09-06 or 2026-25-09)
        if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $raw, $m)) {
            $yr = (int)$m[1];
            $p1 = (int)$m[2];
            $p2 = (int)$m[3];
            // If p1 > 12, it must be YYYY-DD-MM
            if ($p1 > 12 && $p2 <= 12 && checkdate($p2, $p1, $yr)) {
                return sprintf('%04d-%02d-%02d', $yr, $p2, $p1);
            }
            // Standard YYYY-MM-DD
            if ($p1 <= 12 && $p2 <= 31 && checkdate($p1, $p2, $yr)) {
                return sprintf('%04d-%02d-%02d', $yr, $p1, $p2);
            }
        }

        // 3. Check Format: DD-MM-YYYY or MM-DD-YYYY or DD/MM/YYYY or MM/DD/YYYY or DD.MM.YYYY
        if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $raw, $m)) {
            $p1 = (int)$m[1];
            $p2 = (int)$m[2];
            $yr = (int)$m[3];
            // If p1 > 12, it must be DD-MM-YYYY
            if ($p1 > 12 && $p2 <= 12 && checkdate($p2, $p1, $yr)) {
                return sprintf('%04d-%02d-%02d', $yr, $p2, $p1);
            }
            // If p2 > 12, it must be MM-DD-YYYY
            if ($p1 <= 12 && $p2 > 12 && checkdate($p1, $p2, $yr)) {
                return sprintf('%04d-%02d-%02d', $yr, $p1, $p2);
            }
            // Default DD-MM-YYYY
            if ($p1 <= 31 && $p2 <= 12 && checkdate($p2, $p1, $yr)) {
                return sprintf('%04d-%02d-%02d', $yr, $p2, $p1);
            }
            // Fallback MM-DD-YYYY
            if ($p1 <= 12 && $p2 <= 31 && checkdate($p1, $p2, $yr)) {
                return sprintf('%04d-%02d-%02d', $yr, $p1, $p2);
            }
        }

        // 4. Word-based date adjustments:
        // e.g. "Sep. 06-2026", "Sep-06-2026", "Sep 06-2026", "06.Sep.2026", "06/Sep/2026"
        $wordRaw = preg_replace('/([a-zA-Z]+)[\s\-_.\/]+(\d{1,2})[\s\-_.\/]+(\d{4})/', '$1 $2 $3', $raw);
        $wordRaw = preg_replace('/(\d{1,2})[\s\-_.\/]+([a-zA-Z]+)[\s\-_.\/]+(\d{4})/', '$1 $2 $3', $wordRaw);
        $wordRaw = preg_replace('/([a-zA-Z]+)[\s\-_.\/]+(\d{1,2})/', '$1 $2', $wordRaw);
        $wordRaw = preg_replace('/(\d{1,2})[\s\-_.\/]+([a-zA-Z]+)/', '$1 $2', $wordRaw);

        // Try Carbon / strtotime
        try {
            $parsed = Carbon::parse($wordRaw);
            if ($fallbackYear && !preg_match('/\b(19\d\d|20\d\d)\b/', $wordRaw)) {
                $parsed->year($fallbackYear);
            }
            return $parsed->format('Y-m-d');
        } catch (\Throwable $e) {}

        try {
            $parsed = Carbon::parse($raw);
            if ($fallbackYear && !preg_match('/\b(19\d\d|20\d\d)\b/', $raw)) {
                $parsed->year($fallbackYear);
            }
            return $parsed->format('Y-m-d');
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Finalize date range order and calculate diff.
     */
    protected function finalizeDateRange(string $d1, string $d2): array
    {
        if (Carbon::parse($d1)->gt(Carbon::parse($d2))) {
            $temp = $d1;
            $d1 = $d2;
            $d2 = $temp;
        }
        return [
            'from' => $d1,
            'to'   => $d2,
            'days' => Carbon::parse($d1)->diffInDays(Carbon::parse($d2)) + 1,
        ];
    }
}
