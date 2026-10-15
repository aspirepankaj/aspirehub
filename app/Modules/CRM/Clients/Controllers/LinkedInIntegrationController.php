<?php

namespace App\Modules\CRM\Clients\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LinkedInIntegrationController extends Controller
{
    /**
     * Redirect to LinkedIn OAuth Authorization URL
     */
    public function redirect(int $id)
    {
        $integration = WebsiteIntegration::with('website')->findOrFail($id);
        $apiCreds = $integration->api_credentials ?? [];

        $clientId = $apiCreds['client_id'] ?? config('services.linkedin.client_id') ?? env('LINKEDIN_CLIENT_ID');
        $clientSecret = $apiCreds['client_secret'] ?? config('services.linkedin.client_secret') ?? env('LINKEDIN_CLIENT_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->back()->with('error', 'LinkedIn App Credentials (Client ID / Secret) are not configured. Please configure them in the modal first.');
        }

        $redirectUri = route('admin.integrations.linkedin.callback');

        // Scopes: Default to standard 'openid profile email' which works on ALL standard apps
        // If developer has added other products (e.g., Community Management API or Share on LinkedIn),
        // they can customize the scopes in the config modal.
        $scopes = !empty($apiCreds['scopes']) 
            ? trim($apiCreds['scopes']) 
            : (config('services.linkedin.scopes') ?: 'openid profile email');

        // Generate clean random 32-character state token to prevent LinkedIn state parameter length issues
        $stateToken = bin2hex(random_bytes(16));
        session([
            'linkedin_oauth_state' => $stateToken,
            'linkedin_integration_id' => $integration->id,
            'linkedin_website_id' => $integration->website_id,
            'linkedin_client_id' => $integration->website->client_id ?? null,
        ]);

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'state' => $stateToken,
            'scope' => $scopes,
        ]);

        return redirect()->away('https://www.linkedin.com/oauth/v2/authorization?' . $query);
    }

    /**
     * Handle OAuth Callback from LinkedIn
     */
    public function callback(Request $request)
    {
        $isStaff = auth()->user() && auth()->user()->staff && auth()->user()->staff->status === 'active';
        $baseRoute = $isStaff ? 'staff.clients' : 'admin.clients';
        $detailRoute = $isStaff ? 'staff.clients.detail' : 'admin.clients.detail';

        // Retrieve integration from session or fallback
        $integrationId = session('linkedin_integration_id');
        $clientId = session('linkedin_client_id');
        $websiteId = session('linkedin_website_id');

        if (!$integrationId && $request->has('state')) {
            try {
                $decrypted = decrypt($request->state);
                $integrationId = $decrypted['integration_id'] ?? null;
            } catch (\Exception $e) {
                // Not encrypted or state token mismatch
            }
        }

        if (!$integrationId) {
            $integration = WebsiteIntegration::where('integration_type', 'linkedin')
                ->latest('updated_at')
                ->with('website')
                ->first();
        } else {
            $integration = WebsiteIntegration::with('website')->find($integrationId);
        }

        $clientIdParam = $integration ? ($integration->website->client_id ?? null) : $clientId;
        $websiteIdParam = $integration ? $integration->website_id : $websiteId;

        // Handle error returned by LinkedIn
        if ($request->has('error')) {
            $errorDesc = $request->get('error_description') ?? $request->get('error');
            Log::error('LinkedIn OAuth returned error: ' . $errorDesc);

            if ($clientIdParam) {
                return redirect()->route($detailRoute, [
                    'id' => $clientIdParam,
                    'activeTab' => 'integrations',
                    'selectedWebsiteId' => $websiteIdParam,
                ])->with('error', 'LinkedIn Error: ' . $errorDesc);
            }

            return redirect()->route($baseRoute)->with('error', 'LinkedIn Error: ' . $errorDesc);
        }

        if (!$request->has('code')) {
            if ($clientIdParam) {
                return redirect()->route($detailRoute, [
                    'id' => $clientIdParam,
                    'activeTab' => 'integrations',
                    'selectedWebsiteId' => $websiteIdParam,
                ])->with('error', 'LinkedIn authentication cancelled or no authorization code received.');
            }
            return redirect()->route($baseRoute)->with('error', 'LinkedIn authentication cancelled or failed.');
        }

        if (!$integration) {
            return redirect()->route($baseRoute)->with('error', 'LinkedIn integration record not found.');
        }

        $apiCreds = $integration->api_credentials ?? [];
        $appClientId = $apiCreds['client_id'] ?? config('services.linkedin.client_id') ?? env('LINKEDIN_CLIENT_ID');
        $appClientSecret = $apiCreds['client_secret'] ?? config('services.linkedin.client_secret') ?? env('LINKEDIN_CLIENT_SECRET');
        $redirectUri = route('admin.integrations.linkedin.callback');

        if (empty($appClientId) || empty($appClientSecret)) {
            return redirect()->route($detailRoute, ['id' => $integration->website->client_id])
                ->with('error', 'LinkedIn credentials not found.');
        }

        $http = Http::asForm();
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'client_id' => $appClientId,
            'client_secret' => $appClientSecret,
            'redirect_uri' => $redirectUri,
        ]);

        if ($response->failed()) {
            $err = $response->json()['error_description'] ?? $response->body();
            Log::error('LinkedIn OAuth Token Exchange Failed: ' . $err);
            return redirect()->route($detailRoute, [
                'id' => $integration->website->client_id,
                'activeTab' => 'integrations',
                'selectedWebsiteId' => $integration->website_id,
            ])->with('error', 'Failed to retrieve LinkedIn access token: ' . $err);
        }

        $tokens = $response->json();
        $accessToken = $tokens['access_token'] ?? null;

        if (!$accessToken) {
            return redirect()->route($detailRoute, [
                'id' => $integration->website->client_id,
                'activeTab' => 'integrations',
                'selectedWebsiteId' => $integration->website_id,
            ])->with('error', 'No access token received from LinkedIn.');
        }

        $accountIdentifier = $this->fetchLinkedInAccountIdentifier($accessToken);

        $existingAuth = $integration->auth_credentials ?? [];
        $integration->update([
            'status' => 'connected',
            'auth_credentials' => array_merge($existingAuth, [
                'access_token' => $accessToken,
                'refresh_token' => $tokens['refresh_token'] ?? ($existingAuth['refresh_token'] ?? null),
                'expires_in' => $tokens['expires_in'] ?? 5184000,
                'created_at' => time(),
                'account_identifier' => $accountIdentifier,
            ]),
            'account_identifier' => $accountIdentifier,
            'last_sync_at' => now(),
        ]);

        // Clear session variables
        session()->forget(['linkedin_oauth_state', 'linkedin_integration_id', 'linkedin_website_id', 'linkedin_client_id']);

        // Log Activity
        try {
            \App\Modules\Core\Activity\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'connect_linkedin_integration',
                'loggable_type' => \App\Modules\CRM\Clients\Models\Client::class,
                'loggable_id' => $integration->website->client_id,
                'description' => "Successfully connected LinkedIn account: {$accountIdentifier}",
                'meta' => [
                    'website_id' => $integration->website_id,
                    'account_identifier' => $accountIdentifier,
                ]
            ]);
        } catch (\Exception $e) {
            // Ignore log error
        }

        session()->flash('success', "Connected with LinkedIn successfully as {$accountIdentifier}! Please select your LinkedIn Page below.");

        return redirect()->to(route($detailRoute, [
            'id' => $integration->website->client_id,
            'activeTab' => 'integrations',
            'selectedWebsiteId' => $integration->website_id,
        ]));
    }

    /**
     * Fetch LinkedIn account details (Name and Email)
     */
    private function fetchLinkedInAccountIdentifier(string $accessToken): string
    {
        try {
            $http = Http::withToken($accessToken);
            if (app()->environment('local')) {
                $http = $http->withoutVerifying();
            }

            // 1. Try standard OpenID Connect userinfo
            $response = $http->get('https://api.linkedin.com/v2/userinfo');
            if ($response->successful()) {
                $data = $response->json();
                $name = $data['name'] ?? '';
                $email = $data['email'] ?? '';
                if ($name && $email) {
                    return "{$name} ({$email})";
                }
                return $name ?: ($email ?: 'LinkedIn Account');
            }

            // 2. Fallback to /v2/me
            $meRes = $http->get('https://api.linkedin.com/v2/me');
            if ($meRes->successful()) {
                $me = $meRes->json();
                $firstName = $me['localizedFirstName'] ?? '';
                $lastName = $me['localizedLastName'] ?? '';
                $name = trim("{$firstName} {$lastName}");
                if ($name) {
                    return $name;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching LinkedIn user info: ' . $e->getMessage());
        }

        return 'LinkedIn Account';
    }
}
