<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Sale;
use App\Models\SaleImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RealSaleSeeder extends Seeder
{
    public function run(): void
    {
        // Clean up previously seeded data
        $oldSales = Sale::whereNotNull('car_id')
            ->whereHas('images')
            ->whereDoesntHave('car', fn($q) => $q->whereNotNull('user_id'))
            ->get()
            ->merge(Sale::whereNull('car_id')->get());

        foreach ($oldSales as $old) {
            foreach ($old->images as $img) {
                Storage::disk('public')->delete($img->path);
            }
            $old->images()->delete();
            $old->forceDelete();
        }

        // Ensure the sales directory exists
        Storage::disk('public')->makeDirectory('sales');

        $seller = User::where('role', 'admin')->first()
            ?? User::first()
            ?? User::factory()->create(['role' => 'admin']);

        $cars = $this->getCars();

        foreach ($cars as $i => $car) {
            // Create a Car record so messaging works
            $carRecord = Car::create([
                'user_id'       => $seller->id,
                'make_model'    => $car['brand'] . ' ' . $car['model'],
                'vin'           => strtoupper(fake()->bothify('??#??##??########')),
                'license_plate' => strtoupper(fake()->regexify('[A-Z]{3}-[0-9]{3}')),
                'year'          => fake()->numberBetween(2018, 2025),
            ]);

            $sale = Sale::create([
                'car_id'               => $carRecord->id,
                'seller_id'            => $seller->id,
                'buyer_id'             => null,
                'vehicle_type'         => $car['vehicle_type'],
                'brand'                => $car['brand'],
                'model'                => $car['model'],
                'body_type'            => $car['body_type'],
                'engine_cc'            => $car['engine_cc'],
                'fuel_type'            => $car['fuel_type'],
                'car_condition'        => $car['car_condition'],
                'price'                => $car['price'],
                'mileage'              => $car['mileage'],
                'description'          => $car['description'],
                'documents_available'  => $car['documents_available'],
                'document_type'        => $car['document_type'],
                'technical_inspection' => $car['technical_inspection'],
                'is_active'            => true,
            ]);

            // Download a real car image from picsum (landscape placeholder)
            $this->attachImages($sale, $car['brand'], $car['model'], $i);
        }

        $this->command->info('30 realistic sales with images seeded successfully.');
    }

    private function attachImages(Sale $sale, string $brand, string $model, int $index): void
    {
        $kepekPath = base_path('kepek');
        if (!is_dir($kepekPath)) {
            return;
        }

        $prefix = mb_strtolower(trim("{$brand} {$model}"));
        $files = collect(File::files($kepekPath))
            ->filter(fn ($f) => str_starts_with(mb_strtolower($f->getFilename()), $prefix))
            ->sortBy(fn ($f) => $f->getFilename())
            ->values()
            ->take(4);

        // If no exact match, try brand-only prefix
        if ($files->isEmpty()) {
            $brandLower = mb_strtolower($brand);
            $files = collect(File::files($kepekPath))
                ->filter(fn ($f) => str_starts_with(mb_strtolower($f->getFilename()), $brandLower))
                ->sortBy(fn ($f) => $f->getFilename())
                ->values()
                ->take(4);
        }

        foreach ($files as $i => $file) {
            $ext = $file->getExtension() ?: 'avif';
            $filename = 'sales/' . uniqid("sale_{$sale->id}_") . '.' . $ext;
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
                'vehicle_type' => 'Autó', 'brand' => 'BMW', 'model' => '3-as',
                'body_type' => 'Sedan', 'engine_cc' => 1998, 'fuel_type' => 'Dízel',
                'car_condition' => 'Újszerű', 'price' => 8_500_000, 'mileage' => 45_000,
                'description' => 'BMW 320d, 2021-es évjárat, automata váltó, bőr belső, LED fényszórók, tolatókamera, tempomat, sávtartó asszisztens. Szervizkönyves, márkaszervizben karbantartott. Első tulajdonostól.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Audi', 'model' => 'A4',
                'body_type' => 'Kombi', 'engine_cc' => 1968, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 6_200_000, 'mileage' => 112_000,
                'description' => 'Audi A4 Avant 2.0 TDI, S-tronic váltó, MMI navigáció, fűthető bőrülések, panoráma tető, LED mátrix fényszóró. Garázsban tartott, dohányzásmentes.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Mercedes-Benz', 'model' => 'C-osztály',
                'body_type' => 'Sedan', 'engine_cc' => 1597, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 12_500_000, 'mileage' => 18_000,
                'description' => 'Mercedes-Benz C 180, 2023-as évjárat, AMG Line csomag, MBUX multimédia, 360°-os kamera, adaptív tempomat, Burmester hangrendszer. Gyári garancia 2025-ig.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Volkswagen', 'model' => 'Golf',
                'body_type' => 'Ferdehátú', 'engine_cc' => 1498, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 4_800_000, 'mileage' => 87_000,
                'description' => 'Volkswagen Golf VIII 1.5 TSI, 2020-as, DSG váltó, digitális műszerfal, Apple CarPlay, Android Auto, adaptív tempomat. Rendszeres karbantartás, minden szerviz elvégezve.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Toyota', 'model' => 'Corolla',
                'body_type' => 'Kombi', 'engine_cc' => 1798, 'fuel_type' => 'Hibrid',
                'car_condition' => 'Újszerű', 'price' => 7_900_000, 'mileage' => 32_000,
                'description' => 'Toyota Corolla Touring Sports 1.8 Hybrid, automata, adaptív tempomat, sávtartó, LED fényszórók. Rendkívül takarékos, 4.5l/100km vegyes fogyasztás. Gyári garancia.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Skoda', 'model' => 'Octavia',
                'body_type' => 'Kombi', 'engine_cc' => 1968, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 5_400_000, 'mileage' => 135_000,
                'description' => 'Skoda Octavia Combi 2.0 TDI, DSG, Style felszereltség, Canton hangrendszer, elektromos csomagtér, fűthető ülések. Rendszeres szerviz, vezérlés cserélve.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Ford', 'model' => 'Focus',
                'body_type' => 'Ferdehátú', 'engine_cc' => 1499, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 3_900_000, 'mileage' => 94_000,
                'description' => 'Ford Focus 1.5 EcoBoost, ST-Line csomag, sportfutómű, SYNC 3 multimédia, hátsó parkolóradar, klíma. Megbízható, takarékos családi autó.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Hyundai', 'model' => 'Tucson',
                'body_type' => 'SUV', 'engine_cc' => 1598, 'fuel_type' => 'Hibrid',
                'car_condition' => 'Újszerű', 'price' => 11_200_000, 'mileage' => 22_000,
                'description' => 'Hyundai Tucson 1.6 T-GDi HEV, automata, Premium felszereltség, 360°-os kamera, digitális műszerfal, KRELL hangrendszer, ventillált ülések. Első tulajdonos.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Kia', 'model' => 'Sportage',
                'body_type' => 'SUV', 'engine_cc' => 1598, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 9_800_000, 'mileage' => 58_000,
                'description' => 'Kia Sportage 1.6 CRDi, GT-Line, automata, panoráma tető, Head-Up Display, JBL hangrendszer, adaptív tempomat. 7 év gyári garancia még érvényes.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Mazda', 'model' => 'CX-5',
                'body_type' => 'SUV', 'engine_cc' => 2488, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 10_500_000, 'mileage' => 28_000,
                'description' => 'Mazda CX-5 2.5 SKYACTIV-G, AWD, automata, Takumi Plus csomag, bőr belső, Bose hangrendszer, HUD, 360°-os kamera. Kiemelkedő minőségérzet.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Peugeot', 'model' => '3008',
                'body_type' => 'SUV', 'engine_cc' => 1499, 'fuel_type' => 'Dízel',
                'car_condition' => 'Normál', 'price' => 6_800_000, 'mileage' => 78_000,
                'description' => 'Peugeot 3008 1.5 BlueHDi, automata, i-Cockpit, GT-Line, LED fényszóró, hátsó kamera, holttérfigyelő. Francia elegancia, alacsony fogyasztás.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Opel', 'model' => 'Astra',
                'body_type' => 'Ferdehátú', 'engine_cc' => 1199, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 3_200_000, 'mileage' => 105_000,
                'description' => 'Opel Astra 1.2 Turbo, 2020-as, Pure Panel digitális kijelző, LED IntelliLux, holttér-figyelő, sávtartó. Kis fogyasztás, megbízható mindennapi autó.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Renault', 'model' => 'Mégane',
                'body_type' => 'Ferdehátú', 'engine_cc' => 1332, 'fuel_type' => 'Benzin',
                'car_condition' => 'Megkímélt', 'price' => 4_200_000, 'mileage' => 72_000,
                'description' => 'Renault Mégane 1.3 TCe, Intens csomag, R-Link multimédia, BOSE hangrendszer, LED Pure Vision, 360°-os kamera. Friss műszaki, kitűnő állapotban.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Seat', 'model' => 'Leon',
                'body_type' => 'Kombi', 'engine_cc' => 1498, 'fuel_type' => 'Dízel',
                'car_condition' => 'Normál', 'price' => 4_500_000, 'mileage' => 98_000,
                'description' => 'Seat Leon Sportstourer 1.5 TDI, FR csomag, virtuális műszerfal, Full LED, tolatókamera, adaptív tempomat. Sportos megjelenés, takarékos fogyasztás.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Tesla', 'model' => 'Model 3',
                'body_type' => 'Sedan', 'engine_cc' => 0, 'fuel_type' => 'Elektromos',
                'car_condition' => 'Újszerű', 'price' => 14_500_000, 'mileage' => 25_000,
                'description' => 'Tesla Model 3 Long Range, dual motor, AWD, autopilot, 15"-os érintőképernyő, üvegtető, fehér prémium belső. 500+ km hatótáv, Supercharger hozzáférés.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Volvo', 'model' => 'XC60',
                'body_type' => 'SUV', 'engine_cc' => 1969, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 13_200_000, 'mileage' => 65_000,
                'description' => 'Volvo XC60 D4 AWD, Inscription csomag, Pilot Assist, Harman Kardon hangrendszer, bőr belső, panoráma tető, adaptív futómű. Skandináv luxus.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Honda', 'model' => 'Civic',
                'body_type' => 'Ferdehátú', 'engine_cc' => 1498, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 5_600_000, 'mileage' => 67_000,
                'description' => 'Honda Civic 1.5 VTEC Turbo, Sport csomag, Honda Sensing biztonsági rendszer, Apple CarPlay, LED fényszóró, sportülések. Japán megbízhatóság.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Dacia', 'model' => 'Duster',
                'body_type' => 'SUV', 'engine_cc' => 1332, 'fuel_type' => 'Benzin',
                'car_condition' => 'Új', 'price' => 6_900_000, 'mileage' => 5_000,
                'description' => 'Dacia Duster 1.3 TCe, Extreme csomag, 4x4, multimédia navigáció, 360°-os kamera, automata klíma, LED fényszóró. Kiváló ár-érték arány, szinte új.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Citroën', 'model' => 'C5 Aircross',
                'body_type' => 'SUV', 'engine_cc' => 1499, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 7_400_000, 'mileage' => 55_000,
                'description' => 'Citroën C5 Aircross 1.5 BlueHDi, automata, Shine csomag, Advanced Comfort ülések, grip control, Head-Up Display, hátsó kamera. Kényelmes családi SUV.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Fiat', 'model' => '500',
                'body_type' => 'Ferdehátú', 'engine_cc' => 999, 'fuel_type' => 'Benzin',
                'car_condition' => 'Megkímélt', 'price' => 3_600_000, 'mileage' => 42_000,
                'description' => 'Fiat 500 1.0 Hybrid, Dolcevita felszereltség, üvegtető, bőr belső, 7" Uconnect, Apple CarPlay, parkolóradar. Ikonikus városi autó, nagyon jó állapotban.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Nissan', 'model' => 'Qashqai',
                'body_type' => 'SUV', 'engine_cc' => 1332, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 9_200_000, 'mileage' => 35_000,
                'description' => 'Nissan Qashqai 1.3 DIG-T, Tekna+ csomag, ProPilot, Bose hangrendszer, panoráma tető, süllyeszthető hátsó ülés, e-Pedal. Modern crossover, teljes extrákkal.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Suzuki', 'model' => 'Vitara',
                'body_type' => 'SUV', 'engine_cc' => 1373, 'fuel_type' => 'Hibrid',
                'car_condition' => 'Normál', 'price' => 5_800_000, 'mileage' => 68_000,
                'description' => 'Suzuki Vitara 1.4 Boosterjet Hybrid, AllGrip 4x4, GLX felszereltség, adaptív tempomat, hátsó kamera, fűthető ülések. Megbízható japán minőség.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Porsche', 'model' => 'Cayenne',
                'body_type' => 'SUV', 'engine_cc' => 2995, 'fuel_type' => 'Benzin',
                'car_condition' => 'Megkímélt', 'price' => 28_500_000, 'mileage' => 48_000,
                'description' => 'Porsche Cayenne 3.0 V6, Sport Chrono, levegős futómű, BOSE, panoráma tető, 21" felni, mátrix LED, hátsó kormányzás. Álomautó, kifogástalan állapotban.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Land Rover', 'model' => 'Range Rover Evoque',
                'body_type' => 'SUV', 'engine_cc' => 1999, 'fuel_type' => 'Dízel',
                'car_condition' => 'Újszerű', 'price' => 16_800_000, 'mileage' => 30_000,
                'description' => 'Range Rover Evoque 2.0 D200, R-Dynamic SE, Meridian hangrendszer, ClearSight tükör, panoráma tető, elektromos ülések, fűthető kormány. Brit elegancia.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Lexus', 'model' => 'NX',
                'body_type' => 'SUV', 'engine_cc' => 2487, 'fuel_type' => 'Hibrid',
                'car_condition' => 'Újszerű', 'price' => 18_200_000, 'mileage' => 15_000,
                'description' => 'Lexus NX 350h, AWD, Luxury Line, Mark Levinson hangrendszer, bőr belső, adaptív futómű, 360°-os kamera, head-up display. Lexus minőség és megbízhatóság.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Alfa Romeo', 'model' => 'Giulia',
                'body_type' => 'Sedan', 'engine_cc' => 2143, 'fuel_type' => 'Dízel',
                'car_condition' => 'Megkímélt', 'price' => 8_900_000, 'mileage' => 72_000,
                'description' => 'Alfa Romeo Giulia 2.2 JTDM, Veloce csomag, Harman Kardon, bőr/Alcantara belső, 19" felni, adaptív futómű, Apple CarPlay. Olasz temperamentum, kiváló vezetési élmény.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Cupra', 'model' => 'Formentor',
                'body_type' => 'SUV', 'engine_cc' => 1498, 'fuel_type' => 'Benzin',
                'car_condition' => 'Újszerű', 'price' => 11_500_000, 'mileage' => 20_000,
                'description' => 'Cupra Formentor 1.5 TSI, DSG, VZ csomag, Beats hangrendszer, virtuális műszerfal, sport ülések, progresszív kormányzás, dinamikus futómű. Sportos crossover.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Mini', 'model' => 'Cooper',
                'body_type' => 'Ferdehátú', 'engine_cc' => 1499, 'fuel_type' => 'Benzin',
                'car_condition' => 'Megkímélt', 'price' => 6_400_000, 'mileage' => 40_000,
                'description' => 'Mini Cooper S, John Cooper Works csomag, sport futómű, Harman Kardon, LED, head-up display, panoráma tető. Ikonikus dizájn, go-kart élmény az utcán.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
            [
                'vehicle_type' => 'Autó', 'brand' => 'Jeep', 'model' => 'Compass',
                'body_type' => 'SUV', 'engine_cc' => 1332, 'fuel_type' => 'Benzin',
                'car_condition' => 'Normál', 'price' => 7_200_000, 'mileage' => 62_000,
                'description' => 'Jeep Compass 1.3 Turbo, Limited csomag, 4x4, Uconnect 10.1", bőr belső, adaptív tempomat, holttér-figyelő, elektromos csomagtér. Amerikai stílus, olasz technológia.',
                'documents_available' => true, 'document_type' => 'Forgalmi, Törzskönyv', 'technical_inspection' => true,
            ],
        ];
    }
}
