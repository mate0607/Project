<?php
// Test: get all images from a Wikipedia article

$titles = [
    'BMW_3_Series', 'Audi_A4', 'Mercedes-Benz_C-Class', 'Volkswagen_Golf',
    'Toyota_Corolla', 'Škoda_Octavia', 'Ford_Focus_(fourth_generation)',
    'Hyundai_Tucson', 'Opel_Astra', 'Suzuki_Vitara'
];

$ctx = stream_context_create(['http' => ['header' => "User-Agent: AutonexSeeder/1.0\r\n"]]);

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
        if (preg_match('/logo|flag|icon|map|commons|symbol|coat.of.arm/i', $ptitle)) continue;

        $photos[] = $info['thumburl'] ?? $info['url'];
    }

    echo sprintf("%-35s => %d photos\n", $title, count($photos));
    foreach (array_slice($photos, 0, 2) as $p) {
        echo "  {$p}\n";
    }
}
