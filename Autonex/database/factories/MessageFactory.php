<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    private static array $messages = [
        'Szia! Érdekelne az autó, még elérhető?',
        'Hello! Meg tudnád mondani, hogy mikor lehet megnézni személyesen?',
        'Jó napot! Az ár végleges, vagy alkuképes?',
        'Üdv! Mennyi km van az órában? Friss műszaki van rajta?',
        'Szia! Csere érdekelne, vagy csak eladó?',
        'Helló! Volt-e balesete az autónak?',
        'Jó napot kívánok! Szervizkönyves az autó?',
        'Szia! Próbaútra lenne lehetőség hétvégén?',
        'Üdvözlöm! Az autó első tulajdonostól van?',
        'Hello! Mennyire sürgős az eladás? Van mozgástér az árban?',
        'Szia! A vezérlés mikor lett cserélve?',
        'Jó napot! Téli gumi is jár hozzá?',
        'Szia! Milyen állapotban van a futómű?',
        'Üdv! Lízingelhető az autó, vagy csak készpénzre?',
        'Helló! Van még garancia az autón?',
        'Igen, még elérhető! Mikor szeretnéd megnézni?',
        'Az ár fix, de személyes megtekintés után beszélhetünk.',
        'Szervizkönyves, minden karbantartás megvan. Szívesen megmutatom.',
        'Hétvégén bármikor megnézheted, egyeztessünk időpontot.',
        'Nem volt balesete, garázs autó, első tulajdonostól.',
        'Igen, a vezérlés 20 000 km-rel ezelőtt lett cserélve.',
        'Csere nem érdekel, csak eladó. Az ár kissé alkuképes.',
        'Téli gumi is van hozzá, alufelni szettel együtt.',
        'Igen, próbaút lehetséges, hívj fel és megbeszéljük!',
        'Friss műszaki van rajta, 2 évre érvényes.',
        'Köszönöm az érdeklődést! Holnap délután ráérek megmutatni.',
        'Rendben, akkor szombaton délelőtt találkozunk!',
        'Megegyeztünk, köszi! Várom a visszajelzésedet.',
        'Szia! Esetleg részletfizetés lehetséges?',
        'Üdv! Mennyit fogyaszt városban és országúton?',
    ];

    public function definition(): array
    {
        return [
            'car_id' => \App\Models\Car::factory(),
            'sender_id' => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            'receiver_id' => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            'message' => fake()->randomElement(self::$messages),
            'is_read' => fake()->boolean(),
        ];
    }
}
