# Autonex – Műszaki Dokumentáció

**Szerzők:** Patkos Dominik, Molnár Attila, Fodor Tamás  
**Verzió:** 1.0  
**Dátum:** 2026-04-20

---

## Tartalomjegyzék

1. [Projekt áttekintés](#1-projekt-áttekintés)
2. [Backend architektúra](#2-backend-architektúra)
3. [Backend autentikáció](#3-backend-autentikáció)
4. [Backend fiókkezelés és e-mail folyamatok](#4-backend-fiókkezelés-és-e-mail-folyamatok)
5. [Backend adatmodellek](#5-backend-adatmodellek)
6. [Backend web és API referencia](#6-backend-web-és-api-referencia)
7. [Backend hibakezelés](#7-backend-hibakezelés)
8. [Backend piactéri állapotgép](#8-backend-piactéri-állapotgép)
9. [Backend keresés, szűrés és tömeges műveletek](#9-backend-keresés-szűrés-és-tömeges-műveletek)
10. [Backend munkafolyamatok](#10-backend-munkafolyamatok)
11. [Backend fájltárolás](#11-backend-fájltárolás)
12. [Backend üzenetsorok és ütemezés](#12-backend-üzenetsorok-és-ütemezés)
13. [Jogosultságok és hozzáférési mátrix](#13-jogosultságok-és-hozzáférési-mátrix)
14. [Frontend architektúra](#14-frontend-architektúra)
15. [Frontend autentikáció és munkamenet](#15-frontend-autentikáció-és-munkamenet)
16. [Frontend komponens- és nézettár](#16-frontend-komponens--és-nézettár)
17. [Frontend útvonalkezelés és navigáció](#17-frontend-útvonalkezelés-és-navigáció)
18. [Frontend állapotkezelés és integráció](#18-frontend-állapotkezelés-és-integráció)
19. [Frontend betöltőrendszer és UX visszajelzések](#19-frontend-betöltőrendszer-és-ux-visszajelzések)
20. [Frontend felhasználói folyamatok](#20-frontend-felhasználói-folyamatok)
21. [Frontend stílusrendszer](#21-frontend-stílusrendszer)
22. [Frontend űrlapok és validáció](#22-frontend-űrlapok-és-validáció)
23. [Frontend hibakezelés](#23-frontend-hibakezelés)
24. [Környezet és deployment](#24-környezet-és-deployment)
25. [Helyi fejlesztési környezet](#25-helyi-fejlesztési-környezet)
26. [Hibaelhárítás](#26-hibaelhárítás)
27. [Összefoglalás](#27-összefoglalás)
28. [A melléklet – Végpontmátrix](#28-a-melléklet--végpontmátrix)
29. [B melléklet – Adatmodell-szótár](#29-b-melléklet--adatmodell-szótár)
30. [C melléklet – Üzemeltetési ellenőrzőlisták](#30-c-melléklet--üzemeltetési-ellenőrzőlisták)

---

## 1. Projekt áttekintés

### 1.1 Célkitűzés

Az **Autonex** egy integrált autós szerviz- és piactéri rendszer. A platform két kulcsproblémát kezel egyetlen rendszerben:

- jármű- és szervizfolyamatok adminisztrációja,
- használt jármű hirdetések kezelése többképes piactéri funkcióval.

A termék értéke abban rejlik, hogy ugyanazon felhasználói fiókon belül egyesíti:

- a saját autó nyilvántartást,
- a hibajegy-kezelést,
- az időpont-foglalást,
- a szervizállapot-követést,
- a hirdetések kezelését,
- a valós idejű jellegű üzenetváltást.

### 1.2 Rendszerhatár

Az alkalmazás Laravel 12 alapú monolit webalkalmazás, Blade nézetekkel. A backend és a frontend egy kódbázisban fut, különálló SPA frontend jelenleg nincs. A kiszolgálás két rétegen történik:

- web route-ok (főbb funkcionalitás),
- egyszerű API route csoport a jármű típusválasztó adatokhoz.

### 1.3 Fő modulok

- Felhasználó- és szerepkörkezelés (`admin`, normál felhasználó).
- Autókezelés (CRUD, tulajdonosi kötés).
- Szervizidőpont-kezelés (felhasználói + admin munkafolyamat).
- Hibajegy-kezelés (issue modul).
- Piactér/hirdetés-kezelés (eladási hirdetések + hirdetési képek).
- Üzenetkezelő (autóhoz és hirdetéshez kapcsolt beszélgetés).
- Értesítési modul (admin értesítés + olvasatlan állapotok).
- Irányítópultok (admin és felhasználói nézetek).

### 1.4 Nem-célok

A jelenlegi implementáció nem általános e-kereskedelmi rendszer:

- nincs teljes kártyafeldolgozó-integrációs fizetési folyamat,
- nincs különálló mikroszerviz-architektúra,
- nincs külön mobil API-verziókezelés.

### 1.5 Repozitórium szerkezete

- `app/` – domain logika: kontrollerek, modellek, policy-k, middleware.
- `routes/` – web, API, konzol route-definíciók.
- `resources/views/` – Blade oldalak és résznézetek.
- `database/` – migrációk, factory-k, seederek.
- `config/` – alkalmazás- és domain-konfiguráció (`vehicles.php`).
- `public/` – web root és statikus állományok.
- `storage/` – futási állapot, naplók, feltöltött tartalom.

### 1.6 Fő domain-entitások

- User
- Car
- Appointment
- Issue
- Sale
- SaleImage
- Message
- AdminNotification
- ServicePhoto

### 1.7 Üzleti képességek összefoglalva

A felhasználó rögzít egy autót, bejelent hibákat, időpontot foglal, követi a szerviz állapotát, közben hirdetést adhat fel, és az adminnal vagy más érintettel beágyazott üzenetváltásban tud egyeztetni.

---

## 2. Backend architektúra

### 2.1 Technológiai stack

| Technológia | Verzió / Típus |
|---|---|
| PHP | 8.2+ |
| Laravel | 12 |
| Adatbázis | MySQL / MariaDB (Eloquent ORM) |
| Nézetek | Blade templating |
| Auth | Laravel UI auth scaffold |
| Build | Vite |
| Stílus | Bootstrap 5 + Sass |
| Üzenetsor | database driver (fejlesztői scriptben queue listener) |

### 2.2 Architektúrális modell

A projekt klasszikus MVC-rétegekből épül fel:

1. Route réteg (`routes/web.php`, `routes/api.php`)
2. Middleware réteg (auth, admin alias)
3. Kontroller réteg (üzleti folyamatok koordinálása)
4. Request validációs réteg (FormRequest osztályok)
5. Model réteg (Eloquent relációk + cast-ok + soft delete)
6. View réteg (Blade nézetek)

### 2.3 Kulcs architektúrális döntések

- Admin jogosultság egyedi middleware alapon (`admin` alias).
- Finomabb jogosultságok policy osztályokkal.
- Resource route-okra épített konvencionális CRUD.
- Hirdetési képek külön táblában (`sale_images`), rendezett `sort_order` mezővel.
- Soft delete több fő entitásnál (`cars`, `appointments`, `sales`, `messages`).

### 2.4 Integrációs pontok

- E-mail küldés időpont-visszaigazoláshoz.
- Kliens oldali aszinkron üzenetfrissítés JSON endpontokon.
- Konfigurált, de nem túlbonyolított API réteg jármű adatokhoz.

### 2.5 A monolit előnyei

- Egyszerű deployment.
- Egyértelmű tranzakciós határok.
- Egyutas auth/session modell.
- Kisebb konzisztenciaproblémák, mint szétszedett mikroszolgáltatóknál.

### 2.6 A monolit korlátai

- Frontend és backend release nem független egymástól.
- Nagyobb kódbázisnál erős modularizáció szükséges.
- Horizontális skálázás MVC monolitnál korlátozottabb, mint különálló API + frontend architektúránál.

---

## 3. Backend autentikáció

### 3.1 Alapmodell

Az alkalmazás alapvetően session/cookie alapú webes autentikációt használ (`Auth::routes()`), nem token-centrikus API autentikációt.

### 3.2 Route-védelem

A web route-ok a következő mintát követik:

- nyitott route: `/`
- autentikáció-védett: irányítópult, profil, autók, időpontok, hibajegyek, üzenetek
- admin-védett: admin irányítópult, admin időpontkezelés, admin értesítéskezelés, hirdetésmódosító route-ok

### 3.3 Admin-ellenőrzés

Az admin hozzáférést az egyedi middleware kezeli:

- belépési feltétel: bejelentkezett felhasználó + `isAdmin() === true`
- sikertelen esetben átirányítás a `/` gyökérre

**Admin middleware** (`bootstrap/app.php`):
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminDashboardController::class, 'index']);
    Route::resource('sales', SaleController::class);
});
```

**Middleware osztály** (`app/Http/Middleware/AdminMiddleware.php`):
```php
public function handle(Request $request, Closure $next)
{
    if (!auth()->user()?->isAdmin()) {
        return redirect('/');
    }
    return $next($request);
}
```

### 3.4 User modell – szerepkör

A User modell `isAdmin()` helperrel adja vissza a szerepkört (`role === 'admin'`).

### 3.5 Biztonsági megjegyzés

Az admin middleware jelenleg átirányítást ad nem jogosult esetben. API-jellegű végpontoknál hosszabb távon ajánlott lenne státuszkód + JSON policy (403) fenntartása a következetesség miatt.

---

## 4. Backend fiókkezelés és e-mail folyamatok

### 4.1 Fiókfunkciók

- Regisztráció / bejelentkezés / kijelentkezés (Laravel UI auth)
- Profil szerkesztése (`ProfileController`)
- Felhasználói adatok kiegészítése (pl. telefonszám)

### 4.2 E-mail folyamat

Az időpont-foglalás után a rendszer visszaigazoló e-mailt próbál küldeni (`AppointmentConfirmationMail`).

Folyamat:

1. Az Appointment rekord létrejön.
2. A `Mail::to(...)->send(...)` meghívódik.
3. Sikertelen küldés esetén naplóbejegyzés készül, a foglalás nem veszik el.

**Mailable osztály** (`app/Mail/AppointmentConfirmationMail.php`):
```php
class AppointmentConfirmationMail extends Mailable
{
    public function __construct(public Appointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Időpont-foglalás megerősítése');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-confirmation',
            with: ['appointment' => $this->appointment],
        );
    }
}
```

**E-mail küldés a kontrollerből**:
```php
try {
    Mail::to(auth()->user()->email)->send(new AppointmentConfirmationMail($appointment));
} catch (\Exception $e) {
    \Log::error('APPOINTMENT_MAIL', ['error' => $e->getMessage()]);
}
```

### 4.3 Hibatűrés

Az e-mail küldés hibatűrő módon működik:

- Ha az e-mail küldés hibázik, a foglalási tranzakció már megtörtént.
- A rendszer a naplóba rögzíti a hibát (`APPOINTMENT_MAIL` címkével).

### 4.4 Hatás a felhasználói élményre

A felhasználó nem veszíti el a foglalást akkor sem, ha az e-mail-szolgáltató oldalán hiba lép fel.

---

## 5. Backend adatmodellek

### 5.1 User

Kulcsmezők:

- `name`, `email`, `phone`, `password`, `role`, `welcome_email_sent_at`

Relációk:

- `hasMany` – cars, appointments, sales (eladó), sentMessages, receivedMessages

### 5.2 Car

Kulcsmezők:

- `user_id`, `make_model`, `vin`, `license_plate`, `year`

Relációk:

- `belongsTo` – user
- `hasMany` – appointments, issues, sales, messages

### 5.3 Appointment

Kulcsmezők:

- `user_id`, `car_id`, `date`, `time`
- `status` (`pending`, `confirmed`, `in_progress`, `completed`, `cancelled`)
- `service`, `service_stage`, `mechanic_name`, `total_cost`
- `service_report`, `issues_found`, `critical_warning`, `work_number`
- Admin-adatok: `customer_*`, `car_*` mezők

Relációk:

- `belongsTo` – user, car
- `hasMany` – servicePhotos

> **Megjegyzés:** A `booted()` creating hook automatikusan generál `work_number` értéket.

**Appointment model** (`app/Models/Appointment.php`):
```php
protected static function booted(): void
{
    static::creating(function (Appointment $appointment) {
        if (!$appointment->work_number) {
            $appointment->work_number = 'MNK-' . strtoupper(substr(uniqid(), -6));
        }
    });
}

protected $casts = [
    'date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
```

### 5.4 Issue

A hibajegy-modul a járműhöz kötött hibaleírásokat kezeli (CRUD), tulajdonosi/policy védelmi logikával.

### 5.5 Sale

Kulcsmezők:

- `car_id` (nullable-ra migrált), `seller_id`, `buyer_id`
- `vehicle_type`, `brand`, `model`, `body_type`
- `engine_cc`, `fuel_type`, `documents_available`, `document_type`
- `technical_inspection`, `price`, `description`, `car_condition`, `mileage`, `is_active`

Relációk:

- `belongsTo` – car, seller, buyer
- `hasMany` – images, messages

### 5.6 SaleImage

A hirdetési kép metaadatai külön táblában tárolva:

- `sale_id`, `path`, `sort_order`

### 5.7 Message

Kulcsmezők:

- `car_id` (opcionális kontextus), `sale_id` (opcionális kontextus)
- `sender_id`, `receiver_id`, `message`, `is_read`

Relációk:

- `belongsTo` – sender, receiver, car, sale

### 5.8 AdminNotification

Felhasználóhoz kötött vagy globális értesítés:

- `user_id` (nullable → globális), `title`, `message`, `is_read`

### 5.9 ServicePhoto

Szervizfolyamat dokumentálása képes bizonyítékokkal:

- `appointment_id`, `title`, `path`

### 5.10 Soft delete stratégia

A soft delete lehetővé teszi:

- audit jellegű visszakövethetőséget,
- véletlen törlések utólagos kezelhetőségét,
- kapcsolt entitások konszolidált megőrzését.

---

## 6. Backend web és API referencia

### 6.1 Web route-struktúra

A rendszer gerincét a `web.php` adja. A route-ok három nagy blokkban rendezettek:

1. Nyilvános/legacy route-ok
2. Admin-only route csoport
3. Bejelentkezett felhasználói route csoport

### 6.2 Főbb web endpoint csoportok

#### Nyilvános

- `GET /` – nyitóoldal
- Laravel auth oldalak (`/login`, `/register` stb.)
- `GET /home` – legacy home

#### Bejelentkezett felhasználó

- `GET /dashboard` – felhasználói irányítópult
- Profil szerkesztése és frissítése
- Cars resource CRUD
- Appointments (index / create / store / show + cancel / reschedule)
- Sales (index / show)
- Issues resource CRUD
- Üzenetküldő endpontok autóhoz és hirdetéshez
- Értesítés olvasása / összes olvasása endpontok

#### Admin

- `GET /admin-dashboard`
- Sales create / store / edit / update / destroy
- Hirdetési kép törlése
- Admin appointments – teljes kezelés
- Admin notifications – teljes kezelés
- Admin üzenetközpont

### 6.3 API endpoint csoport

`/api/vehicles/*`:

- `types` – jármű típusok
- `brands` – márkák
- `models` – modellek
- `body-types` – karosszériatípusok

Ez az API csoport a frontend űrlapok dinamikus típusválasztóit támogatja.

### 6.4 Resource + egyedi endpoint minta

A projekt egyensúlyoz a következők között:

- resource route (konvencionális CRUD),
- egyedi route (üzleti folyamat endpontok: cancel, reschedule, update-status).

### 6.5 API szerződési forma

A web route-ok vegyesen adnak vissza:

- Blade view-t,
- JSON payload-ot (főként üzenetküldő / értesítési aszinkron endpontoknál).

### 6.6 Konszolidációs javaslat

Hosszú távon érdemes lehet az endpoint szerződéseket explicit rétegbe szervezni:

- tiszta web response profil,
- tiszta JSON API profil,
- egységes hibaburkoló.

---

## 7. Backend hibakezelés

### 7.1 Validációs hibakezelés

A rendszer több helyen request validációval dolgozik:

- FormRequest (`StoreSaleRequest`, `UpdateSaleRequest` stb.)
- Inline kontroller validáció (`$request->validate(...)`)

**FormRequest validáció** (`app/Http/Requests/StoreSaleRequest.php`):
```php
public function rules(): array
{
    return [
        'brand'    => 'required|string|max:255',
        'model'    => 'required|string|max:255',
        'price'    => 'required|numeric|min:0',
        'images'   => 'array|max:10',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
    ];
}

public function authorize(): bool
{
    return auth()->user()->isAdmin();
}
```

### 7.2 Üzleti ütközéskezelés

Időpontnál külön konfliktusvizsgálat fut: ugyanarra a dátum + idő slotra ne lehessen két `confirmed` foglalás.

### 7.3 Jogosultsági hibák

Tipikus hibaforrásoк:

- idegen autóhoz való tartozás,
- inaktív hirdetéshez való üzenetküldés,
- nem címzett értesítés olvasása.

### 7.4 Üzenetküldési védelmi esetek

Az üzenet endpontok explicit ellenőrzik:

- hogy a felhasználó tulajdonos-e,
- hogy admin-e,
- hogy a hirdetés aktív-e,
- hogy jogosult-e a beszélgetésre.

### 7.5 Fájlkezelési hibák

Hirdetési/szervizképek törlésekor a rendszer a storage oldali törlést is elvégzi. Üzemeltetési oldalon ajánlott rendszeres árva fájl ellenőrzés.

### 7.6 Megfigyelhetőség

E-mail küldésnél naplózott kivétel-metaadatok segítik a hibafeltárást.

---

## 8. Backend piactéri állapotgép

### 8.1 Jelenlegi állapot

Az Autonex piactéri modul jelenleg nem Stripe-jellegű fizetési folyamatra épül, hanem hirdetési állapot- és tulajdonosi munkafolyamatra.

### 8.2 Sale állapotlogika

Kulcs állapotjelző: `is_active`.

- `true` – a hirdetés aktív, nyilvános kommunikáció engedett.
- `false` – a hirdetés zárva/inaktív, külső felhasználó számára a kommunikáció korlátozott.

### 8.3 Tranzakció helyett folyamatkontroll

A hangsúly a következőkön van:

- Ki hozhat létre hirdetést?
- Ki módosíthat?
- Ki törölhet?
- Ki üzenhet adott hirdetésről?

### 8.4 Vevő/eladó modellezése

A modell támogatja a `buyer_id` mezőt is, ami egy jövőbeli teljes adásvételi folyamat alapja lehet.

### 8.5 Kiterjesztési pontok

Következő iterációban integrálható:

- foglalási állapot,
- fizetési állapot,
- tranzakciós napló,
- birtokbaadási munkafolyamat.

---

## 9. Backend keresés, szűrés és tömeges műveletek

### 9.1 Hirdetéslista

A piactéri listázás lapozott (`paginate(10)`), és betölti a kapcsolt entitásokat (`car`, `buyer`, `seller`, `images`).

### 9.2 Admin időpontszűrők

Az admin időpontlistán több mezőre lehet szűrni:

- ügyfél neve,
- autó márka/modell,
- rendszám,
- dátum.

### 9.3 Járműkonfiguráció mint domain-szótár

A `config/vehicles.php` több száz márka/modell elemet tartalmaz. Gyakorlatilag egy domain-szótár:

- vehicle_type → brand → model
- body_type listák vehicle_type szerint.

### 9.4 Tömeges jellegű műveletek

A kódban explicit bulk endpoint nincs minden modulban, de a seederek szintjén jelentkezik tömeges adatbetöltés:

- autó + hirdetés + kép csomagolt előállítása.

### 9.5 Keresési fejlesztési lehetőségek

- Teljes szöveges index a hirdetés-leírásokra.
- Facet-szerű szűrés (ár, márka, üzemanyag, állapot).
- Előre indexelt irányítópult-mutatók.

---

## 10. Backend munkafolyamatok

### 10.1 Felhasználói autófelvételi munkafolyamat

1. A felhasználó bejelentkezik.
2. A `cars.create` oldalon rögzíti az adatokat.
3. Validáció lefut.
4. `cars.store` végrehajtódik.
5. Az autó megjelenik a saját listában.

### 10.2 Felhasználói időpont-foglalási munkafolyamat

1. A felhasználó kiválaszt egy saját autót.
2. Megadja a dátumot és az időpontot.
3. Konfliktusellenőrzés fut.
4. Az appointment `pending` státusszal létrejön.
5. Visszaigazoló e-mail küldési kísérlet.

**Konfliktusellenőrzés** (`app/Http/Controllers/AppointmentController.php`):
```php
public function store(StoreAppointmentRequest $request)
{
    $conflict = Appointment::where('car_id', $request->car_id)
        ->where('date', $request->date)
        ->where('time', $request->time)
        ->where('status', 'confirmed')
        ->exists();

    if ($conflict) {
        return back()->withErrors(['time' => 'Ez az idő már foglalt.']);
    }

    $appointment = Appointment::create([
        'user_id' => auth()->id(),
        'car_id'  => $request->car_id,
        'date'    => $request->date,
        'time'    => $request->time,
        'status'  => 'pending',
    ]);

    Mail::to(auth()->user()->email)->send(new AppointmentConfirmationMail($appointment));
    return back()->with('success', 'Időpont foglalva.');
}
```

### 10.3 Felhasználói időpont-átütemezés és lemondás

- Csak `pending` / `confirmed` állapotban engedett.
- Státusz és dátum/idő frissítése.
- Admin értesítés létrehozása.

### 10.4 Admin időpontkezelési munkafolyamat

1. Az admin szűrőkkel listázza az időpontokat.
2. Megtekinti a részleteket és szerkeszti.
3. Státuszfrissítés (`confirmed` / `cancelled` / `completed`).
4. Szervizszakasz kezelése.
5. Szervizfotók feltöltése / törlése.
6. `completed + ready` esetén felhasználói értesítés küldése.

### 10.5 Hirdetés CRUD munkafolyamata

- Az admin létrehozza a hirdetést.
- Több képet tölthet fel.
- Utólag képenként törölhet.
- Policy alapú frissítés / törlés.

### 10.6 Üzenet munkafolyamat autókontextusban

- A felhasználó vagy az admin üzenetet küld.
- A fogadó feloldása automatikusan megtörténik.
- Az olvasott/olvasatlan állapot endpontokon frissül.
- Admin oldalon összegzett olvasatlan badge jelenik meg.

**Üzenet küldése** (`app/Http/Controllers/MessageController.php`):
```php
public function store(Car $car, StoreMessageRequest $request)
{
    $this->authorize('create', [Message::class, $car]);

    Message::create([
        'car_id'      => $car->id,
        'sender_id'   => auth()->id(),
        'receiver_id' => $this->resolveReceiver($car),
        'message'     => $request->message,
        'is_read'     => false,
    ]);

    return back()->with('success', 'Üzenet elküldve.');
}

private function resolveReceiver(Car $car)
{
    // Ha admin küldi, a tulajdonosnak; ha felhasználó, akkor az adminnak
    return auth()->user()->isAdmin() ? $car->user_id : 1;
}
```

### 10.7 Üzenet munkafolyamat hirdetési kontextusban

- Az admin, az eladó és a vevő szerepköre külön ágon fut.
- Inaktív hirdetésnél korlátozott az üzenetküldés.
- Értesítés automatikusan generálódik.

---

## 11. Backend fájltárolás

### 11.1 Tárolási modell

A rendszer a Laravel `public` lemezét használja:

- hirdetési képek: `sales/...`
- szervizfotók: `service-photos/...`

### 11.2 Szimbolikus link

A nyilvános kiszolgáláshoz kötelező:

```bash
php artisan storage:link
```

### 11.3 Képtárolás és metaadatok

Maga a bináris tartalom a storage-ban van, az adatbázisban csak metaadat tárolódik:

- relatív elérési út
- rendezési sorrend (`sort_order`)
- tulajdonosi kötés (`sale_id`, `appointment_id`)

**Képfeltöltés** (`app/Http/Controllers/SaleController.php`):
```php
public function store(StoreSaleRequest $request)
{
    $sale = Sale::create($request->validated());

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('sales', 'public');
            SaleImage::create([
                'sale_id'    => $sale->id,
                'path'       => $path,
                'sort_order' => $index + 1,
            ]);
        }
    }

    return back()->with('success', 'Hirdetés létrehozva.');
}
```

### 11.4 Seeder képforrás

A hirdetési seeder a `képek` mappából olvas prefix alapú egyeztetéssel, majd a `public` lemezre másol.

### 11.5 Fájltípusok

Az aktív seeder logika AVIF képekre optimalizált, de a request validáció oldalán jpeg / png / jpg / webp elfogadott.

### 11.6 Fájlrendszer kockázatok

- Törölt DB rekord után ottmaradt fájlok.
- Manuális storage törlés után DB-ben maradt elérési utak.
- Nagy mennyiségű kép esetén tárhelymenedzselési igény.

---

## 12. Backend üzenetsorok és ütemezés

### 12.1 Üzenetsor

A composer dev script futtatja a `queue:listen` folyamatot:

- `--tries=1`
- `--timeout=0`

Ez egyszerű helyi hibatűrő módot ad, de éles környezetben worker manager (Supervisor / systemd) javasolt.

### 12.2 Ütemező

Külön egyedi ütemező parancs jelenleg nincs a kódban (pl. lejárt foglalások kezelése), de a Laravel ütemező infrastruktúra rendelkezésre áll.

### 12.3 Naplóstream

A `php artisan pail` benne van a fejlesztői orchestration scriptben – ez gyors hibakeresést ad helyi fejlesztéskor.

### 12.4 Üzemeltetési javaslatok

- Queue worker folyamat monitorozása.
- Sikertelen feladatok (`failed_jobs`) rendszeres kezelése.
- Naplórotációs policy bevezetése.

---

## 13. Jogosultságok és hozzáférési mátrix

### 13.1 Rétegek

1. Route middleware (`auth`, `admin`)
2. Policy osztályok (`CarPolicy`, `SalePolicy`, `AppointmentPolicy`, `MessagePolicy`, `IssuePolicy`)
3. Kontroller szintű egyedi ellenőrzők

### 13.2 Szerepkör-mátrix összefoglalása

**Admin:**
- Teljes admin irányítópult
- Időpontkezelés
- Hirdetéskezelés
- Globális üzenetközpont
- Értesítéskezelés

**Bejelentkezett felhasználó:**
- Saját profil
- Saját autó CRUD
- Saját időpontok
- Hibajegyek
- Hirdetéslista megtekintése
- Üzenetküldés a feltételek szerint

**Vendég:**
- Nyitóoldal
- Auth oldalak

### 13.3 Policy minták

- `SalePolicy::create` → csak admin.
- `SalePolicy::update/delete` → admin vagy eladó.
- `CarPolicy::update/delete` → admin vagy tulajdonos.
- `MessagePolicy::view` → feladó / fogadó / admin.

**CarPolicy** (`app/Policies/CarPolicy.php`):
```php
public function update(User $user, Car $car): bool
{
    return $user->isAdmin() || $user->id === $car->user_id;
}

public function delete(User $user, Car $car): bool
{
    return $user->isAdmin() || $user->id === $car->user_id;
}
```

**SalePolicy** (`app/Policies/SalePolicy.php`):
```php
public function create(User $user): bool
{
    return $user->isAdmin();
}

public function update(User $user, Sale $sale): bool
{
    return $user->isAdmin() || $user->id === $sale->seller_id;
}
```

**Kontroller alkalmazása** (`app/Http/Controllers/CarController.php`):
```php
public function update(Request $request, Car $car)
{
    $this->authorize('update', $car);
    $car->update($request->validated());
    return back()->with('success', 'Autó frissítve.');
}
```

### 13.4 Mélységi védelem (defense in depth)

A kódban több ponton redundáns védelem van:

- policy,
- kontroller feltételek,
- route middleware.

Ez csökkenti a jogosulatlan hozzáférés kockázatát route-szintű hibakonfiguráció esetén is.

**Többszintű védelem** (`app/Http/Controllers/SaleController.php`):
```php
public function destroy(Sale $sale)
{
    // 1. Route middleware: auth
    // 2. Policy: csak admin vagy eladó törölhet
    $this->authorize('delete', $sale);

    // 3. Extra tulajdonos-ellenőrzés
    if (!auth()->user()->isAdmin() && auth()->user()->id !== $sale->seller_id) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    // 4. Soft delete
    $sale->delete();
    return back()->with('success', 'Hirdetés törölve.');
}
```

---

## 14. Frontend architektúra

### 14.1 Általános kép

A frontend szerver oldali renderelésű Blade rendszer, amelyet kis mértékű JavaScript egészít ki aszinkron üzenetfrissítéshez és dinamikus interakciókhoz.

### 14.2 Technológiák

- Blade templating
- Bootstrap 5
- Sass
- Vite
- Minimális `resources/js/app.js`

### 14.3 Oldalszerkezet

Főbb nézetmappák:

- `layouts/`
- `dashboard/`
- `cars/`
- `appointments/`
- `issues/`
- `sales/`
- `messages/`
- `admin/`

### 14.4 Layout komponensek

A közös oldalfej/menüszerkezet centralizált layoutban van, ahol az AppServiceProvider view composer injektálja a szükséges értesítési adatokat.

### 14.5 Frontend rétegzés

- Teljes oldalas navigáció (klasszikus webalkalmazás)
- Endpoint szintű AJAX a chat/értesítés helyzetekben
- Progresszív fejlesztési (progressive enhancement) megközelítés

---

## 15. Frontend autentikáció és munkamenet

### 15.1 Session modell

A frontend auth állapota klasszikus Laravel session cookie alapú.

### 15.2 Auth UX folyamat

1. A felhasználó a bejelentkezési oldalon hitelesít.
2. Session létrejön.
3. Az auth middleware védi a route-okat.
4. Kijelentkezéskor a session érvénytelenedik.

### 15.3 Navigációs elkülönítés

- Admin irányítópult – külön endpoint.
- Felhasználói irányítópult – külön endpoint.
- Route-szintű auth guard.

### 15.4 Jogosultsági visszajelzés

Nem admin felhasználó admin route-ra lépve átirányítást kap a gyökérre.

---

## 16. Frontend komponens- és nézettár

### 16.1 Nézettípusok

- Listázó oldalak (`index`)
- Létrehozó oldalak (`create`)
- Szerkesztő oldalak (`edit`)
- Részletoldalak (`show`)

### 16.2 Modulonkénti sablonok

- Sales: `index / create / edit / show`
- Appointments: felhasználói oldali folyamatoldalak
- Admin appointments: külön mappa, külön UX
- Messages: admin index + conversation

### 16.3 Újrafelhasználhatóság

A Blade résznézetekkel és a közös layouttal a tipikus navigációs és visszajelzési minták újrafelhasználhatók.

---

## 17. Frontend útvonalkezelés és navigáció

### 17.1 Navigációs topológia

- Nyitóoldal → bejelentkezés / regisztráció
- Bejelentkezés után szerepkör-függő irányítópult
- Irányítópultról tematikus modulok

### 17.2 Legacy route-támogatás

A `/home` route fenntartott kompatibilitási célból az auth scaffolding alapértelmezett átirányítása miatt.

### 17.3 Kontextuális navigáció

A felhasználó a jármű, időpont, hibajegy és hirdetés modulok között válthat; admin oldalon külön menüpontok adnak munkafolyamat-központú navigációt.

---

## 18. Frontend állapotkezelés és integráció

### 18.1 SSR állapotforrás

Az állapot többnyire szerver oldalon renderelt adat: Blade template változók.

### 18.2 AJAX állapot

Ahol valós idejű érzet kell:

- üzenetek olvasása / küldése,
- olvasatlan darabszám frissítése,
- értesítés olvasott állapota.

### 18.3 API integrációs típusok

- Webes form submit (redirect + flash üzenet)
- JSON endpoint hívás (`expectsJson` esetekben)

### 18.4 Adatbetöltési minta

A kontrollerek jellemzően eager loadingot használnak (`with(...)`) az N+1 probléma csökkentésére.

---

## 19. Frontend betöltőrendszer és UX visszajelzések

### 19.1 Visszajelzési formák

A rendszer fő visszajelzési csatornái:

- Success flash üzenetek
- Validációs hibabiokkok
- Inline állapotjelzések
- Badge / olvasatlan számlálók

### 19.2 Admin valós idejű jelzés

Az admin navbarban az olvasatlan üzenetek száma jelenik meg, amelyet a view composer tölt fel.

### 19.3 Felhasználói értesítési UX

Felhasználói oldalon a navigációs értesítések felhasználóspecifikus és globális (user_id NULL) elemeket egyesítenek.

### 19.4 Konzisztencia

A redirect + flash minta egyszerű, konzisztens és tanulható felhasználói folyamatot ad.

---

## 20. Frontend felhasználói folyamatok

### 20.1 Fő folyamat: jármű rögzítése

1. A felhasználó bejelentkezik.
2. Autót hoz létre.
3. Az autó megjelenik a saját listában.

### 20.2 Fő folyamat: időpont-foglalás

1. A felhasználó kiválaszt egy saját autót.
2. Kitölti a foglalási űrlapot.
3. Konfliktusellenőrzés fut.
4. Pending időpont létrejön.
5. E-mail visszaigazolási kísérlet.

### 20.3 Fő folyamat: admin szervizkezelés

1. Az admin szűri / listázza az időpontokat.
2. Szerkeszti a státuszt és a szervizadatokat.
3. Szervizfotókat csatol.
4. Kész állapotban felhasználói értesítést küld.

### 20.4 Fő folyamat: piactér

1. A felhasználó megtekinti a hirdetéslistát.
2. A hirdetés részletoldalán képgaléria + adatok láthatók.
3. Kapcsolódó kommunikáció a sale message endponton.

### 20.5 Fő folyamat: üzenetküldés

- Autókontextusban vagy hirdetési kontextusban indul.
- A rendszer automatikusan feloldja a fogadót.
- Az olvasatlan számlálók frissülnek.

### 20.6 Fő folyamat: értesítések

- A felhasználó olvassa az egyedi és globális értesítéseket.
- A „Mindet olvasottnak jelöl" endponttal egyszerre zárható az összes.

---

## 21. Frontend stílusrendszer

### 21.1 Alapelvek

- Bootstrap utility-first szerkezetek
- Saját Sass/CSS kiegészítések
- Blade oldal szintű stílusrészek

### 21.2 Konzisztencia

A rendszer legnagyobb erőssége a konvencionális, ismerős admin panel jellegű UI, amely gyors betanulást tesz lehetővé.

### 21.3 Továbbfejlesztési irányok

- Design tokenek formalizálása.
- Komponensszintű stílusstandardok.
- Sötét / világos témaopció.

---

## 22. Frontend űrlapok és validáció

### 22.1 Validációs rétegek

- Szerver oldali validáció (elsődleges)
- Request osztályok egyes moduloknál
- Inline validáció más moduloknál

### 22.2 Hirdetési validáció

A hirdetés store / update validáció kezeli:

- Közös domain mezőket.
- Képfeltöltési korlátot (max. 10).
- Támogatott MIME-típusokat.
- Méretlimitet.

### 22.3 Időpont validáció

Az időpont modulban dátum, idő, autó tulajdonjog, állapotátmenet és konfliktusellenőrzés egyszerre érvényesül.

### 22.4 Admin időpont validáció

Az admin oldal kibővített mezőket kezel (ügyfél + autó technikai adatok), és külön státuszfrissítési endpoint is van.

### 22.5 Hibamegjelenítés

A validációs hibák tipikusan redirect + oldalon megjelenített hibabiokkon keresztül látszanak.

---

## 23. Frontend hibakezelés

### 23.1 Hibaterületek

- URL validációs hiba
- Jogosultsági hiba (403 / átirányítás)
- Üzleti konfliktus (időpont ütközés)
- Fájlkezelési hiba
- E-mail küldési hiba (csökkentett üzemmód)

### 23.2 UX szempont

A rendszer általában nem „hard fail" módon viselkedik: a kritikus üzleti adat mentésre kerül, az opcionális szolgáltatás (e-mail) hibája naplózódik.

### 23.3 Naplózás

E-mail küldésnél explicit naplómetaadat segít a kiváltó ok elemzésében.

### 23.4 Javasolt további erősítések

- Egységes hibakód-konvenció.
- Centrális kivételleképzés.
- Strukturált audit napló.

---

## 24. Környezet és deployment

### 24.1 Kötelező komponensek

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL / MariaDB

### 24.2 Build és futtatás

A backend + frontend assetek együtt futnak a Laravel alkalmazáson belül.

### 24.3 Storage előfeltétel

A képes funkciókhoz kötelező a storage link létrehozása:

```bash
php artisan storage:link
```

### 24.4 Queue / napló folyamatok

A dev script párhuzamosan futtatja:

- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail`
- `npm run dev`

### 24.5 Éles környezeti ellenőrzőlista

- [ ] `APP_KEY` és `APP_ENV` helyes
- [ ] DB-kapcsolat rendben
- [ ] `MAIL_*` konfiguráció rendben
- [ ] Fájlrendszer lemez beállítása
- [ ] Queue worker démonizálva
- [ ] Naplórotáció beállítva
- [ ] Backup policy létrehozva

---

## 25. Helyi fejlesztési környezet

### 25.1 Gyors beállítás

1. `composer install`
2. `npm install`
3. `.env` létrehozása `.env.example` alapján
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan storage:link`

### 25.2 Napi futtatás

Két külön terminálban:

```bash
php artisan serve
npm run dev
```

Vagy összevontan:

```bash
composer run dev
```

### 25.3 Seeder adatok

A seederek demótartalommal töltik fel az adatbázist:

- felhasználók,
- autók,
- hirdetések (helyi képforrásból),
- hibajegyek,
- időpontok,
- üzenetek.

### 25.4 Tesztek

A standard tesztfuttatás a Laravel / PHPUnit pipeline-on keresztül történik:

```bash
php artisan test
```

---

## 26. Hibaelhárítás

### 26.1 A storage képek nem látszanak

Tünetek: 404-es képURL-ek, üres hirdetésgaléria.

Lépések:
1. Futtasd: `php artisan storage:link`
2. Ellenőrizd, hogy a `storage/app/public/sales` tartalmaz-e fájlokat.
3. Ellenőrizd a fájlrendszer jogosultságokat.

### 26.2 Időpont-foglalás sikertelen

Tipikus okok:

- Dátum/idő ütközés egy megerősített foglalással.
- Idegen autóhoz való foglalási kísérlet.

### 26.3 Üzenetküldés tiltva

Tipikus okok:

- A felhasználó nem tulajdonos.
- A hirdetés inaktív.
- Nincs jogosultság a kontextushoz.

### 26.4 E-mail nem megy ki

1. Ellenőrizd a `MAIL_*` környezeti változókat.
2. Futtasd: `php artisan config:clear`
3. Nézd meg a naplót az `APPOINTMENT_MAIL` címkére szűrve.

### 26.5 Admin irányítópult nem érhető el

- Ellenőrizd a felhasználó `role` mezőjét (`admin`).
- Ellenőrizd, hogy az auth session aktív-e.

### 26.6 Migráció / seeder hibák

- FK-konfliktus → `php artisan migrate:fresh --seed`
- Képek hiánya → ellenőrizd a `képek` mappát.

### 26.7 Változó route-viselkedés

Ha egy endpoint másként viselkedik, ellenőrizd:

- a middleware csoportot,
- a policy-t,
- a kontroller egyedi ellenőrzőjét.

---

## 27. Összefoglalás

### 27.1 Fő eredmény

Az Autonex egy jól strukturált, üzletileg használható Laravel monolit, amely:

- lefedi a jármű + szerviz + piactér főbb use case-eit,
- szerepköralapú jogosultságokkal dolgozik,
- különálló admin operációs munkafolyamatot ad,
- képeket és kommunikációt valós rendszerfunkciókkal kezel.

### 27.2 Legfontosabb erősségek

- Tiszta MVC modulrendszer.
- Életszerű domain modellek.
- Valós admin munkafolyamatok.
- Hibatűrő e-mail kezelés.
- Egyszerű, gyorsan telepíthető fejlesztői pipeline.

### 27.3 Legfontosabb kockázatok

- Vegyes validációs megközelítés (FormRequest + inline).
- Web / API response forma nem teljesen homogén.
- Fizetési munkafolyamat nincs teljesen külön modulba kiemelve.
- Az üzenetfolyamatok komplexitása növekedéssel nehezebben karbantarthatóvá válhat.

### 27.4 Prioritáslista a továbbfejlesztéshez

1. Egységes API / hiba envelope policy.
2. Különálló service réteg a komplex kontroller munkafolyamatokhoz.
3. Tesztlefedettség növelése (feature + policy + integráció).
4. Üzenetmodul audit / monitoring bővítése.
5. Opcionális valós idejű csatorna (WebSocket) a polling jellegű megközelítés helyett.

---

## 28. A melléklet – Végpontmátrix

### A.1 Nyilvános + auth alap

- `GET /`
- `Auth::routes()` által adott auth endpontok
- `GET /home`

### A.2 Felhasználói irányítópult + profil

- `GET /dashboard`
- `GET /profile`
- `PUT /profile`

### A.3 Értesítések

- `PATCH /notifications/{notification}/read`
- `PATCH /notifications/read-all`

### A.4 Cars (autók)

- Resource: `cars` (index / create / store / show / edit / update / destroy)

### A.5 Appointments (időpontok – felhasználói)

- `GET /appointments`
- `GET /appointments/create`
- `POST /appointments`
- `GET /appointments/{appointment}`
- `PATCH /appointments/{appointment}/cancel`
- `PATCH /appointments/{appointment}/reschedule`
- `GET /appointments/{appointment}/work-order-pdf`

### A.6 Sales (hirdetések)

- Felhasználói: `GET /sales`, `GET /sales/{sale}`
- Admin: create / store / edit / update / destroy
- `DELETE /sales/{sale}/images/{image}`

### A.7 Issues (hibajegyek)

- Resource: `issues`

### A.8 Messages (üzenetek)

Autókontextus:
- `POST /cars/{car}/messages`
- `GET /cars/{car}/messages`

Hirdetési kontextus:
- `POST /sales/{sale}/messages`
- `GET /sales/{sale}/messages`

Badge:
- `GET /messages/unread-count`

Admin üzenetközpont:
- `GET /admin/messages`
- `GET /admin/messages/car/{car}`

### A.9 Admin appointments

- `GET /admin/appointments`
- `GET /admin/appointments/create`
- `POST /admin/appointments`
- `GET /admin/appointments/{appointment}`
- `GET /admin/appointments/{appointment}/edit`
- `PUT /admin/appointments/{appointment}`
- `PATCH /admin/appointments/{appointment}/update-status`
- `DELETE /admin/service-photos/{photo}`
- `DELETE /admin/appointments/{appointment}`

### A.10 Admin notifications (értesítések)

- `GET /admin/notifications`
- `GET /admin/notifications/create`
- `POST /admin/notifications`
- `DELETE /admin/notifications/{notification}`

### A.11 API vehicles (jármű adatok)

- `GET /api/vehicles/types`
- `GET /api/vehicles/brands`
- `GET /api/vehicles/models`
- `GET /api/vehicles/body-types`

---

## 29. B melléklet – Adatmodell-szótár

### B.1 users
**Felelős:** autentikáció, szerepkör, alap profil.

Főbb mezők: `id`, `name`, `email`, `phone`, `password`, `role`, `email_verified_at`, `welcome_email_sent_at`, `created_at`, `updated_at`

### B.2 cars
**Felelős:** felhasználói jármű törzs.

Főbb mezők: `id`, `user_id`, `make_model`, `vin`, `license_plate`, `year`, `deleted_at`

### B.3 appointments
**Felelős:** szervizfoglalás + üzemeltetési állapotok.

Főbb mezők: `id`, `user_id`, `car_id`, `date`, `time`, `status`, `service`, `description`, `service_stage`, `mechanic_name`, `total_cost`, `service_report`, `issues_found`, `critical_warning`, `work_number`, `customer_*` / `car_*` admin adatok, `deleted_at`

### B.4 issues
**Felelős:** hibajegy-nyilvántartás.

Főbb mezők: `id`, `user_id`, `car_id`, `title`, leírás, státusz jellegű mezők, `deleted_at`

### B.5 sales
**Felelős:** piactéri hirdetés.

Főbb mezők: `id`, `car_id`, `seller_id`, `buyer_id`, `vehicle_type`, `brand`, `model`, `body_type`, `engine_cc`, `fuel_type`, `documents_available`, `document_type`, `technical_inspection`, `price`, `description`, `car_condition`, `mileage`, `is_active`, `deleted_at`

### B.6 sale_images
**Felelős:** hirdetéshez tartozó több kép metaadata.

Főbb mezők: `id`, `sale_id`, `path`, `sort_order`, `created_at`, `updated_at`

### B.7 messages
**Felelős:** autóhoz / hirdetéshez kötött üzenetek.

Főbb mezők: `id`, `car_id`, `sale_id`, `sender_id`, `receiver_id`, `message`, `is_read`, `deleted_at`

### B.8 admin_notifications
**Felelős:** rendszerüzenetek, olvasottság.

Főbb mezők: `id`, `user_id` (nullable), `title`, `message`, `is_read`, `created_at`, `updated_at`

### B.9 service_photos
**Felelős:** szervizfolyamat dokumentálása.

Főbb mezők: `id`, `appointment_id`, `title`, `path`, `created_at`, `updated_at`

---

## 30. C melléklet – Üzemeltetési ellenőrzőlisták

### C.1 Kiadás előtti ellenőrzés

- [ ] Környezeti változók megfelelőek
- [ ] Migráció lefutott
- [ ] Seed lefutott (demo környezetben)
- [ ] Storage link megvan
- [ ] Queue worker fut
- [ ] A naplók tiszták, kritikus kivétel nélkül
- [ ] Admin és felhasználói bejelentkezés tesztelve
- [ ] Képfeltöltés + törlés tesztelve
- [ ] Időpont munkafolyamat tesztelve
- [ ] Üzenetküldési munkafolyamat tesztelve

### C.2 Füstteszt forgatókönyv

1. Bejelentkezés admin és felhasználó szerepkörrel.
2. Felhasználó létrehoz egy autót.
3. Felhasználó időpontot foglal.
4. Admin megerősíti, majd `completed + ready` állapotba teszi.
5. Felhasználói oldalon értesítés ellenőrzése.
6. Admin létrehoz / módosít hirdetést több képpel.
7. Felhasználó megtekinti a hirdetést és üzenetet küld.
8. Admin válaszol, olvasatlan badge ellenőrzése.

### C.3 Incidens-kezelési mini runbook

1. Reprodukálhatóság ellenőrzése.
2. Route + middleware + policy hármas ellenőrzése.
3. DB állapot ellenőrzése (`appointments`, `sales`, `messages`).
4. Storage állapot ellenőrzése (`sales`, `service-photos`).
5. Naplóelemzés (`laravel.log`, pail stream).
6. Gyorsjavítás vagy visszaállítás döntése.

### C.4 Fejlesztési minőségi kapuk

- [ ] Kódstílus rendben
- [ ] Validáció nem lazult
- [ ] Jogosultság nem gyengült
- [ ] N+1 regresszió nincs
- [ ] Route névadás konzisztens
- [ ] UI visszajelzés egyértelmű

---

## Záradék

Ez a dokumentáció az Autonex aktuális kódállapotához igazodva készült, a valós route-ok, kontrollerek, modellek, policy-k, seederek és migrációs állapot figyelembevételével. A dokumentum célja, hogy fejlesztői, üzemeltetői és átadási oldalról egyaránt használható referencia legyen.
