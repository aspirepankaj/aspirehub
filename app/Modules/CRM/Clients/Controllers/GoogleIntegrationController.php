<?php

namespace App\Modules\CRM\Clients\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Websites\Models\WebsiteIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleIntegrationController extends Controller
{
    public function redirect(int $id)
    {
        $integration = WebsiteIntegration::with('website')->findOrFail($id);
        $apiCreds = $integration->api_credentials;

        if (isset($apiCreds['type']) && $apiCreds['type'] === 'service_account') {
            $integration->update([
                'status' => 'connected',
                'auth_credentials' => [
                    'access_token' => 'service_account',
                    'refresh_token' => null,
                ],
                'account_identifier' => $apiCreds['client_email'] ?? 'Service Account',
                'last_sync_at' => now(),
            ]);

            $isStaff = auth()->user() && auth()->user()->staff && auth()->user()->staff->status === 'active';
            $detailRoute = $isStaff ? 'staff.clients.detail' : 'admin.clients.detail';

            session()->flash('success', "Service Account authenticated successfully!");
            
            return redirect()->to(route($detailRoute, [
                'id' => $integration->website->client_id,
                'activeTab' => 'integrations',
                'selectedWebsiteId' => $integration->website_id,
            ]));
        }

        $config = $apiCreds['web'] ?? $apiCreds['installed'] ?? null;

        if (!$config || empty($config['client_id'])) {
            return redirect()->back()->with('error', 'Google App Credentials are not properly configured.');
        }

        $clientId = $config['client_id'];
        $redirectUri = $config['redirect_uris'][0] ?? route('admin.integrations.google.callback');

        $scopes = [
            'https://www.googleapis.com/auth/analytics.readonly',
            'https://www.googleapis.com/auth/webmasters.readonly',
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/yt-analytics.readonly'
        ];

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => implode(' ', $scopes),
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => encrypt([
                'integration_id' => $integration->id,
                'website_id' => $integration->website_id,
            ]),
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function callback(Request $request)
    {
        $isStaff = auth()->user() && auth()->user()->staff && auth()->user()->staff->status === 'active';
        $baseRoute = $isStaff ? 'staff.clients' : 'admin.clients';
        $detailRoute = $isStaff ? 'staff.clients.detail' : 'admin.clients.detail';

        if (!$request->has('code') || !$request->has('state')) {
            return redirect()->route($baseRoute)->with('error', 'Google authentication failed or was cancelled.');
        }

        try {
            $state = decrypt($request->state);
            $integrationId = $state['integration_id'];
        } catch (\Exception $e) {
            return redirect()->route($baseRoute)->with('error', 'Invalid OAuth state.');
        }

        $integration = WebsiteIntegration::with('website')->findOrFail($integrationId);
        $apiCreds = $integration->api_credentials;
        $config = $apiCreds['web'] ?? $apiCreds['installed'] ?? null;

        if (!$config || empty($config['client_id']) || empty($config['client_secret'])) {
            return redirect()->route($detailRoute, ['id' => $integration->website->client_id])
                ->with('error', 'Integration credentials not found.');
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $request->code,
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect_uris'][0] ?? route('admin.integrations.google.callback'),
            'grant_type' => 'authorization_code',
        ]);

        if ($response->failed()) {
            return redirect()->route($detailRoute, ['id' => $integration->website->client_id])
                ->with('error', 'Failed to retrieve access tokens: ' . ($response->json()['error_description'] ?? $response->body()));
        }

        $tokens = $response->json();

        $integration->update([
            'status' => 'connected',
            'auth_credentials' => [
                'access_token' => $tokens['access_token'] ?? null,
                'refresh_token' => $tokens['refresh_token'] ?? ($integration->auth_credentials['refresh_token'] ?? null),
                'expires_in' => $tokens['expires_in'] ?? 3600,
                'created_at' => time(),
            ],
            'account_identifier' => $this->fetchGoogleAccountEmail($tokens['access_token'] ?? ''),
            'last_sync_at' => now(),
        ]);

        session()->flash('success', "Authenticated with Google successfully!");

        return redirect()->to(route($detailRoute, [
            'id' => $integration->website->client_id,
            'activeTab' => 'integrations',
            'selectedWebsiteId' => $integration->website_id,
        ]));
    }

    private function fetchGoogleAccountEmail(string $accessToken): string
    {
        try {
            $response = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v2/userinfo');
            if ($response->successful()) {
                return $response->json()['email'] ?? 'Google Account';
            }
        } catch (\Exception $e) {
            // Ignore
        }
        return 'Google Account';
    }
}
