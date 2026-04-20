<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    private static array $cars = [
        [
            'brand' => 'BMW', 'model' => '320d', 'vehicle_type' => 'Autó',
            'body_type' => 'Sedan', 'engine_cc' => 1998, 'fuel_type' => 'Dízel',
            'price_min' => 7_500_000, 'price_max' => 12_000_000,
            'description' => 'BMW 320d, dízel, automata váltó, bőr belső, LED fényszórók, tolatókamera, tempomat, sávtartó asszisztens. Szervizkönyves, márkaszervizben karbantartott.',
        ],
        [
            'brand' => 'Audi', 'model' => 'A4', 'vehicle_type' => 'Autó',
            'body_type' => 'Kombi', 'engine_cc' => 1968, 'fuel_type' => 'Dízel',
            'price_min' => 5_500_000, 'price_max' => 9_500_000,
            'description' => 'Audi A4 Avant 2.0 TDI, S-tronic váltó, MMI navigáció, fűthető bőrülések, panoráma tető, LED mátrix fényszóró. Garázsban tartott, dohányzásmentes.',
        ],
        [
            'brand' => 'Mercedes-Benz', 'model' => 'C 200', 'vehicle_type' => 'Autó',
            'body_type' => 'Sedan', 'engine_cc' => 1597, 'fuel_type' => 'Benzin',
            'price_min' => 9_000_000, 'price_max' => 15_000_000,
            'description' => 'Mercedes-Benz C 200, AMG Line csomag, MBUX multimédia, 360°-os kamera, adaptív tempomat, Burmester hangrendszer. Kifogástalan állapotban.',
        ],
        [
            'brand' => 'Volkswagen', 'model' => 'Golf', 'vehicle_type' => 'Autó',
            'body_type' => 'Hatchback', 'engine_cc' => 1498, 'fuel_type' => 'Benzin',
            'price_min' => 3_800_000, 'price_max' => 6_500_000,
            'description' => 'Volkswagen Golf 1.5 TSI, DSG váltó, digitális műszerfal, Apple CarPlay, Android Auto, adaptív tempomat. Rendszeres karbantartás, minden szerviz elvégezve.',
        ],
        [
            'brand' => 'Toyota', 'model' => 'Corolla', 'vehicle_type' => 'Autó',
            'body_type' => 'Kombi', 'engine_cc' => 1798, 'fuel_type' => 'Hibrid',
            'price_min' => 6_500_000, 'price_max' => 10_500_000,
            'description' => 'Toyota Corolla 1.8 Hybrid, automata, adaptív tempomat, sávtartó, LED fényszórók. Rendkívül takarékos, 4.5l/100km vegyes fogyasztás.',
        ],
        [
            'brand' => 'Škoda', 'model' => 'Octavia', 'vehicle_type' => 'Autó',
            'body_type' => 'Kombi', 'engine_cc' => 1968, 'fuel_type' => 'Dízel',
            'price_min' => 4_500_000, 'price_max' => 7_800_000,
            'description' => 'Skoda Octavia Combi 2.0 TDI, DSG, Style felszereltség, Canton hangrendszer, elektromos csomagtér, fűthető ülések. Rendszeres szerviz, vezérlés cserélve.',
        ],
        [
            'brand' => 'Ford', 'model' => 'Focus', 'vehicle_type' => 'Autó',
            'body_type' => 'Hatchback', 'engine_cc' => 1499, 'fuel_type' => 'Benzin',
            'price_min' => 3_200_000, 'price_max' => 5_500_000,
            'description' => 'Ford Focus 1.5 EcoBoost, ST-Line csomag, sportfutómű, SYNC 3 multimédia, hátsó parkolóradar, klíma. Megbízható, takarékos családi autó.',
        ],
        [
            'brand' => 'Hyundai', 'model' => 'Tucson', 'vehicle_type' => 'Autó',
            'body_type' => 'SUV', 'engine_cc' => 1598, 'fuel_type' => 'Hibrid',
            'price_min' => 9_500_000, 'price_max' => 14_000_000,
            'description' => 'Hyundai Tucson 1.6 T-GDi HEV, automata, Premium felszereltség, 360°-os kamera, digitális műszerfal, KRELL hangrendszer. Első tulajdonos.',
        ],
        [
            'brand' => 'Opel', 'model' => 'Astra', 'vehicle_type' => 'Autó',
            'body_type' => 'Hatchback', 'engine_cc' => 1199, 'fuel_type' => 'Benzin',
            'price_min' => 2_800_000, 'price_max' => 5_000_000,
            'description' => 'Opel Astra 1.2 Turbo, Pure Panel digitális kijelző, LED IntelliLux, holttér-figyelő, sávtartó. Kis fogyasztás, megbízható mindennapi autó.',
        ],
        [
            'brand' => 'Suzuki', 'model' => 'Vitara', 'vehicle_type' => 'Autó',
            'body_type' => 'SUV', 'engine_cc' => 1373, 'fuel_type' => 'Hibrid',
            'price_min' => 5_000_000, 'price_max' => 8_200_000,
            'description' => 'Suzuki Vitara 1.4 Boosterjet Hybrid, AllGrip 4x4, GLX felszereltség, adaptív tempomat, hátsó kamera, fűthető ülések. Megbízható japán minőség.',
        ],
    ];

    public function definition(): array
    {
        $car = fake()->randomElement(self::$cars);
        $sellerId = \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory();

        return [
            'car_id' => \App\Models\Car::factory(),
            'buyer_id' => null,
            'seller_id' => $sellerId,
            'vehicle_type' => $car['vehicle_type'],
            'brand' => $car['brand'],
            'model' => $car['model'],
            'body_type' => $car['body_type'],
            'engine_cc' => $car['engine_cc'],
            'fuel_type' => $car['fuel_type'],
            'price' => fake()->numberBetween($car['price_min'], $car['price_max']),
            'description' => $car['description'],
            'car_condition' => fake()->randomElement([
                'Újszerű',
                'Megkímélt',
                'Normál',
                'Sérült',
            ]),
            'mileage' => fake()->numberBetween(5_000, 200_000),
            'is_active' => true,
            'documents_available' => fake()->boolean(70),
            'document_type' => fake()->randomElement(['Szervizkönyv', 'Tulajdoni lap', 'Forgalmi engedély', null]),
            'technical_inspection' => fake()->boolean(60),
        ];
    }

    public function sold(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'buyer_id' => \App\Models\User::where('id', '!=', $attributes['seller_id'])->inRandomOrder()->value('id')
                    ?? \App\Models\User::factory(),
                'is_active' => false,
            ];
        });
    }
}
