<?php
$files = glob('storage/app/adscljson/christine-mullen-christine/aspiredigitalsolutions/gads/2026/*.json');
foreach ($files as $file) {
    $data = json_decode(file_get_contents($file), true);
    $data['top_keywords'] = [
        ['keyword' => '"australia guided vacation"', 'cost' => 731.26, 'clicks' => 91, 'ctr' => 0.0652],
        ['keyword' => '"best australia vacation"', 'cost' => 523.84, 'clicks' => 190, 'ctr' => 0.0650],
        ['keyword' => '"australia vacation packages"', 'cost' => 377.03, 'clicks' => 68, 'ctr' => 0.1266],
        ['keyword' => '"australia itinerary"', 'cost' => 261.53, 'clicks' => 58, 'ctr' => 0.0641],
        ['keyword' => '[best australia vacation]', 'cost' => 186.16, 'clicks' => 91, 'ctr' => 0.1013]
    ];
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    echo "Injected " . $file . "\n";
}
