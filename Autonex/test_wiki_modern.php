<?php
// Test: modern generation Wikipedia article images

$titles = [
    'BMW_3_Series_(G20)', 'Audi_A4_(B9)', 'Mercedes-Benz_C-Class_(W206)',
    'Volkswagen_Golf_Mk8', 'Toyota_Corolla_(E210)', 'Škoda_Octavia_(NX)',
    'Ford_Focus_(fourth_generation)', 'Hyundai_Tucson_(NX4)',
    'Kia_Sportage', 'Mazda_CX-5', 'Peugeot_3008',
    'Opel_Astra_L', 'Renault_Mégane', 'SEAT_León_(Mk4)',
    'Tesla_Model_3', 'Volvo_XC60', 'Honda_Civic_(eleventh_generation)',
    'Dacia_Duster', 'Citroën_C5_Aircross', 'Fiat_New_500',
    'Nissan_Qashqai', 'Suzuki_Vitara_(2015)', 'Porsche_Cayenne',
    'Range_Rover_Evoque', 'Lexus_NX', 'Alfa_Romeo_Giulia_(952)',
    'Cupra_Formentor', 'Mini_Hatch', 'Jeep_Compass'
];

$ctx = stream_context_create(['http' => ['header' => "User-Agent: AutonexSeeder/1.0\r\n", 'timeout' => 10]]);

foreach ($titles as $title) {
    $url = 'https://en.wikipedia.org/w/api.php?' . http_build_query([
        'action' => 'query',
        'titles' => $title,
        'generator' => 'images',
        'gimlimit' => 20,
        'prop' => 'imageinfo',
        'iiprop' => 'url|mime|size',
        'iiurlwidth' => 800,
        'format' => 'json',
    ]);

    $json = @file_get_contents($url, false, $ctx);
    if (!$json) {
        echo "FAIL: {$title}\n";
        continue;
    }

    $data = json_decode($json, true);
    $pages = $data['query']['pages'] ?? [];

    $photos = [];
    foreach ($pages as $page) {
        $info = $page['imageinfo'][0] ?? null;
        if (!$info) continue;

        $mime = $info['mime'] ?? '';
        $width = $info['width'] ?? 0;
        $ptitle = strtolower($page['title'] ?? '');

        if ($mime !== 'image/jpeg') continue;
        if ($width < 600) continue;
        if (preg_match('/logo|flag|icon|map|commons|symbol|coat|crash|accident|euro.?ncap/i', $ptitle)) continue;

        $photos[] = $info['thumburl'] ?? $info['url'];
    }

    echo sprintf("%-40s => %d photos\n", $title, count($photos));
}
