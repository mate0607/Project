<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('sales');
        Storage::disk('public')->makeDirectory('sales');

        $sales = Sale::factory()->count(15)->create();

        foreach ($sales as $sale) {
            $imageUrls = $this->fetchCarImageUrls($sale->brand, $sale->model);
            $created = 0;

            foreach (array_slice($imageUrls, 0, 2) as $i => $imageUrl) {
                try {
                    $response = Http::timeout(20)
                        ->withHeaders(['User-Agent' => 'AutonexSeeder/1.0'])
                        ->withOptions(['allow_redirects' => true])
                        ->get($imageUrl);

                    if ($response->successful() && strlen($response->body()) > 1000) {
                        $contentType = $response->header('Content-Type') ?? '';
                        $ext = match (true) {
                            str_contains($contentType, 'png') => 'png',
                            str_contains($contentType, 'webp') => 'webp',
                            default => 'jpg',
                        };
                        $filename = 'sales/' . uniqid("sale_{$sale->id}_") . '.' . $ext;
                        Storage::disk('public')->put($filename, $response->body());

                        SaleImage::create([
                            'sale_id'    => $sale->id,
                            'path'       => $filename,
                            'sort_order' => $created,
                        ]);
                        $created++;
                        continue;
                    }
                } catch (\Exception $e) {
                    //
                }
            }

            // Fill remaining with SVG placeholders
            for ($i = $created; $i < 2; $i++) {
                $colors = ['#1a365d', '#2d3748', '#1e3a5f', '#2c5282', '#1a202c', '#2b6cb0'];
                $bg = $colors[array_rand($colors)];
                $label = htmlspecialchars("{$sale->brand} {$sale->model}", ENT_XML1);

                $svg = <<<SVG
                <svg xmlns="http://www.w3.org/2000/svg" width="800" height="500" viewBox="0 0 800 500">
                    <rect width="800" height="500" fill="{$bg}"/>
                    <text x="400" y="250" font-family="Arial,sans-serif" font-size="32" fill="#cbd5e0"
                          text-anchor="middle" dominant-baseline="middle">{$label}</text>
                </svg>
                SVG;

                $filename = 'sales/' . uniqid("sale_{$sale->id}_") . '.svg';
                Storage::disk('public')->put($filename, $svg);

                SaleImage::create([
                    'sale_id'    => $sale->id,
                    'path'       => $filename,
                    'sort_order' => $i,
                ]);
            }

            $this->command->info("  ✓ {$sale->brand} {$sale->model}");
        }
    }

    private function fetchCarImageUrls(string $brand, string $model): array
    {
        $urls = [];

        try {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'AutonexSeeder/1.0'])
                ->get('https://en.wikipedia.org/w/api.php', [
                    'action' => 'query',
                    'generator' => 'search',
                    'gsrsearch' => "{$brand} {$model} car",
                    'gsrlimit' => 1,
                    'prop' => 'pageimages',
                    'piprop' => 'thumbnail',
                    'pithumbsize' => 800,
                    'format' => 'json',
                ]);

            if ($response->successful()) {
                foreach ($response->json('query.pages') ?? [] as $page) {
                    if (isset($page['thumbnail']['source'])) {
                        $urls[] = $page['thumbnail']['source'];
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            //
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'AutonexSeeder/1.0'])
                ->get('https://commons.wikimedia.org/w/api.php', [
                    'action' => 'query',
                    'generator' => 'search',
                    'gsrsearch' => "{$brand} {$model}",
                    'gsrnamespace' => 6,
                    'gsrlimit' => 10,
                    'prop' => 'imageinfo',
                    'iiprop' => 'url|mime|size',
                    'iiurlwidth' => 800,
                    'format' => 'json',
                ]);

            if ($response->successful()) {
                foreach ($response->json('query.pages') ?? [] as $page) {
                    $info = $page['imageinfo'][0] ?? null;
                    if (!$info) continue;

                    $mime = $info['mime'] ?? '';
                    $width = $info['width'] ?? 0;

                    if (!in_array($mime, ['image/jpeg', 'image/png'])) continue;
                    if ($width < 400) continue;

                    $url = $info['thumburl'] ?? $info['url'] ?? null;
                    if ($url && !in_array($url, $urls)) {
                        $urls[] = $url;
                        if (count($urls) >= 3) break;
                    }
                }
            }
        } catch (\Exception $e) {
            //
        }

        return $urls;
    }
}
