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

$url = "https://googleads.googleapis.com/v17/customers:listAccessibleCustomers";
$options = [
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer $accessToken\r\n" .
                    "developer-token: $developerToken\r\n",
        'ignore_errors' => true
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
];
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $http_response_header[0] . "\n";
echo $result . "\n";
