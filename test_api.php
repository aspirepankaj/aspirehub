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
if (!$accessToken) die("Failed to get access token");

foreach (['v17', 'v18', 'v19'] as $version) {
    echo "Testing $version...\n";
    $url = "https://googleads.googleapis.com/$version/customers:listAccessibleCustomers";
    $req = \Illuminate\Support\Facades\Http::withToken($accessToken)
        ->withHeaders(['developer-token' => $developerToken]);
    if (app()->environment('local')) $req = $req->withoutVerifying();
    $res = $req->get($url);
    if ($res->successful()) {
        echo "SUCCESS on $version!\n";
        print_r($res->json());
        break;
    } else {
        echo "FAILED on $version: " . $res->status() . "\n";
    }
}
