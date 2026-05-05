<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Sale;
use App\Models\SaleImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $this->cleanupExistingSales();

        Storage::disk('public')->deleteDirectory('sales');
        Storage::disk('public')->makeDirectory('sales');

        $seller = User::where('role', 'admin')->first()
            ?? User::first()
            ?? User::factory()->create(['role' => 'admin']);

        foreach ($this->getCars() as $carData) {
            $carRecord = Car::create([
                'user_id'       => $seller->id,
                'make_model'    => $carData['brand'] . ' ' . $carData['model'],
                'vin'           => strtoupper(fake()->bothify('??#??##??########')),
                'license_plate' => strtoupper(fake()->regexify('[A-Z]{3}-[0-9]{3}')),
                'year'          => fake()->numberBetween(2018, 2025),
            ]);

            $sale = Sale::forceCreate([
                'car_id'               => $carRecord->id,
                'seller_id'            => $seller->id,
                'vehicle_type'         => $carData['vehicle_type'],
                'brand'                => $carData['brand'],
                'model'                => $carData['model'],
                'body_type'            => $carData['body_type'],
                'engine_cc'            => $carData['engine_cc'],
                'fuel_type'            => $carData['fuel_type'],
                'car_condition'        => $carData['car_condition'],
                'price'                => $carData['price'],
                'mileage'              => $carData['mileage'],
                'description'          => $carData['description'],
                'documents_available'  => $carData['documents_available'],
                'document_type'        => $carData['document_type'],
                'technical_inspection' => $carData['technical_inspection'],
                'is_active'            => true,
            ]);

            $this->attachImages($sale, $carData['image_prefix']);

            $this->command->info("  ✓ {$carData['brand']} {$carData['model']}");
        }
    }

    private function cleanupExistingSales(): void
    {
        $sales = Sale::with(['images', 'car'])->get();

        foreach ($sales as $sale) {
            foreach ($sale->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            $sale->images()->delete();

            $car = $sale->car;
            $sale->forceDelete();

            if ($car
                && !$car->appointments()->exists()
                && !$car->issues()->exists()
                && !$car->messages()->exists()
                && !$car->sales()->withTrashed()->exists()) {
                $car->forceDelete();
            }
        }
    }

    private function attachImages(Sale $sale, string $prefix): void
    {
        $kepekPath = base_path('kepek');
        $files = collect(File::files($kepekPath))
            ->filter(fn ($f) => str_starts_with(mb_strtolower($f->getFilename()), mb_strtolower($prefix)))
            ->sortBy(fn ($f) => $f->getFilename())
            ->values()
            ->take(4);

        foreach ($files as $i => $file) {
            $filename = 'sales/' . uniqid("sale_{$sale->id}_") . '.avif';
            Storage::disk('public')->put($filename, File::get($file->getPathname()));

            SaleImage::create([
                'sale_id'    => $sale->id,
                'path'       => $filename,
                'sort_order' => $i,
            ]);
        }
    }

    private function getCars(): array
    {
        return [
            [
                'vehicle_type' => 'Autó', 'brand' => 'Alfa Romeo', 'model' => 'Giulia',
                'body_type' => 'Sedan', 'engine_cc' => 2143, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 8_900_000, 'mileage' => 72_000,
                'description' => 'Alfa Romeo Giulia 2.2 JTDM, Veloce csomag, Harman Kardon, bőr/Alcantara belső, 19" felni, adaptív futómű, Apple CarPlay. Olasz temperamentum, kiváló vezetési élmény.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'alfa romeo gulia',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Audi', 'model' => 'Q8',
                'body_type' => 'SUV', 'engine_cc' => 2995, 'fuel_type' => 'Dízel',
                'car_condition' => 'Újszerű', 'price' => 24_500_000, 'mileage' => 35_000,
                'description' => 'Audi Q8 3.0 TDI quattro, S-Line, Matrix LED, Bang & Olufsen, légfelfüggesztés, panoráma tető, head-up display. Prémium SUV, teljesen felszerelt.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'audi q8',
            ],
            [
                'vehicle_type' => 'Kis teherautó', 'brand' => 'Fiat', 'model' => 'Ducato',
                'body_type' => 'Furgon', 'engine_cc' => 2287, 'fuel_type' => 'Dízel',
                'car_condition' => 'Normál', 'price' => 6_800_000, 'mileage' => 145_000,
                'description' => 'Fiat Ducato 2.3 MultiJet, L2H2, klíma, tempomat, tolatóradar. Megbízható haszongépjármű, rendszeres szerviz.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'fiat ducato',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Ford', 'model' => 'Puma',
                'body_type' => 'SUV', 'engine_cc' => 999, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 7_200_000, 'mileage' => 28_000,
                'description' => 'Ford Puma 1.0 EcoBoost Hybrid, ST-Line, digitális műszerfal, B&O hangrendszer, LED fényszóró, hátsó kamera. Sportos crossover, rendkívül takarékos.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'ford puma',
            ],
            [
                'vehicle_type' => 'Motor', 'brand' => 'Honda', 'model' => 'Gold Wing',
                'body_type' => 'Túra', 'engine_cc' => 1833, 'fuel_type' => 'Benzin',
                'car_condition' => 'Megkímélt', 'price' => 9_500_000, 'mileage' => 42_000,
                'description' => 'Honda GL 1800 Gold Wing DCT, full extra, fűthető ülés és markolat, elektromos szélvédő, tempomat, Apple CarPlay. A túramotorok királya.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'Honda GL 1800',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Jeep', 'model' => 'Cherokee',
                'body_type' => 'SUV', 'engine_cc' => 2184, 'fuel_type' => 'Dízel',
                'car_condition' => 'Normál', 'price' => 8_200_000, 'mileage' => 95_000,
                'description' => 'Jeep Cherokee 2.2 MultiJet, Limited, 4x4, bőr belső, Uconnect 8.4", adaptív tempomat, holttér-figyelő. Amerikai stílus, olasz technológia.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'jeep cherokee',
            ],
            [
                'vehicle_type' => 'Motor', 'brand' => 'Kawasaki', 'model' => 'Ninja ZX-10R',
                'body_type' => 'Sport', 'engine_cc' => 998, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 7_800_000, 'mileage' => 8_000,
                'description' => 'Kawasaki Ninja H2R, szupercharger, 310+ LE, öntörvényű szupersport. Pályahasználatra optimalizált, exkluzív darab.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'kawasaki h2R',
            ],
            [
                'vehicle_type' => 'Motor', 'brand' => 'KTM', 'model' => '1290 Super Duke R',
                'body_type' => 'Naked', 'engine_cc' => 1301, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 12_500_000, 'mileage' => 5_000,
                'description' => 'KTM 1290 Super Duke R BRABUS Edition, limitált széria, 180 LE, Akrapovič kipufogó, WP APEX futómű, TFT műszerfal. A naked bike csúcsa.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'KTM 1290',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Lexus', 'model' => 'CT',
                'body_type' => 'Hatchback', 'engine_cc' => 1798, 'fuel_type' => 'Hibrid',
                'car_condition' => 'Megkímélt', 'price' => 5_400_000, 'mileage' => 85_000,
                'description' => 'Lexus CT 200h, hibrid, automata, bőr belső, Mark Levinson hangrendszer, LED fényszóró, tolatókamera. Lexus megbízhatóság, alacsony fogyasztás.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'lexus ct200h',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Mercedes-Benz', 'model' => 'E-osztály',
                'body_type' => 'Sedan', 'engine_cc' => 3982, 'fuel_type' => 'Benzin',
                'car_condition' => 'Megkímélt', 'price' => 18_500_000, 'mileage' => 62_000,
                'description' => 'Mercedes-Benz E 63 AMG, 571 LE, 4MATIC+, AMG Performance csomag, Burmester hangrendszer, légfelfüggesztés, panoráma tető. Brutális erő, limuzin kényelem.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'Mercedes-Benz E 63',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Opel', 'model' => 'Astra',
                'body_type' => 'Hatchback', 'engine_cc' => 1199, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 3_800_000, 'mileage' => 78_000,
                'description' => 'Opel Astra L 1.2 Turbo, Pure Panel digitális kijelző, LED IntelliLux, holttér-figyelő, sávtartó. Kis fogyasztás, megbízható mindennapi autó.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'opel astra L',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Peugeot', 'model' => '206',
                'body_type' => 'Kabrió', 'engine_cc' => 1587, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 1_800_000, 'mileage' => 142_000,
                'description' => 'Peugeot 206 CC 1.6, elektromos keménytetős kabrió, bőr belső, klíma, CD-lejátszó. Klasszikus nyári autó, jó állapotban.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'peugeot 206 cc',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Porsche', 'model' => '911',
                'body_type' => 'Kupé', 'engine_cc' => 2981, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 52_000_000, 'mileage' => 12_000,
                'description' => 'Porsche 911 (992) Carrera S, PDK, Sport Chrono, PASM, Bose, SportDesign csomag, 20/21" felni. Álomautó, kifogástalan állapotban.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'porsche 992',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Saab', 'model' => '9-3',
                'body_type' => 'Sedan', 'engine_cc' => 1998, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 2_200_000, 'mileage' => 178_000,
                'description' => 'Saab 9-3 2.0t, turbó, automata, bőr belső, fűthető ülések, xenon fényszóró. Svéd klasszikus, egyedi karakter, jól karbantartott.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'saab 9-3',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Škoda', 'model' => 'Octavia',
                'body_type' => 'Kombi', 'engine_cc' => 1968, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 5_400_000, 'mileage' => 135_000,
                'description' => 'Škoda Octavia Combi 2.0 TDI, DSG, Style felszereltség, Canton hangrendszer, elektromos csomagtér, fűthető ülések. Rendszeres szerviz, vezérlés cserélve.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'skoda octavia',
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Toyota', 'model' => 'Yaris',
                'body_type' => 'Hatchback', 'engine_cc' => 1490, 'fuel_type' => 'Hibrid',
                'car_condition' => 'Újszerű', 'price' => 6_200_000, 'mileage' => 22_000,
                'description' => 'Toyota Yaris 1.5 Hybrid, automata, Toyota Safety Sense, LED fényszóró, hátsó kamera, digitális műszerfal. Japán megbízhatóság, alacsony fogyasztás.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
                'image_prefix' => 'toyota yaris',
            ],
        ];
    }
}
