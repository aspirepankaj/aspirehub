<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$integration = \App\Modules\CRM\Websites\Models\WebsiteIntegration::where('integration_type', 'gads')->first();
$apiCreds = $integration->api_credentials ?? [];
$developerToken = $apiCreds['developer_token'] ?? '';
$auth = $integration->auth_credentials ?? [];
$refreshToken = $auth['refresh_token'] ?? '';
$clientId = $apiCreds['web']['client_id'] ?? $apiCreds['installed']['client_id'] ?? '';
$clientSecret = $apiCreds['web']['client_secret'] ?? $apiCreds['installed']['client_secret'] ?? '';

$http = \Illuminate\Support\Facades\Http::asForm();
if (app()->environment('local')) $http = $http->withoutVerifying();
$response = $http->post('https://oauth2.googleapis.com/token', [
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'refresh_token' => $refreshToken,
    'grant_type' => 'refresh_token',
]);
$accessToken = $response->json()['access_token'] ?? '';

$customerId = '3016365280';
$query = "SELECT metrics.clicks, metrics.impressions, metrics.cost_micros, metrics.conversions FROM customer WHERE segments.date >= '2024-01-01'";

$ch = curl_init("https://googleads.googleapis.com/v17/customers/{$customerId}/googleAds:search");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "developer-token: $developerToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $query]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "Status: $status\n";
echo "Body: $res\n";
