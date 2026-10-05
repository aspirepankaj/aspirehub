<?php
$url = "https://googleads.googleapis.com/v17/customers/listAccessibleCustomers";
$options = [
    'http' => [
        'method' => 'GET',
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
