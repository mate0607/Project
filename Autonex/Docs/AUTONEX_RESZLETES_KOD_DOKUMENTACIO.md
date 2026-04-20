# AutoNex – Részletes Kód- és Funkciódokumentáció

---

## Tartalomjegyzék

1. [Projekt áttekintés](#1-projekt-áttekintés)
2. [Technológiai stack](#2-technológiai-stack)
3. [Mappastruktúra](#3-mappastruktúra)
4. [Adatbázis – Migrációk és táblák](#4-adatbázis--migrációk-és-táblák)
5. [Modellek (Eloquent)](#5-modellek-eloquent)
6. [Útvonalak (Routes)](#6-útvonalak-routes)
7. [Middleware](#7-middleware)
8. [Kontrollerek részletesen](#8-kontrollerek-részletesen)
9. [Form Request validációk](#9-form-request-validációk)
10. [Policy-k (jogosultságkezelés)](#10-policy-k-jogosultságkezelés)
11. [E-mail küldés (Mailable)](#11-e-mail-küldés-mailable)
12. [Értesítési rendszer](#12-értesítési-rendszer)
13. [Service Provider](#13-service-provider)
14. [Seederek](#14-seederek)
15. [API végpontok](#15-api-végpontok)
16. [Konfiguráció – Járműadatok](#16-konfiguráció--járműadatok)
17. [Autentikáció (Auth)](#17-autentikáció-auth)
18. [Összegzés – Funkciótérkép](#18-összegzés--funkciótérkép)

---

## 1. Projekt áttekintés

Az **AutoNex** egy Laravel alapú autószerviz-menedzsment és piactér alkalmazás. A rendszer két fő felhasználói szerepet különböztet meg:

- **Felhasználó (user):** Saját autóit kezeli, időpontot foglal szervizre, hibajegyeket ír, üzenetet küld az adminnak, és böngészi a piacteret.
- **Admin:** Teljes hozzáféréssel rendelkezik: kezel minden időpontot, szervizfolyamatot, értesítést küld, hirdetéseket kezel, és üzeneteket vált az ügyfelekkel.

### Fő funkciók:
- Autónyilvántartás (CRUD)
- Időpont foglalás és szervizkövetés
- Hibajegy-kezelés
- Piactér (eladás/hirdetés)
- Üzenetküldés (autó-alapú chat)
- Admin értesítési rendszer
- E-mail küldés (üdvözlő + időpont-visszaigazolás)
- Járműadat API (típus → márka → modell kaszkád)

---

## 2. Technológiai stack

| Technológia | Verzió/Típus |
|---|---|
| **Backend** | Laravel (PHP) |
| **Frontend** | Blade template engine + Vite |
| **Adatbázis** | MySQL/MariaDB (migrációkkal) |
| **Autentikáció** | Laravel Auth scaffolding (Sanctum támogatás) |
| **E-mail** | Laravel Mailable |
| **Fájlkezelés** | Laravel Storage (`public` disk) |
| **CSS** | SASS |

---

## 3. Mappastruktúra

```
app/
├── Http/
│   ├── Controllers/          # Összes kontroller
│   │   ├── Admin/            # Admin-specifikus kontrollerek
│   │   ├── Auth/             # Bejelentkezés, regisztráció
│   │   └── Traits/           # Újrafelhasználható trait-ek
│   ├── Middleware/            # Admin middleware
│   └── Requests/             # Form Request validációs osztályok
├── Mail/                     # Mailable osztályok (email sablonok)
├── Models/                   # Eloquent modellek
├── Policies/                 # Jogosultságkezelés (Policy-k)
└── Providers/                # Service Provider-ek

config/
└── vehicles.php              # Járműtípus/márka/modell kaszkád adat

database/
├── factories/                # Model factory-k (tesztadatok)
├── migrations/               # Adatbázis migrációk
└── seeders/                  # Seeder-ek (kezdeti adatok)

routes/
├── web.php                   # Webes route-ok
├── api.php                   # API route-ok
└── console.php               # Konzol parancsok

resources/views/              # Blade nézetek
```

---

## 4. Adatbázis – Migrációk és táblák

### 4.1 `users` tábla
```
id, name, email, phone, role, password, welcome_email_sent_at, remember_token, timestamps
```
- `role`: A felhasználó szerepe (`user` vagy `admin`)
- `welcome_email_sent_at`: Mikor kapott üdvözlő e-mailt (megakadályozza az ismételt küldést)
- `phone`: Telefonszám (későbbi migráció: `add_phone_to_users_table`)

### 4.2 `cars` tábla
```
id, user_id (FK→users), make_model, vin, license_plate, year, timestamps, soft_deletes
```
- Egy felhasználónak több autója lehet
- `vin`: Alvázszám (opcionális)
- `license_plate`: Rendszám (későbbi migráció)
- **SoftDeletes**: A törlés logikai, nem fizikai

### 4.3 `appointments` tábla
```
id, user_id (FK→users), car_id (FK→cars), work_number, date, time, description, status, 
service, service_stage, mechanic_name, total_cost, service_report, issues_found, 
critical_warning, customer_name, customer_phone, car_brand, car_model, car_year, 
car_engine, car_fuel_type, timestamps, soft_deletes
```
- `status`: `pending`, `confirmed`, `in_progress`, `completed`, `cancelled`
- `service_stage`: `received`, `inspected`, `in_progress`, `ready` (szervizfolyamat fázis)
- `work_number`: Automatikusan generált munkaszám (`MNK-XXXXXX`)
- Admin-specifikus mezők (`customer_name`, `car_brand`, stb.): Lehetővé teszik, hogy az admin rendszer nélküli ügyfeleknek is rögzítsen időpontot

### 4.4 `issues` tábla
```
id, car_id (FK→cars), category, description, urgency, timestamps, soft_deletes
```
- `urgency`: `low`, `medium`, `high`
- Egy autóhoz több hibajegy tartozhat

### 4.5 `sales` tábla
```
id, car_id (FK→cars, nullable), vehicle_type, brand, model, body_type, engine_cc, 
fuel_type, documents_available, document_type, technical_inspection, buyer_id (FK→users, nullable), 
seller_id (FK→users), price, description, car_condition, mileage, is_active, timestamps, soft_deletes
```
- A piactér hirdetések táblája
- `is_active`: Aktív-e a hirdetés
- `documents_available`: Van-e iratcsomag
- `technical_inspection`: Érvényes műszaki vizsga

### 4.6 `sale_images` tábla
```
id, sale_id (FK→sales), path, sort_order, timestamps
```
- Egy hirdetéshez több kép tartozhat, rendezési sorrenddel

### 4.7 `messages` tábla
```
id, car_id (FK→cars), sale_id (FK→sales), sender_id (FK→users), receiver_id (FK→users), 
message, is_read, timestamps, soft_deletes
```
- Autó-alapú üzenetváltás felhasználó és admin között
- `is_read`: Olvasottság jelzése

### 4.8 `admin_notifications` tábla
```
id, user_id (FK→users, nullable), title, message, is_read, timestamps
```
- Ha `user_id` NULL → globális értesítés minden felhasználónak
- Ha `user_id` kitöltve → adott felhasználónak szóló értesítés

### 4.9 `service_photos` tábla
```
id, appointment_id (FK→appointments), title, path, timestamps
```
- Szerviz közben készített fotók az időponthoz csatolva

---

## 5. Modellek (Eloquent)

### 5.1 `User` modell
**Fájl:** `app/Models/User.php`

```php
class User extends Authenticatable
```

**Fillable mezők:** `name`, `email`, `phone`, `role`, `password`, `welcome_email_sent_at`

**Kapcsolatok:**
| Metódus | Típus | Leírás |
|---|---|---|
| `appointments()` | hasMany → Appointment | A felhasználó által létrehozott időpont-foglalások |
| `cars()` | hasMany → Car | A felhasználó tulajdonában lévő autók |
| `sentMessages()` | hasMany → Message (sender_id) | A felhasználó által küldött üzenetek |
| `receivedMessages()` | hasMany → Message (receiver_id) | A felhasználónak küldött üzenetek |
| `sales()` | hasMany → Sale (seller_id) | A felhasználó hirdetései |

**Fontos metódus:**
- `isAdmin(): bool` — Igaz, ha a felhasználó `role` mezője `'admin'`

**Cast-ok:**
- `password` → `hashed` (automatikus hash-elés)
- `welcome_email_sent_at` → `datetime`

---

### 5.2 `Car` modell
**Fájl:** `app/Models/Car.php`

```php
class Car extends Model  // SoftDeletes trait-tel
```

**Fillable mezők:** `user_id`, `make_model`, `vin`, `license_plate`, `year`

**Kapcsolatok:**
| Metódus | Típus | Leírás |
|---|---|---|
| `appointments()` | hasMany → Appointment | Az autóhoz tartozó időpontok |
| `issues()` | hasMany → Issue | Az autóhoz tartozó hibajegyek |
| `sales()` | hasMany → Sale | Az autó piactéri hirdetései |
| `user()` | belongsTo → User | Az autó tulajdonosa |
| `messages()` | hasMany → Message | Az autóhoz tartozó üzenetek |

---

### 5.3 `Appointment` modell
**Fájl:** `app/Models/Appointment.php`

```php
class Appointment extends Model  // SoftDeletes trait-tel
```

**Fillable mezők:** `user_id`, `car_id`, `work_number`, `date`, `time`, `description`, `status`, `service`, `service_stage`, `mechanic_name`, `total_cost`, `service_report`, `issues_found`, `critical_warning`, `customer_name`, `customer_phone`, `car_brand`, `car_model`, `car_year`, `car_engine`, `car_fuel_type`

**Automatikus munkaszám generálás:**
```php
protected static function booted(): void
{
    static::creating(function (Appointment $appointment) {
        if (!$appointment->work_number) {
            $appointment->work_number = 'MNK-' . strtoupper(substr(uniqid(), -6));
        }
    });
}
```
A `creating` event során, ha nincs munkaszám megadva, automatikusan generál egyet `MNK-` prefixszel és 6 karakteres egyedi azonosítóval.

**Kapcsolatok:**
| Metódus | Típus | Leírás |
|---|---|---|
| `user()` | belongsTo → User | A foglaló felhasználó |
| `car()` | belongsTo → Car | Az időponthoz tartozó autó |
| `servicePhotos()` | hasMany → ServicePhoto | A szerviz során készült fotók |

**Cast-ok:**
- `date` → `date:Y-m-d`
- `user_id`, `car_id` → `integer`

---

### 5.4 `Issue` modell
**Fájl:** `app/Models/Issue.php`

```php
class Issue extends Model  // SoftDeletes trait-tel
```

**Fillable mezők:** `car_id`, `category`, `description`, `urgency`

**Kapcsolatok:**
| Metódus | Típus | Leírás |
|---|---|---|
| `car()` | belongsTo → Car | A hibajegyhez tartozó autó |

---

### 5.5 `Message` modell
**Fájl:** `app/Models/Message.php`

```php
class Message extends Model  // SoftDeletes trait-tel
```

**Fillable mezők:** `car_id`, `sale_id`, `sender_id`, `receiver_id`, `message`, `is_read`

**Kapcsolatok:**
| Metódus | Típus | Leírás |
|---|---|---|
| `car()` | belongsTo → Car | A kapcsolódó autó |
| `sale()` | belongsTo → Sale | A kapcsolódó hirdetés |
| `sender()` | belongsTo → User (sender_id) | Az üzenet küldője |
| `receiver()` | belongsTo → User (receiver_id) | Az üzenet címzettje |

---

### 5.6 `Sale` modell
**Fájl:** `app/Models/Sale.php`

```php
class Sale extends Model  // SoftDeletes trait-tel
```

**Fillable mezők:** `car_id`, `vehicle_type`, `brand`, `model`, `body_type`, `engine_cc`, `fuel_type`, `documents_available`, `document_type`, `technical_inspection`, `buyer_id`, `seller_id`, `price`, `description`, `car_condition`, `mileage`, `is_active`

**Kapcsolatok:**
| Metódus | Típus | Leírás |
|---|---|---|
| `car()` | belongsTo → Car | A meghirdetett autó |
| `buyer()` | belongsTo → User (buyer_id) | A vevő |
| `seller()` | belongsTo → User (seller_id) | Az eladó |
| `images()` | hasMany → SaleImage (sort_order szerint rendezve) | A hirdetés képei |
| `messages()` | hasMany → Message | A hirdetéshez tartozó üzenetek |

**Cast-ok:**
- `price` → `decimal:2`, `mileage` → `integer`, `engine_cc` → `integer`
- `is_active`, `documents_available`, `technical_inspection` → `boolean`

---

### 5.7 `SaleImage` modell
**Fájl:** `app/Models/SaleImage.php`

**Fillable mezők:** `sale_id`, `path`, `sort_order`

**Kapcsolat:** `sale()` → belongsTo → Sale

---

### 5.8 `ServicePhoto` modell
**Fájl:** `app/Models/ServicePhoto.php`

**Fillable mezők:** `appointment_id`, `title`, `path`

**Kapcsolat:** `appointment()` → belongsTo → Appointment

---

### 5.9 `AdminNotification` modell
**Fájl:** `app/Models/AdminNotification.php`

**Fillable mezők:** `user_id`, `title`, `message`, `is_read`

**Kapcsolat:** `user()` → belongsTo → User

---

## 6. Útvonalak (Routes)

### 6.1 Webes útvonalak (`routes/web.php`)

#### Publikus útvonalak
| Metódus | Útvonal | Kontroller | Leírás |
|---|---|---|---|
| GET | `/` | Closure | Nyitóoldal (`welcome` nézet) |
| — | `/login`, `/register`, stb. | Auth routes | Laravel alap autentikációs route-ok |

#### Bejelentkezett felhasználóknak (`auth` middleware)
| Metódus | Útvonal | Kontroller@metódus | Leírás |
|---|---|---|---|
| GET | `/home` | HomeController@index | Átirányít dashboardra (role alapján) |
| GET | `/dashboard` | DashboardController@user | Felhasználói dashboard |
| GET | `/profile` | ProfileController@edit | Profil szerkesztése |
| PUT | `/profile` | ProfileController@update | Profil mentése |
| PATCH | `/notifications/{id}/read` | NotificationController@markAsRead | Értesítés olvasottá tétele |
| PATCH | `/notifications/read-all` | NotificationController@markAllAsRead | Összes értesítés olvasottá |
| CRUD | `/cars` | CarController (resource) | Autók kezelése |
| GET, POST | `/appointments` | AppointmentController (index, create, store, show) | Időpont foglalás |
| PATCH | `/appointments/{id}/cancel` | AppointmentController@cancel | Időpont lemondás |
| PATCH | `/appointments/{id}/reschedule` | AppointmentController@reschedule | Időpont átütemezés |
| GET | `/sales` | SaleController@index | Piactér listázás |
| GET | `/sales/{id}` | SaleController@show | Hirdetés megtekintése |
| CRUD | `/issues` | IssueController (resource) | Hibajegyek kezelése |
| POST | `/cars/{car}/messages` | MessageController@store | Üzenet küldése autóhoz |
| GET | `/cars/{car}/messages` | MessageController@carMessages | Üzenetek lekérése (JSON) |
| GET | `/messages/unread-count` | MessageController@unreadCount | Olvasatlan üzenetek száma |

#### Admin-only útvonalak (`auth` + `admin` middleware)
| Metódus | Útvonal | Kontroller@metódus | Leírás |
|---|---|---|---|
| GET | `/admin-dashboard` | DashboardController@admin | Admin dashboard |
| CRUD | `/sales` (create, store, edit, update, destroy) | SaleController | Hirdetések kezelése |
| DELETE | `/sales/{sale}/images/{image}` | SaleController@destroyImage | Hirdetéskép törlése |
| CRUD | `/admin/appointments` | AppointmentManagementController | Admin időpontkezelés |
| PATCH | `/admin/appointments/{id}/update-status` | AppointmentManagementController@updateStatus | Gyors státuszfrissítés |
| DELETE | `/admin/service-photos/{photo}` | AppointmentManagementController@destroyPhoto | Szervizfotó törlése |
| CRUD | `/admin/notifications` | AdminNotificationController | Értesítések kezelése |
| GET | `/admin/messages` | MessageController@adminIndex | Üzenetek listázása |
| GET | `/admin/messages/car/{car}` | MessageController@adminConversation | Beszélgetés megtekintése |

### 6.2 API útvonalak (`routes/api.php`)
| Metódus | Útvonal | Kontroller@metódus | Leírás |
|---|---|---|---|
| GET | `/api/vehicles/types` | VehicleDataController@types | Járműtípusok listája |
| GET | `/api/vehicles/brands` | VehicleDataController@brands | Márkák (type szűréssel) |
| GET | `/api/vehicles/models` | VehicleDataController@models | Modellek (type+brand szűréssel) |
| GET | `/api/vehicles/body-types` | VehicleDataController@bodyTypes | Karosszéria típusok |

---

## 7. Middleware

### 7.1 `AdminMiddleware`
**Fájl:** `app/Http/Middleware/AdminMiddleware.php`

```php
public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->isAdmin()) {
        return $next($request);
    }
    return redirect('/');
}
```

**Működés:**
1. Ellenőrzi, hogy a felhasználó be van-e jelentkezve
2. Ellenőrzi, hogy admin-e (`role === 'admin'`)
3. Ha igen: átengedi a kérést
4. Ha nem: átirányítja a főoldalra

**Regisztrálás:** A `bootstrap/app.php`-ban alias-ként van regisztrálva:
```php
$middleware->alias(['admin' => AdminMiddleware::class]);
```

---

## 8. Kontrollerek részletesen

### 8.1 `HomeController`
**Fájl:** `app/Http/Controllers/HomeController.php`

**Cél:** A bejelentkezés utáni átirányítás kezelése.

| Metódus | Leírás |
|---|---|
| `__construct()` | Az `auth` middleware-t alkalmazza — csak bejelentkezett felhasználóknak érhető el |
| `index()` | Ha admin → átirányít `/admin-dashboard`-ra; Ha user → átirányít `/dashboard`-ra |

---

### 8.2 `DashboardController`
**Fájl:** `app/Http/Controllers/DashboardController.php`

**Cél:** Az admin és felhasználói dashboard adatainak összeállítása.

#### `admin(Request $request)` — Admin Dashboard
Összegyűjti:
- **Szervizben lévő autók száma** (`inServiceCount`): `in_progress` státuszú időpontok egyedi `car_id`-ja
- **Mai időpontok** (`todayAppointments`): Az adott napon esedékes foglalások idő szerint rendezve
- **Mai kész autók** (`todayCompletedCars`): `completed` + `ready` státuszú mai foglalások
- **Naptár adatok** (`calendarAppointments`): Az aktuális hónap foglalásai napokra csoportosítva
- **Havi grafikon** (`monthlyLabels`, `monthlyCounts`): Az utolsó 6 hónap foglalásainak száma

#### `user()` — Felhasználói Dashboard
Összegyűjti:
- **Saját időpontok** (`appointments`): Az összes saját foglalás
- **Szervizben lévő autók** (`inServiceCount`): Saját `in_progress` foglalások
- **Közelgő időpontok** (`upcomingAppointmentsCount`): Mai és jövőbeli foglalások száma
- **Autók száma** (`totalCarsCount`): Saját autók darabszáma
- **Befejezett szervizek** (`completedServicesCount`): Lezárt foglalások száma
- **Értesítések** (`adminNotifications`): Legutóbbi 10 saját vagy globális értesítés
- **Következő időpont** (`nextAppointment`): A legközelebbi jövőbeli foglalás
- **Friss hirdetések** (`latestSales`): Legújabb 8 aktív piactéri hirdetés

---

### 8.3 `CarController`
**Fájl:** `app/Http/Controllers/CarController.php`

**Trait:** `AdminHelpers` — admin-ellenőrzés és autólista szűrés

#### Privát metódusok:
- **`ensureCarOwnership(Car $car)`**: Ha nem admin, és nem a saját autója → 403-as hiba

#### CRUD metódusok:

| Metódus | Működés |
|---|---|
| `index()` | Admin: összes autó; User: csak saját autók. Eager load: `appointments`, olvasatlan üzenetek száma. |
| `create()` | Autó létrehozó form megjelenítése |
| `store(StoreCarRequest)` | Validáció → Autó létrehozása `user_id`-vel → Átirányítás sikerüzenettel |
| `show(Car)` | Tulajdonjog ellenőrzés → Autó részleteinek megjelenítése |
| `edit(Car)` | Tulajdonjog ellenőrzés → Szerkesztő form |
| `update(UpdateCarRequest, Car)` | Tulajdonjog ellenőrzés → Validált adatok mentése |
| `destroy(Car)` | Tulajdonjog ellenőrzés → Soft delete |

---

### 8.4 `AppointmentController`
**Fájl:** `app/Http/Controllers/AppointmentController.php`

**Trait:** `AdminHelpers`

#### Privát metódusok:
- **`ensureAppointmentOwnership(Appointment)`**: Csak a tulajdonos vagy admin érheti el
- **`validateStoreData(Request)`**: Validálja a foglalás adatait (`car_id`, `date`, `time`, `description`, `service`)
- **`ensureCarOwnershipById(int $carId, int $userId)`**: Ellenőrzi, hogy a user a saját autójára foglal-e
- **`hasConfirmedConflict(string $date, string $time)`**: Megnézi, van-e már `confirmed` foglalás az adott időpontra
- **`throwTimeConflictValidationError()`**: Validációs hibát dob ütközés esetén

#### CRUD és extra metódusok:

| Metódus | Működés |
|---|---|
| `index()` | Admin: összes időpont; User: saját időpontok. Eager load: `car`, `user`. |
| `create()` | Autólista lekérése (user saját / admin összes) → Form megjelenítése |
| `store(Request)` | Validálás → Autó-tulajdonjog ellenőrzés → Ütközés ellenőrzés → Mentés `pending` státusszal → **E-mail küldés** (visszaigazolás) → Átirányítás |
| `show(Appointment)` | Tulajdonjog ellenőrzés → Eager load: `car`, `user`, `servicePhotos` → Megjelenítés |
| `cancel(Appointment)` | Tulajdonjog ellenőrzés → Csak `pending`/`confirmed` mondható le → Státusz: `cancelled` → **Értesítés** küldése a felhasználónak |
| `reschedule(Request, Appointment)` | Tulajdonjog ellenőrzés → Validálás (jövőbeli dátum) → Ütközés ellenőrzés → Dátum/idő frissítés → Státusz: `pending` → **Értesítés** küldése |

**E-mail küldés a `store()` metódusban:**
```php
Mail::to($appointment->user->email)
    ->send(new AppointmentConfirmationMail($appointment));
```
Ha az email küldés sikertelen, loggolásra kerül, de nem akadályozza meg a foglalást.

---

### 8.5 `IssueController`
**Fájl:** `app/Http/Controllers/IssueController.php`

**Trait:** `AdminHelpers`

**Jogosultságkezelés:** Minden metódus `$this->authorize()` hívást használ a `IssuePolicy` alapján.

| Metódus | Működés |
|---|---|
| `index()` | Admin: összes hibajegy; User: csak saját autóinak hibajegyei (a `car.user_id` alapján szűrve) |
| `create()` | Autólista (saját/összes) → Form |
| `store(StoreIssueRequest)` | Validálás → Hibajegy létrehozása |
| `show(Issue)` | Policy ellenőrzés → Eager load: `car` → Megjelenítés |
| `edit(Issue)` | Policy ellenőrzés → Szerkesztő form |
| `update(UpdateIssueRequest, Issue)` | Policy ellenőrzés → Frissítés |
| `destroy(Issue)` | Policy ellenőrzés → Soft delete |

---

### 8.6 `SaleController`
**Fájl:** `app/Http/Controllers/SaleController.php`

#### Privát metódus:
- **`getFormDependencies()`**: Visszaadja a `cars` és `users` listákat a form-hoz

| Metódus | Működés |
|---|---|
| `index()` | Az összes hirdetés lekérése (szűrőkhöz adatok) + paginálás (10/oldal). Eager load: `car`, `buyer`, `seller`, `images` |
| `create()` | Form megjelenítése az autó/felhasználó listával |
| `store(StoreSaleRequest)` | Validálás → Hirdetés létrehozása (seller_id = aktuális user) → **Képfeltöltés**: minden kép `sales/` mappába kerül, `SaleImage` rekordok létrehozása `sort_order`-rel |
| `show(Sale)` | Eager load → Hirdetés megtekintése |
| `edit(Sale)` | Képek eager load → Szerkesztő form |
| `update(UpdateSaleRequest, Sale)` | Frissítés → Új képek hozzáadása (max sort_order + 1-től) |
| `destroy(Sale)` | Policy ellenőrzés → Soft delete |
| `destroyImage(Sale, SaleImage)` | Ellenőrzi, hogy a kép a hirdetéshez tartozik-e → Fizikai fájl törlése Storage-ből → DB rekord törlése |

**Képfeltöltés logikája:**
```php
foreach ($request->file('images') as $i => $file) {
    $sale->images()->create([
        'path' => $file->store('sales', 'public'),  // storage/app/public/sales/
        'sort_order' => $i,
    ]);
}
```

---

### 8.7 `MessageController`
**Fájl:** `app/Http/Controllers/MessageController.php`

#### Metódusok:

| Metódus | Működés |
|---|---|
| `store(Request, Car)` | **Üzenet küldése autóhoz.** Validálja az üzenetet (max 2000 karakter). Jogosultság: az autó tulajdonosa, admin, vagy aktív hirdetéshez tartozó user. **Címzett meghatározása:** Ha admin küld → a legutolsó nem-admin üzenet küldőjének megy. Ha user küld → az adminnak megy. Küldés után **értesítés** jön létre. Támogatja JSON választ is (AJAX). |
| `carMessages(Car)` | **Üzenetek lekérése (JSON).** Jogosultság-ellenőrzés → Olvasatlanok megjelölése olvasottként → Üzenetek időrendben, `sender` eager load-dal → JSON válasz formátum: `id`, `message`, `sender_name`, `is_mine`, `created_at` |
| `adminIndex()` | **Admin üzenet-áttekintő.** Lekéri az összes autót, amelyhez van üzenet. Olvasatlan üzenetek számával és utolsó üzenettel kiegészítve, olvasatlanok szerint rendezve. |
| `adminConversation(Car)` | **Admin: beszélgetés megtekintése.** Adott autó üzenetei időrendben → Olvasatlanok jelölése olvasottként. |
| `unreadCount()` | **Olvasatlan szám (JSON).** Badge-hez használva a UI-ban. |

**Címzett-meghatározás logikája:**
```php
if ($user->isAdmin()) {
    // Admin válaszol: a legutóbbi nem-admin üzenet küldőjének
    $receiverId = $lastUserMessage ? $lastUserMessage->sender_id : $car->user_id;
} else {
    // User ír: az adminnak megy
    $receiverId = User::where('role', 'admin')->value('id');
}
```

---

### 8.8 `Admin\AppointmentManagementController`
**Fájl:** `app/Http/Controllers/Admin/AppointmentManagementController.php`

**Cél:** Az admin oldali teljes szervizfolyamat-kezelés.

**Konstansok:**
- `QUICK_UPDATE_STATUSES`: `confirmed`, `cancelled`, `completed`
- `MECHANIC_POOL`: 20 szerelő neve (random hozzárendeléshez)

#### Metódusok:

| Metódus | Működés |
|---|---|
| `index(Request)` | Szűrhető listázás: név, autó, rendszám, dátum alapján |
| `create()` | Admin időpont-létrehozó form (rendszeren kívüli ügyfeleknek) |
| `store(Request)` | Validálás (customer_name, phone, car adatok, stb.) → Ütközésellenőrzés → Mentés `pending` státusszal |
| `show(Appointment)` | Időpont részletei (user, car eager load) |
| `edit(Appointment)` | Szerkesztő form szervizfotókkal, autólistával, szerelő-pool-lal |
| `update(UpdateAppointmentRequest, Appointment)` | Validálás → Ütközésellenőrzés (confirmed státusznál) → Frissítés → **Ha kész és átvehető** (`completed` + `ready`): értesítés küldése → **Fotó feltöltés** ha van csatolva |
| `updateStatus(Request, Appointment)` | Gyors státuszfrissítés (confirmed/cancelled/completed) → Ütközésellenőrzés → **Ha kész**: értesítés |
| `destroyPhoto(ServicePhoto)` | Fizikai fájl + DB rekord törlése |

**Privát metódusok:**
- **`sendReadyNotification(int $userId, ?Car $car)`**: `AdminNotification` létrehozása a felhasználónak, hogy az autó elkészült
- **`hasConfirmedConflict(string $date, string $time, ?int $ignoreId)`**: Ütközés-ellenőrzés, a saját rekordot kihagyva

---

### 8.9 `Admin\AdminNotificationController`
**Fájl:** `app/Http/Controllers/Admin/AdminNotificationController.php`

| Metódus | Működés |
|---|---|
| `index(Request)` | Szűrhető listázás: név, rendszám, alvázszám, dátum alapján. Paginálás: 20/oldal. |
| `create(Request)` | Form: felhasználó-lista (nem admin-ok) |
| `store(Request)` | Validálás (`title`, `message`, `user_id` opcionális) → Értesítés létrehozása |
| `destroy(AdminNotification)` | Értesítés törlése |

---

### 8.10 `NotificationController`
**Fájl:** `app/Http/Controllers/NotificationController.php`

| Metódus | Működés |
|---|---|
| `markAsRead(AdminNotification)` | Jogosultság-ellenőrzés → `is_read = true` → JSON válasz |
| `markAllAsRead()` | A felhasználó összes saját + globális olvasatlan értesítése olvasottá |

---

### 8.11 `ProfileController`
**Fájl:** `app/Http/Controllers/ProfileController.php`

| Metódus | Működés |
|---|---|
| `edit()` | A bejelentkezett felhasználó profil-szerkesztő formja |
| `update(Request)` | Validálás: `name` (kötelező), `email` (egyedi, kivéve sajátját), `phone` (opcionális) → Mentés |

---

### 8.12 `EmailsController`
**Fájl:** `app/Http/Controllers/EmailsController.php`

| Metódus | Működés |
|---|---|
| `WelcomeEmail()` | Üdvözlő e-mail küldése a bejelentkezett felhasználónak. Ellenőrzi, hogy van-e érvényes email cím. JSON választ ad. |

---

### 8.13 `VehicleDataController`
**Fájl:** `app/Http/Controllers/VehicleDataController.php`

**Cél:** Járműadat API a kaszkád (lépcsőzetes) kiválasztókhoz.

| Metódus | Paraméterek | Visszatérés |
|---|---|---|
| `types()` | — | JSON tömb: járműtípusok (`Autó`, `Motor`, stb.) |
| `brands(Request)` | `?type=Autó&q=keresés` | JSON tömb: márkák a típushoz, szűréssel |
| `models(Request)` | `?type=Autó&brand=BMW&q=keresés` | JSON tömb: modellek a típus+márkához, szűréssel |
| `bodyTypes(Request)` | `?type=Autó` | JSON tömb: karosszéria típusok |

Az adatok a `config/vehicles.php`-ból jönnek.

---

### 8.14 `Traits\AdminHelpers`
**Fájl:** `app/Http/Controllers/Traits/AdminHelpers.php`

**Cél:** Közös admin-segédfüggvények, amelyeket több kontroller is használ.

| Metódus | Visszatérés | Leírás |
|---|---|---|
| `isAdmin()` | `bool` | Igaz, ha a bejelentkezett felhasználó admin |
| `currentUserId()` | `?int` | Az aktuális felhasználó ID-ja, vagy null |
| `userCarsQuery()` | `Builder` | Auto lekérdezés: admin → összes; user → csak saját |

---

### 8.15 Auth Kontrollerek

#### `LoginController`
**Fájl:** `app/Http/Controllers/Auth/LoginController.php`

- Laravel `AuthenticatesUsers` trait-et használja
- **`authenticated()`** felülírás: admin → `/admin-dashboard`; user → `/dashboard`
- Middleware: `guest` (kivéve `logout`)

#### `RegisterController`
**Fájl:** `app/Http/Controllers/Auth/RegisterController.php`

- Laravel `RegistersUsers` trait-et használja
- **Validáció:** name, email (egyedi), password (min 8, megerősítéssel)
- **`create()`**: User létrehozása hash-elt jelszóval
- **`registered()`** felülírás: Sikeres regisztráció után **üdvözlő e-mail** küldése:
  ```php
  Mail::to($user->email)->send(new WelcomeMail($user->name));
  $user->update(['welcome_email_sent_at' => now()]);
  ```
  - Csak egyszer küldi el (ellenőrzi `welcome_email_sent_at`-ot)
  - Hiba esetén loggol, nem akadályozza a regisztrációt

---

## 9. Form Request validációk

### 9.1 `StoreCarRequest` / `UpdateCarRequest`
| Mező | Szabály |
|---|---|
| `make_model` | Kötelező, szöveg, max 255 |
| `vin` | Opcionális, szöveg, max 255 |
| `license_plate` | Opcionális, szöveg, max 20 |
| `year` | Opcionális, egész szám, 1900/1920–aktuális év |

### 9.2 `StoreIssueRequest` / `UpdateIssueRequest`
| Mező | Szabály |
|---|---|
| `car_id` | Kötelező, egész, létező `cars.id` |
| `category` | Kötelező, szöveg, max 255 |
| `description` | Kötelező, szöveg |
| `urgency` | Kötelező, értékek: `low`, `medium`, `high` |

### 9.3 `StoreSaleRequest`
| Mező | Szabály |
|---|---|
| `car_id` | Opcionális, egész, létező |
| `vehicle_type` | Kötelező, szöveg |
| `brand` | Kötelező, szöveg |
| `model` | Kötelező, szöveg |
| `body_type` | Opcionális, szöveg |
| `engine_cc` | Opcionális, egész, min 0 |
| `fuel_type` | Opcionális, szöveg |
| `documents_available` | Opcionális, boolean |
| `document_type` | Opcionális, szöveg |
| `technical_inspection` | Opcionális, boolean |
| `price` | Kötelező, szám, min 0 |
| `description` | Opcionális, szöveg |
| `car_condition` | Kötelező, szöveg |
| `mileage` | Opcionális, egész, min 0 |
| `images` | Opcionális, tömb, max 10 kép |
| `images.*` | Kép (jpeg, png, jpg, webp), max 5 MB |

### 9.4 `UpdateSaleRequest`
Ugyanaz mint `StoreSaleRequest`, plusz: `is_active` (opcionális, boolean).

### 9.5 `UpdateAppointmentRequest`
| Mező | Szabály |
|---|---|
| `car_id` | Kötelező, egész, létező |
| `date` | Kötelező, dátum |
| `time` | Kötelező, HH:mm formátum |
| `description` | Opcionális, max 1000 |
| `service` | Opcionális, max 255 |
| `status` | Kötelező: `pending`, `confirmed`, `in_progress`, `completed`, `cancelled` |
| `service_stage` | Opcionális: `received`, `inspected`, `in_progress`, `ready` |
| `mechanic_name` | Opcionális, max 255 |
| `total_cost` | Opcionális, szám, min 0 |
| `service_report` | Opcionális, max 5000 |
| `issues_found` | Opcionális, max 5000 |
| `critical_warning` | Opcionális, max 5000 |
| `photo` | Opcionális, kép, max 5 MB |
| `photo_title` | Opcionális, max 255 |

---

## 10. Policy-k (jogosultságkezelés)

### 10.1 `AppointmentPolicy`
| Művelet | Szabály |
|---|---|
| `viewAny` | Bárki |
| `view` | Admin VAGY tulajdonos (`user_id === auth user`) |
| `create` | Bárki |
| `update` | Admin VAGY tulajdonos |
| `delete` | Admin VAGY tulajdonos |
| `restore` | Csak admin |
| `forceDelete` | Csak admin |

### 10.2 `CarPolicy`
Ugyanaz a logika mint `AppointmentPolicy` — a `user_id` alapján.

### 10.3 `IssuePolicy`
| Művelet | Szabály |
|---|---|
| `viewAny` / `create` | Bárki |
| `view` / `update` / `delete` | Admin VAGY az autó tulajdonosa (`$issue->car()->where('user_id', ...)`) |
| `restore` / `forceDelete` | Csak admin |

### 10.4 `MessagePolicy`
| Művelet | Szabály |
|---|---|
| `viewAny` / `create` | Bárki |
| `view` | Küldő, címzett, vagy admin |
| `update` | Csak a küldő |
| `delete` | Küldő vagy admin |
| `restore` / `forceDelete` | Csak admin |

### 10.5 `SalePolicy`
| Művelet | Szabály |
|---|---|
| `viewAny` / `view` | Bárki |
| `create` | Csak admin |
| `update` / `delete` | Admin VAGY az eladó (`seller_id`) |
| `restore` / `forceDelete` | Csak admin |

---

## 11. E-mail küldés (Mailable)

### 11.1 `AppointmentConfirmationMail`
**Fájl:** `app/Mail/AppointmentConfirmationMail.php`

**Mikor küldődik:** Új időpont-foglalás létrehozásakor (`AppointmentController@store`)

**Tárgy:** `Időpont visszaigazolás – AutoNex`

**Nézet:** `emails.AppointmentConfirmationMail`

**Átadott adatok:**
- `userName`: A foglaló felhasználó neve
- `date`: A foglalás dátuma (`Y. m. d.` formátum)
- `time`: A foglalás időpontja
- `service`: A kért szolgáltatás
- `car`: Az autó objektum
- `workNumber`: A generált munkaszám

---

### 11.2 `WelcomeMail`
**Fájl:** `app/Mail/WelcomeMail.php`

**Mikor küldődik:** Sikeres regisztráció után (`RegisterController@registered`)

**Tárgy:** `Üdvözlünk az AutoNex-ben!`

**Nézet:** `emails.WelcomeMail`

**Átadott adatok:**
- `userName`: A felhasználó neve
- `appUrl`: Az alkalmazás URL-je (`config('app.url')`)

---

## 12. Értesítési rendszer

Az értesítési rendszer az `AdminNotification` modellen alapul.

### Értesítés létrehozásának helyei:
1. **Időpont lemondásakor** (`AppointmentController@cancel`): A felhasználó kap értesítést
2. **Időpont átütemezésekor** (`AppointmentController@reschedule`): A felhasználó kap értesítést
3. **Szerviz elkészültekor** (`AppointmentManagementController@update/updateStatus`): Ha `completed` + `ready`
4. **Üzenet küldésekor** (`MessageController@store`): A címzett kap értesítést
5. **Admin kézi értesítés** (`AdminNotificationController@store`): Admin bármelyik felhasználónak küldhet

### Értesítés megjelenítése:
- A `AppServiceProvider` `View::composer`-ben a `layouts.app` nézet mindig megkapja:
  - `navNotifications`: Legutóbbi 8 értesítés
  - `navUnreadCount`: Olvasatlan értesítések száma
- A felhasználó `markAsRead()` és `markAllAsRead()` metódusokkal kezeli az olvasottságot

---

## 13. Service Provider

### `AppServiceProvider`
**Fájl:** `app/Providers/AppServiceProvider.php`

**`boot()` metódus:**

`View::composer('layouts.app', ...)` — Minden oldalbetöltésnél a fő layout-ba injektálja:

**Nem admin felhasználóknak:**
- `navNotifications`: A saját + globális (user_id = null) értesítések, legutóbbi 8
- `navUnreadCount`: Az ezek közül olvasatlanok száma

**Admin felhasználóknak:**
- `adminUnreadMsgCount`: Az adminnak szóló olvasatlan üzenetek száma (badge-hez)

---

## 14. Seederek

### `DatabaseSeeder`
**Futtatási sorrend:**
1. `UserSeeder` → 2. `CarSeeder` → 3. `SaleSeeder` → 4. `IssueSeeder` → 5. `MessageSeeder` → 6. `AppointmentSeeder`

### `UserSeeder`
- **1 admin felhasználó:** `admin@admin.com` / `admin123` (role: `admin`)
- **10 teszt felhasználó:** Factory-val generálva

A többi seeder a megfelelő Factory-t használja tesztadatok generálására.

---

## 15. API végpontok

### Járműadat API (`/api/vehicles/`)

**Cél:** A piactéri hirdetés-létrehozó form kaszkád (lépcsőzetes) kiválasztóinak táplálása.

**Folyamat:**
1. `GET /api/vehicles/types` → Visszaadja: `["Autó", "Motor", ...]`
2. `GET /api/vehicles/brands?type=Autó&q=bm` → Visszaadja: `["BMW"]`
3. `GET /api/vehicles/models?type=Autó&brand=BMW&q=3` → Visszaadja: `["3-as"]`
4. `GET /api/vehicles/body-types?type=Autó` → Visszaadja: `["Sedan", "Kombi", ...]`

A keresés (`q` paraméter) case-insensitive, substring-match alapú.

---

## 16. Konfiguráció – Járműadatok

**Fájl:** `config/vehicles.php`

Kaszkád struktúra:
```php
'types' => [
    'Autó' => [
        'BMW' => ['1-es', '2-es', '3-as', ...],
        'Audi' => ['A1', 'A2', 'A3', ...],
        // ...
    ],
    'Motor' => [ ... ],
],
'body_types' => [
    'Autó' => ['Sedan', 'Kombi', 'SUV', ...],
    // ...
]
```

Ez az adatforrás a `VehicleDataController` számára, amely JSON-ként szolgálja ki a frontend-nek.

---

## 17. Autentikáció (Auth)

### Bejelentkezés
- **Route:** `POST /login`
- **Kontroller:** `LoginController` (Laravel `AuthenticatesUsers` trait)
- **Bejelentkezés után:** Admin → `/admin-dashboard`; User → `/dashboard`

### Regisztráció
- **Route:** `POST /register`
- **Kontroller:** `RegisterController` (Laravel `RegistersUsers` trait)
- **Validáció:** név (kötelező), email (egyedi), jelszó (min 8, megerősítéssel)
- **Regisztráció után:** Üdvözlő email küldése → Átirányítás `/home`-ra → Onnan dashboard-ra

### Jelszó-visszaállítás
- `ForgotPasswordController`: Jelszó-visszaállító email küldése
- `ResetPasswordController`: Új jelszó beállítása token alapján
- `ConfirmPasswordController`: Jelszó megerősítése érzékeny műveleteknél

### Middleware-ek
| Middleware | Alkalmazás |
|---|---|
| `auth` | Bejelentkezett felhasználó szükséges |
| `admin` | Admin jogosultság szükséges (bejelentkezés + admin role) |
| `guest` | Csak vendég (nem bejelentkezett) felhasználóknak |

---

## 18. Összegzés – Funkciótérkép

```
AutoNex
├── Autentikáció
│   ├── Bejelentkezés (role-alapú átirányítás)
│   ├── Regisztráció (+ üdvözlő email)
│   └── Jelszó-visszaállítás
│
├── Felhasználói funkciók
│   ├── Dashboard (statisztikák, értesítések, következő időpont, friss hirdetések)
│   ├── Autókezelés (CRUD + üzenetek)
│   ├── Időpont-foglalás (létrehozás, lemondás, átütemezés + email visszaigazolás)
│   ├── Hibajegyek (CRUD, autóhoz kötve)
│   ├── Piactér böngészés (hirdetések megtekintése)
│   ├── Üzenetküldés (autó-alapú chat az adminnal)
│   ├── Értesítések kezelése (olvasottá jelölés)
│   └── Profilszerkesztés
│
├── Admin funkciók
│   ├── Admin Dashboard (naptár, statisztikák, grafikonok)
│   ├── Időpont-kezelés (teljes CRUD + szervizfolyamat)
│   │   ├── Státuszok: pending → confirmed → in_progress → completed
│   │   ├── Szerviz fázisok: received → inspected → in_progress → ready
│   │   ├── Szerelő hozzárendelés
│   │   ├── Szervizfotók feltöltése
│   │   ├── Szervizjelentés, talált hibák, kritikus figyelmeztetés
│   │   └── Költség rögzítése
│   ├── Piactér kezelés (hirdetések CRUD + képkezelés)
│   ├── Értesítés küldés (egyéni vagy globális)
│   ├── Üzenet-kezelés (összes beszélgetés áttekintése, válaszadás)
│   └── Rendszeren kívüli ügyfél időpont rögzítése
│
└── API
    └── Járműadatok (típus → márka → modell kaszkád)
```

---

*Dokumentáció generálva: 2026. április 20.*
