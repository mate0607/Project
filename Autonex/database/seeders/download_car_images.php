<?php
/**
 * Pre-download Wikipedia car images for seeding.
 * Run once: php database/seeders/download_car_images.php
 * Images saved to database/seeders/car-images/
 */

$titles = [
    'BMW_3_Series_(G20)',
    'Audi_A4',
    'Mercedes-Benz_C-Class_(W206)',
    'Volkswagen_Golf_Mk8',
    'Toyota_Corolla_(E210)',
    'Škoda_Octavia',
    'Ford_Focus_(fourth_generation)',
    'Hyundai_Tucson',
    'Opel_Astra',
    'Suzuki_Vitara',
    'Kia_Sportage',
    'Mazda_CX-5',
    'Peugeot_3008',
    'Renault_Mégane',
    'SEAT_León',
    'Tesla_Model_3',
    'Volvo_XC60',
    'Honda_Civic_(eleventh_generation)',
    'Dacia_Duster',
    'Citroën_C5_Aircross',
    'Fiat_500',
    'Nissan_Qashqai',
    'Porsche_Cayenne',
    'Range_Rover_Evoque',
    'Lexus_NX',
    'Alfa_Romeo_Giulia',
    'Cupra_Formentor',
    'Mini_Hatch',
    'Jeep_Compass',
];

$dir = __DIR__ . '/car-images';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$ctx = stream_context_create([
    'http' => [
        'header' => "User-Agent: AutonexSeeder/1.0 (education project)\r\n",
        'timeout' => 30,
    ]
]);

$success = 0;
$fail = 0;

foreach ($titles as $title) {
    $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title) . '.jpg';
    $filepath = $dir . '/' . $safeFilename;

    if (file_exists($filepath) && filesize($filepath) > 2000) {
        echo "  ✓ SKIP {$title} (already exists)\n";
        $success++;
        continue;
    }

    echo "  📥 {$title}... ";

    // Get thumbnail URL from REST API
    $apiUrl = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($title);
    $json = @file_get_contents($apiUrl, false, $ctx);
    if (!$json) {
        echo "API FAILED\n";
        $fail++;
        sleep(2);
        continue;
    }

    $data = json_decode($json, true);
    $thumbUrl = $data['thumbnail']['source'] ?? null;
    if (!$thumbUrl) {
        echo "NO THUMBNAIL\n";
        $fail++;
        continue;
    }

    // Download thumbnail
    $imgData = @file_get_contents($thumbUrl, false, $ctx);
    if ($imgData && strlen($imgData) > 2000) {
        file_put_contents($filepath, $imgData);
        echo "OK (" . strlen($imgData) . " bytes)\n";
        $success++;
    } else {
        echo "DOWNLOAD FAILED\n";
        $fail++;
    }

    // Be polite to Wikimedia
    usleep(500_000);
}

echo "\nDone! Success: {$success}, Failed: {$fail}\n";
echo "Images saved to: {$dir}\n";
