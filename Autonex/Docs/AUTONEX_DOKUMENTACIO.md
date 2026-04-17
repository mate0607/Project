# Szerző

- **Név:** `[Név]`
- **ETR-azonosító:** `[ETR-azonosító]`
- **Neptun-azonosító:** `[Neptun-azonosító]`
- **Drótposta-cím:** `[email]`
- **Kurzuskód:** `[kurzuskód]`
- **Gyakorlatvezető neve:** `[gyakorlatvezető neve]`
- **Feladatsorszám:** `[feladatsorszám]`

---

# Tartalom

- [Felhasználói dokumentáció](#felhasználói-dokumentáció)
  - [Feladat](#feladat)
  - [Környezet](#környezet)
  - [Telepítés](#telepítés)
  - [Használat](#használat)
    - [Az alkalmazás indítása](#az-alkalmazás-indítása)
    - [Regisztráció és bejelentkezés](#regisztráció-és-bejelentkezés)
    - [Felhasználói műveletek](#felhasználói-műveletek)
    - [Admin műveletek](#admin-műveletek)
  - [Hibalehetőségek](#hibalehetőségek)
- [Fejlesztői dokumentáció](#fejlesztői-dokumentáció)
  - [Feladat (fejlesztői)](#feladat-fejlesztői)
  - [Specifikáció](#specifikáció)
  - [Környezet (fejlesztői)](#környezet-fejlesztői)
  - [Forráskód](#forráskód)
  - [Megoldás](#megoldás)
    - [Adatbázis-séma](#adatbázis-séma)
    - [Programfelépítés](#programfelépítés)
    - [Útvonalak (Routes)](#útvonalak-routes)
    - [Modellek és kapcsolatok](#modellek-és-kapcsolatok)
    - [Vezérlők (Controllers)](#vezérlők-controllers)
    - [Jogosultságkezelés](#jogosultságkezelés)
    - [E-mail rendszer](#e-mail-rendszer)
  - [Tesztelés](#tesztelés)
  - [Fejlesztési lehetőségek](#fejlesztési-lehetőségek)

---

# Felhasználói dokumentáció

## Feladat

Az **AutoNex** egy Laravel keretrendszerre épülő autószerviz ügyfélkezelő webalkalmazás, amely az alábbi funkciókat biztosítja:

- **Felhasználókezelés:** regisztráció, bejelentkezés, e-mail hitelesítés, profil szerkesztése.
- **Gépjármű-nyilvántartás:** a felhasználó saját autóinak CRUD kezelése (márka/modell, alvázszám, rendszám, évjárat).
- **Időpont-foglalás:** szervizidőpont létrehozása, lemondása, átütemezése; ütközésvizsgálat; visszaigazoló e-mail küldése.
- **Hibajegy-kezelés:** gépjárművekhez tartozó hibabejelentések nyilvántartása (kategória, leírás, sürgősség).
- **Autópiactér:** eladásra kínált járművek listázása, többképes hirdetéskezelés, szűrés.
- **Üzenetrendszer:** vevők és eladók közötti beszélgetések az egyes hirdetésekhez.
- **Értesítési rendszer:** rendszerszintű értesítések (célzottan vagy broadcast módon).
- **Admin felület:** naptáras áttekintés, szervizfolyamat-követés, szervizfotók, költség- és jelentéskezelés.

## Környezet

| Követelmény | Verzió / leírás |
|---|---|
| PHP | ≥ 8.2 |
| Composer | ≥ 2.x |
| Node.js | ≥ 18.x |
| npm | ≥ 9.x |
| MySQL / MariaDB | ≥ 8.0 / 10.x |
| Webböngésző | Bármely modern böngésző (Chrome, Firefox, Edge, Safari) |
| Operációs rendszer | Windows 10+, macOS, Linux |

Az alkalmazás Laravel keretrendszert használ, a frontend Blade sablonokkal, Bootstrap 5, Tailwind CSS 4 és Vite build-rendszerrel készült.

## Telepítés

1. A projekt klónozása vagy kicsomagolása a kívánt könyvtárba.
2. Függőségek telepítése:
   ```
   composer install
   npm install
   ```
3. Környezeti fájl előkészítése:
   ```
   cp .env.example .env
   php artisan key:generate
   ```
4. Adatbázis beállítása a `.env` fájlban:
   ```
   DB_DATABASE=autonex
   DB_USERNAME=root
   DB_PASSWORD=...
   ```
5. Migrációk és seeder futtatása:
   ```
   php artisan migrate --seed
   ```
6. Tárhelylinkek létrehozása (képfeltöltésekhez):
   ```
   php artisan storage:link
   ```
7. Frontend eszközök buildelése:
   ```
   npm run build
   ```

## Használat

### Az alkalmazás indítása

Fejlesztői módban:
```
php artisan serve
npm run dev
```
Az alkalmazás alapértelmezetten a `http://localhost:8000` címen érhető el.

### Regisztráció és bejelentkezés

1. A nyitóoldalon (`/`) kattintson a **Regisztráció** gombra.
2. Adja meg a nevét, e-mail címét, telefonszámát és jelszavát.
3. A regisztráció után az alkalmazás visszaigazoló e-mailt küld – kattintson a benne lévő linkre az e-mail hitelesítéséhez.
4. Hitelesítés után a **bejelentkezés** oldalon beléphet.

> **Alapértelmezett admin fiók (seederből):**
> - E-mail: `admin@admin.com`
> - Jelszó: `admin123`

### Felhasználói műveletek

#### Vezérlőpult (`/dashboard`)
- Közelgő időpontok száma
- Szervizben lévő autók
- Összes saját autó
- Elvégzett szervizek
- Legutóbbi időpontok
- Értesítések

#### Gépjárművek kezelése (`/cars`)
- **Listázás:** az összes saját autó áttekintése.
- **Hozzáadás:** márka/modell, alvázszám (VIN), rendszám, évjárat megadásával.
- **Szerkesztés / törlés:** meglévő autó adatainak módosítása vagy (soft) törlése.

#### Időpont-foglalás (`/appointments`)
- **Foglalás:** dátum, idő, autó kiválasztása, leírás megadása. A rendszer ellenőrzi az ütközéseket.
- **Visszaigazolás:** sikeres foglalás után e-mailt kap a munkalapszámmal (pl. `MNK-XXXXXX`).
- **Lemondás:** függőben lévő vagy visszaigazolt időpont lemondható.
- **Átütemezés:** új dátum/idő megadásával átütemezhető.

#### Hibajegyek (`/issues`)
- **Bejelentés:** autóhoz rendelt hiba kategóriájának, leírásának és sürgősségének (alacsony / közepes / magas) rögzítése.
- **Szerkesztés / törlés:** meglévő hibajegy módosítása.

#### Piactér (`/sales`)
- **Böngészés:** aktív hirdetések oldalankénti (10/oldal) megtekintése.
- **Részletek:** hirdetés képei, jármű adatai, ár, állapot, futásteljesítmény, dokumentumok.

#### Üzenetek (`/messages`)
- **Beszélgetés indítása:** hirdetéshez tartozó üzenet küldése az eladónak.
- **Beszélgetések:** hirdetésenkénti üzenetfolyamok áttekintése; beérkező üzenetek automatikusan olvasottá válnak.
- **Szerkesztés / törlés:** saját üzenet módosítása vagy törlése.

#### Értesítések
- Az értesítések a vezérlőpulton és a navigációs sávon jelennek meg.
- Egyes vagy összes értesítés olvasottá jelölhető.

#### Profil szerkesztése (`/profile`)
- Név, e-mail cím, telefonszám módosítása.

### Admin műveletek

Az admin felhasználók a fenti funkciókon túl az alábbiakhoz is hozzáférnek:

#### Admin vezérlőpult (`/admin-dashboard`)
- Szervizben lévő autók száma
- Mai időpontok részletei
- Elkészült járművek
- Havi naptárnézet az összes időponttal
- Utolsó 6 hónap statisztikái (grafikon)

#### Időpontkezelés (`/admin/appointments`)
- **Listázás:** minden időpont szűrőkkel (név, autó, rendszám, dátum).
- **Létrehozás:** admin által manuálisan rögzített időpont (ügyfél név, telefon, jármű adatokkal).
- **Szerkesztés:** szervizfolyamat-követés mezők (szerelő neve, összköltség, szervizjelentés, talált hibák, kritikus figyelmeztetés, szervizfotók feltöltése).
- **Státuszkezelés:** gyors státuszmódosítás (visszaigazolt / lemondott / kész).
- **Készre jelölés:** ha a státusz „kész" és a szervizfázis „kész", értesítés küldése az ügyfélnek.

#### Hirdetéskezelés (`/sales/create`, `/sales/{sale}/edit`)
- Új eladási hirdetés létrehozása (járműtípus, modell, karosszéria, motor, üzemanyag, dokumentumok, műszaki vizsga, ár, állapot, futásteljesítmény, több kép feltöltéssel).
- Meglévő hirdetés szerkesztése / törlése.
- Hirdetésképek egyenkénti törlése.

#### Értesítések kezelése (`/admin/notifications`)
- Értesítés küldése egyedi felhasználónak vagy broadcast (minden felhasználónak).
- Értesítések szűrése, törlése.

## Hibalehetőségek

| Hiba | Magyarázat |
|---|---|
| E-mail hitelesítés nélkül nem érhető el az alkalmazás | A felhasználónak ki kell kattintania a regisztrációs visszaigazoló e-mailben lévő linket. |
| Időpont-ütközés | Ha az adott dátumra és időpontra már van visszaigazolt foglalás, a rendszer hibaüzenetet ad. |
| Nem megfelelő dátumformátum | A dátum és idő mezőket a megadott formátumban kell kitölteni. |
| Hozzáférés megtagadva (403) | A felhasználó csak a saját erőforrásait kezelheti; admin jogok nélkül az admin felület nem érhető el. |
| Képfeltöltési hiba | A storage link hiánya esetén a feltöltött képek nem jelennek meg (`php artisan storage:link`). |
| Levélküldési hiba | Ha az SMTP konfiguráció hibás, a rendszer naplózza a hibát, de az alkalmazás tovább működik. |

---

# Fejlesztői dokumentáció

## Feladat (fejlesztői)

Autószerviz ügyfélkezelő webalkalmazás fejlesztése Laravel keretrendszerben, amely a következő alrendszereket tartalmazza:
- felhasználó- és jogosultságkezelés (user / admin szerepkörök),
- gépjármű-nyilvántartás (CRUD),
- időpont-foglalási rendszer ütközésvizsgálattal és e-mail értesítéssel,
- hibajegy-kezelés járművenként,
- autópiactér többképes hirdetésekkel és üzenetrendszerrel,
- admin vezérlőpult naptáras nézettel és szervizfolyamat-követéssel,
- rendszerszintű értesítések (célzott és broadcast).

## Specifikáció

**Bemenet:**
- Felhasználói adatok: név, e-mail, telefon, jelszó
- Gépjármű adatok: márka/modell, VIN, rendszám, évjárat
- Időpont adatok: dátum, idő, autó, leírás, szerviz típusa
- Hibajegy adatok: autó, kategória, leírás, sürgősség
- Hirdetés adatok: járműtípus, modell, karosszéria, motor, üzemanyag, ár, állapot, futásteljesítmény, képek
- Üzenet adatok: hirdetés, címzett, szöveg

**Kimenet:**
- Vezérlőpult statisztikák (közelgő időpontok, szervizben lévő autók, stb.)
- Gépjármű-, időpont-, hibajegy-, hirdetés- és üzenetlisták
- Admin naptárnézet, havi statisztikák
- E-mail értesítések (visszaigazolás, üdvözlő levél)
- Rendszerszintű értesítések

**Előfeltételek:**
- `0 ≤ gépjárművek száma`
- `0 ≤ időpontok száma`
- Felhasználó hitelesített és e-mail cím megerősítve
- Admin műveletek: `user.role = 'admin'`

**Utófeltételek:**
- Időpont-foglalás: nincs ütközés visszaigazolt időponttal
- Minden erőforrás (autó, hibajegy, üzenet) a tulajdonoshoz kötött
- Soft delete: törölt rekordok visszaállíthatók

## Környezet (fejlesztői)

| Elem | Verzió / leírás |
|---|---|
| Nyelv | PHP ≥ 8.2 |
| Keretrendszer | Laravel 12.x |
| Frontend | Blade, Bootstrap 5, Tailwind CSS 4, Vite |
| Adatbázis | MySQL 8.0+ / MariaDB 10.x+ |
| Build | Vite (vite.config.js) |
| Stílusok | SASS + Tailwind CSS |
| E-mail | SMTP (Laravel Mail) |
| Hitelesítés | Laravel Auth scaffolding + e-mail verifikáció |
| API tokenek | Laravel Sanctum (előkészítve, jelenleg nem használt) |
| Tesztelés | PHPUnit |

## Forráskód

A teljes fejlesztői anyag az `Autonex` nevű könyvtárban található. A fejlesztés során használt könyvtár-struktúra:

```
Autonex/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AppointmentController.php      – Felhasználói időpontkezelés
│   │   │   ├── CarController.php              – Gépjármű CRUD
│   │   │   ├── DashboardController.php        – Vezérlőpult (user + admin)
│   │   │   ├── IssueController.php            – Hibajegy CRUD
│   │   │   ├── SaleController.php             – Piactér kezelés
│   │   │   ├── MessageController.php          – Üzenetrendszer
│   │   │   ├── NotificationController.php     – Értesítések kezelése
│   │   │   ├── ProfileController.php          – Profilszerkesztés
│   │   │   ├── HomeController.php             – Főoldal
│   │   │   ├── EmailsController.php           – E-mail műveletek
│   │   │   ├── Admin/
│   │   │   │   ├── AppointmentManagementController.php  – Admin időpontkezelés
│   │   │   │   └── AdminNotificationController.php      – Admin értesítések
│   │   │   ├── Auth/                          – Laravel auth vezérlők
│   │   │   └── Traits/
│   │   │       └── AdminHelpers.php           – Admin segédfüggvények
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php            – Admin jogosultság-ellenőrzés
│   │   └── Requests/                          – Form request validációk
│   ├── Models/
│   │   ├── User.php                           – Felhasználó modell
│   │   ├── Car.php                            – Gépjármű modell
│   │   ├── Appointment.php                    – Időpont modell
│   │   ├── Issue.php                          – Hibajegy modell
│   │   ├── Sale.php                           – Hirdetés modell
│   │   ├── Message.php                        – Üzenet modell
│   │   ├── SaleImage.php                      – Hirdetéskép modell
│   │   ├── ServicePhoto.php                   – Szervizfotó modell
│   │   └── AdminNotification.php              – Értesítés modell
│   ├── Mail/
│   │   ├── WelcomeMail.php                    – Üdvözlő e-mail
│   │   └── AppointmentConfirmationMail.php    – Időpont-visszaigazolás
│   ├── Policies/
│   │   ├── AppointmentPolicy.php
│   │   ├── CarPolicy.php
│   │   ├── IssuePolicy.php
│   │   ├── MessagePolicy.php
│   │   └── SalePolicy.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── factories/                             – Modell factory-k teszteléshez
│   ├── migrations/                            – 25 db migrációs fájl
│   └── seeders/                               – Teszt adatfeltöltők
├── resources/
│   ├── views/                                 – Blade nézet sablonok
│   ├── css/                                   – Stíluslapok
│   ├── js/                                    – JavaScript fájlok
│   └── sass/                                  – SASS stíluslapok
├── routes/
│   ├── web.php                                – Webes útvonalak
│   ├── api.php                                – API útvonalak (jelenleg üres)
│   └── console.php                            – Artisan parancsok
├── config/                                    – Alkalmazás konfigurációk
├── public/                                    – Publikus fájlok (index.php, képek)
├── storage/                                   – Feltöltött fájlok, naplók, cache
├── tests/                                     – PHPUnit tesztek
├── composer.json                              – PHP függőségek
├── package.json                               – NPM függőségek
├── vite.config.js                             – Vite konfiguráció
├── phpunit.xml                                – Tesztkonfiguráció
└── deploy.sh                                  – Telepítő script
```

## Megoldás

### Adatbázis-séma

Az alkalmazás 12 táblát használ (a Laravel alapértelmezett tábláival együtt):

#### Táblák és oszlopok

**users**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| name | string | Felhasználó neve |
| email | string (unique) | E-mail cím |
| phone | string (nullable) | Telefonszám |
| role | string (default: 'user') | Szerepkör: `user` / `admin` |
| password | string | Titkosított jelszó |
| email_verified_at | timestamp (nullable) | E-mail hitelesítés ideje |
| welcome_email_sent_at | timestamp (nullable) | Üdvözlő e-mail küldési ideje |
| remember_token | string | Emlékeztetés token |
| created_at, updated_at | timestamps | Létrehozás / módosítás ideje |

**cars**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| user_id | bigint (FK → users) | Tulajdonos |
| make_model | string | Márka és modell |
| vin | string | Alvázszám |
| license_plate | string (nullable) | Rendszám |
| year | integer | Évjárat |
| deleted_at | timestamp (nullable) | Soft delete |

**appointments**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| user_id | bigint (FK → users, nullable) | Felhasználó |
| car_id | bigint (FK → cars, nullable) | Gépjármű |
| work_number | string (unique) | Munkalapszám (MNK-XXXXXX) |
| date | date | Időpont dátuma |
| time | time | Időpont ideje |
| description | text (nullable) | Leírás |
| service | string (nullable) | Szerviz típusa |
| status | enum | `pending` / `confirmed` / `in_progress` / `completed` / `cancelled` |
| service_stage | string (nullable) | Szerviz fázis |
| mechanic_name | string (nullable) | Szerelő neve |
| total_cost | decimal (nullable) | Összköltség |
| service_report | text (nullable) | Szervizjelentés |
| issues_found | text (nullable) | Talált hibák |
| critical_warning | boolean (default: false) | Kritikus figyelmeztetés |
| customer_name | string (nullable) | Ügyfélnév (admin) |
| customer_phone | string (nullable) | Ügyféltelefon (admin) |
| car_brand, car_model, car_year, car_engine, car_fuel_type | string (nullable) | Jármű adatok (admin) |
| deleted_at | timestamp (nullable) | Soft delete |

**issues**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| car_id | bigint (FK → cars) | Gépjármű |
| category | string | Hiba kategóriája |
| description | text | Leírás |
| urgency | enum | `low` / `medium` / `high` |
| deleted_at | timestamp (nullable) | Soft delete |

**sales**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| car_id | bigint (FK → cars, nullable) | Gépjármű |
| seller_id | bigint (FK → users) | Eladó |
| buyer_id | bigint (FK → users, nullable) | Vevő |
| vehicle_type | string (nullable) | Járműtípus |
| model | string (nullable) | Modell |
| body_type | string (nullable) | Karosszéria |
| engine_cc | integer (nullable) | Motor (cm³) |
| fuel_type | string (nullable) | Üzemanyag |
| price | decimal(10,2) | Ár |
| description | text (nullable) | Leírás |
| car_condition | string (nullable) | Állapot |
| mileage | integer (nullable) | Futásteljesítmény |
| documents_available | boolean (nullable) | Dokumentumok elérhetők |
| document_type | string (nullable) | Dokumentum típusa |
| technical_inspection | date (nullable) | Műszaki vizsga |
| is_active | boolean (default: true) | Aktív hirdetés |
| deleted_at | timestamp (nullable) | Soft delete |

**messages**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| sale_id | bigint (FK → sales) | Hirdetés |
| sender_id | bigint (FK → users) | Küldő |
| receiver_id | bigint (FK → users) | Címzett |
| message | text | Üzenet szövege |
| is_read | boolean (default: false) | Olvasott |
| deleted_at | timestamp (nullable) | Soft delete |

**sale_images**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| sale_id | bigint (FK → sales) | Hirdetés |
| path | string | Kép elérési útja |
| sort_order | integer (default: 0) | Rendezési sorrend |

**service_photos**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| appointment_id | bigint (FK → appointments) | Időpont |
| title | string (nullable) | Cím |
| path | string | Fotó elérési útja |

**admin_notifications**
| Oszlop | Típus | Leírás |
|---|---|---|
| id | bigint (PK) | Elsődleges kulcs |
| user_id | bigint (FK → users, nullable) | Címzett (NULL = broadcast) |
| title | string | Értesítés címe |
| message | text | Értesítés szövege |
| is_read | boolean (default: false) | Olvasott |

### Programfelépítés

Az alkalmazás az MVC (Model-View-Controller) architektúrát követi:

```
┌────────────────────────────────────────────────────────┐
│                      Böngésző                          │
└──────────────┬─────────────────────────┬───────────────┘
               │ HTTP kérés              │ HTTP válasz
               ▼                         │
┌──────────────────────────────────────────────────────┐
│                   routes/web.php                     │
│              (Útvonalak és middleware-ek)             │
└──────────────┬───────────────────────────────────────┘
               │
               ▼
┌──────────────────────────────┐   ┌──────────────────┐
│       Middleware              │   │   Policies       │
│  ├─ auth                     │   │  ├─ Appointment   │
│  ├─ verified                 │   │  ├─ Car           │
│  └─ AdminMiddleware          │   │  ├─ Issue         │
└──────────────┬───────────────┘   │  ├─ Message       │
               │                   │  └─ Sale          │
               ▼                   └──────────┬────────┘
┌──────────────────────────────────────────────┤
│              Controllers                     │
│  ├─ AppointmentController                    │
│  ├─ CarController                            │
│  ├─ DashboardController                      │
│  ├─ IssueController                          │
│  ├─ SaleController                           │
│  ├─ MessageController                        │
│  ├─ NotificationController                   │
│  ├─ ProfileController                        │
│  ├─ Admin/AppointmentManagementController    │
│  └─ Admin/AdminNotificationController        │
└────────┬──────────────────────┬──────────────┘
         │                      │
         ▼                      ▼
┌─────────────────┐   ┌──────────────────────┐
│     Models      │   │    Views (Blade)     │
│  ├─ User        │   │  ├─ layouts/         │
│  ├─ Car         │   │  ├─ dashboard/       │
│  ├─ Appointment │   │  ├─ cars/            │
│  ├─ Issue       │   │  ├─ appointments/    │
│  ├─ Sale        │   │  ├─ issues/          │
│  ├─ Message     │   │  ├─ sales/           │
│  ├─ SaleImage   │   │  ├─ messages/        │
│  ├─ ServicePhoto│   │  ├─ admin/           │
│  └─ AdminNotif. │   │  ├─ emails/          │
└────────┬────────┘   │  └─ auth/            │
         │            └──────────────────────┘
         ▼
┌─────────────────┐
│    Adatbázis    │
│    (MySQL)      │
└─────────────────┘
```

### Útvonalak (Routes)

#### Publikus útvonalak
| Metódus | URI | Leírás |
|---|---|---|
| GET | `/` | Nyitóoldal |
| GET/POST | `/login` | Bejelentkezés |
| GET/POST | `/register` | Regisztráció |
| GET | `/email/verify/{id}/{hash}` | E-mail hitelesítés |

#### Hitelesített felhasználói útvonalak (auth + verified)
| Metódus | URI | Leírás |
|---|---|---|
| GET | `/dashboard` | Felhasználói vezérlőpult |
| GET/PUT | `/profile` | Profil szerkesztése |
| CRUD | `/cars` | Gépjármű kezelés (resource) |
| GET/POST | `/appointments` | Időpont-foglalás |
| GET | `/appointments/{appointment}` | Időpont részletei |
| PATCH | `/appointments/{appointment}/cancel` | Időpont lemondása |
| PATCH | `/appointments/{appointment}/reschedule` | Átütemezés |
| CRUD | `/issues` | Hibajegy kezelés (resource) |
| GET | `/sales` | Piactér böngészése |
| GET | `/sales/{sale}` | Hirdetés részletei |
| GET/POST | `/messages` | Üzenetek |
| GET | `/messages/conversation/{sale}` | Beszélgetés megtekintése |
| PATCH | `/notifications/{notification}/read` | Értesítés olvasottá jelölése |
| PATCH | `/notifications/read-all` | Összes értesítés olvasottá jelölése |

#### Admin útvonalak (auth + verified + admin)
| Metódus | URI | Leírás |
|---|---|---|
| GET | `/admin-dashboard` | Admin vezérlőpult |
| CRUD | `/admin/appointments` | Időpont kezelés (resource) |
| PATCH | `/admin/appointments/{id}/update-status` | Gyors státuszváltás |
| DELETE | `/admin/service-photos/{photo}` | Szervizfotó törlése |
| CRUD | `/sales` (create/store/edit/update/destroy) | Hirdetés kezelés |
| DELETE | `/sales/{sale}/images/{image}` | Hirdetéskép törlése |
| CRUD | `/admin/notifications` | Értesítés kezelés |

### Modellek és kapcsolatok

```
User ──< Car ──< Issue
  │        │
  │        └──< Appointment ──< ServicePhoto
  │        │
  │        └──< Sale ──< SaleImage
  │               │
  │               └──< Message
  │
  └──< AdminNotification

Jelölés: ──< = egy-a-többhöz (hasMany / belongsTo)
```

**Részletes kapcsolatok:**

| Modell | Kapcsolat | Célmodell | Típus |
|---|---|---|---|
| User | cars | Car | hasMany |
| User | appointments | Appointment | hasMany |
| User | sentMessages | Message | hasMany (sender_id) |
| User | receivedMessages | Message | hasMany (receiver_id) |
| User | sales | Sale | hasMany (seller_id) |
| Car | user | User | belongsTo |
| Car | appointments | Appointment | hasMany |
| Car | issues | Issue | hasMany |
| Car | sales | Sale | hasMany |
| Appointment | user | User | belongsTo |
| Appointment | car | Car | belongsTo |
| Appointment | servicePhotos | ServicePhoto | hasMany |
| Issue | car | Car | belongsTo |
| Sale | car | Car | belongsTo |
| Sale | seller | User | belongsTo (seller_id) |
| Sale | buyer | User | belongsTo (buyer_id) |
| Sale | images | SaleImage | hasMany |
| Sale | messages | Message | hasMany |
| Message | sale | Sale | belongsTo |
| Message | sender | User | belongsTo (sender_id) |
| Message | receiver | User | belongsTo (receiver_id) |
| SaleImage | sale | Sale | belongsTo |
| ServicePhoto | appointment | Appointment | belongsTo |
| AdminNotification | user | User | belongsTo |

### Vezérlők (Controllers)

#### AppointmentController
- `index()` – felhasználó saját időpontjai (admin: összes)
- `create()` – foglalási űrlap
- `store()` – létrehozás ütközésvizsgálattal + visszaigazoló e-mail
- `show()` – időpont részletei szervizfotókkal
- `cancel()` – lemondás + értesítés
- `reschedule()` – átütemezés validálással

#### CarController
- Teljes CRUD (index, create, store, show, edit, update, destroy)
- Soft delete használata
- Policy-alapú jogosultság-ellenőrzés

#### DashboardController
- `user()` – felhasználói statisztikák, értesítések, legutóbbi időpontok
- `admin()` – naptár nézet, napi időpontok, elkészült járművek, havi statisztika (6 hónap)

#### SaleController
- `index()` – aktív hirdetések (10/oldal) szűrőkkel
- `store()` – többképes feltöltés sort_order-rel
- `destroyImage()` – kép törlése a storage-ból

#### MessageController
- `conversation()` – üzenetfolyam megtekintése, olvasatlanok automatikus olvasottá jelölése
- Policy-alapú jogosultság (küldő / címzett / admin)

#### Admin/AppointmentManagementController
- Szervizfolyamat-követés (fázis, szerelő, költség, jelentés, szervizfotók)
- 20 előre megadott szerelőnév poolból választható
- Munkalapszám automatikus generálás (`MNK-XXXXXX`)
- Készre jelölés + ügyfélértesítés

#### Admin/AdminNotificationController
- Célzott és broadcast értesítések küldése
- Szűrhető lista (név, rendszám, VIN, dátum)

### Jogosultságkezelés

**Middleware:**
- `auth` – bejelentkezett felhasználó szükséges
- `verified` – e-mail hitelesítés szükséges
- `AdminMiddleware` – `user.isAdmin()` ellenőrzés, hamisnál átirányítás `/`-ra

**Policy-k:**
| Policy | view | create | update | delete | forceDelete |
|---|---|---|---|---|---|
| AppointmentPolicy | tulajdonos / admin | mindenki | tulajdonos / admin | tulajdonos / admin | admin |
| CarPolicy | tulajdonos / admin | mindenki | tulajdonos / admin | tulajdonos / admin | admin |
| IssuePolicy | autó tulajdonosa / admin | mindenki | autó tulajdonosa / admin | autó tulajdonosa / admin | admin |
| MessagePolicy | küldő / címzett / admin | mindenki | küldő | küldő / admin | admin |
| SalePolicy | mindenki | admin | eladó / admin | eladó / admin | admin |

### E-mail rendszer

**WelcomeMail** – Üdvözlő e-mail regisztrációkor
- Tárgy: *„Üdvözlünk az AutoNex-ben!"*
- Adat: felhasználónév, alkalmazás URL
- Sablon: `emails.WelcomeMail`

**AppointmentConfirmationMail** – Időpont-visszaigazolás
- Tárgy: *„Időpont visszaigazolás – AutoNex"*
- Adat: felhasználónév, dátum (Y.m.d), idő, szerviz típus, autó adatok, munkalapszám
- Sablon: `emails.AppointmentConfirmationMail`
- Hibakezelés: sikertelen küldés naplózása, az alkalmazás nem áll le

## Tesztelés

### Teszt-adatbázis (Seeders)

A `php artisan migrate --seed` parancs az alábbi tesztadatokat tölti fel:

| Seeder | Leírás |
|---|---|
| UserSeeder | 1 admin (admin@admin.com / admin123) + 10 véletlenszerű felhasználó |
| CarSeeder | Véletlenszerű gépjárművek (BMW 320d, Audi A4, Mercedes C200, VW Golf, Toyota Corolla) |
| AppointmentSeeder | Véletlenszerű időpontok különböző státuszokkal |
| IssueSeeder | Véletlenszerű hibajegyek |
| SaleSeeder | Véletlenszerű hirdetések |
| MessageSeeder | Véletlenszerű üzenetek |

### Érvényes tesztesetek

| # | Leírás | Elvárt eredmény |
|---|---|---|
| 1 | Regisztráció helyes adatokkal | Fiók létrejön, üdvözlő e-mail érkezik |
| 2 | Bejelentkezés helyes e-mail/jelszó | Átirányítás a vezérlőpultra |
| 3 | Gépjármű hozzáadása | Autó megjelenik a listában |
| 4 | Időpont-foglalás szabad időpontra | Időpont létrejön, munkalapszám generálódik, visszaigazoló e-mail |
| 5 | Időpont lemondása | Státusz: `cancelled`, értesítés létrejön |
| 6 | Hibajegy létrehozása | Hibajegy megjelenik az autó hibái között |
| 7 | Hirdetés böngészése | Aktív hirdetések oldalankénti megjelenítése |
| 8 | Üzenet küldése hirdetéshez | Üzenet megjelenik a beszélgetésben |
| 9 | Admin: időpont státusz módosítás | Státusz frissül (confirmed / cancelled / completed) |
| 10 | Admin: értesítés küldése | Értesítés megjelenik a felhasználó vezérlőpultján |

### Érvénytelen tesztesetek

| # | Leírás | Elvárt eredmény |
|---|---|---|
| 1 | Regisztráció létező e-mail címmel | Hibaüzenet: az e-mail már foglalt |
| 2 | Bejelentkezés hibás jelszóval | Hibaüzenet: hibás bejelentkezési adatok |
| 3 | Időpont-foglalás ütköző időpontra | Hibaüzenet: az időpont már foglalt |
| 4 | Más felhasználó autójának szerkesztése | 403 Forbidden |
| 5 | Nem admin felhasználó admin oldalra lép | Átirányítás a főoldalra |
| 6 | Gépjármű mentése hiányzó mezőkkel | Validációs hibaüzenet |
| 7 | E-mail hitelesítés nélkül belépés | Átirányítás a hitelesítési oldalra |
| 8 | Üzenet szerkesztése nem saját üzenetnél | 403 Forbidden |

### PHPUnit tesztek

A tesztek a `tests/` könyvtárban találhatók:
```
tests/
├── TestCase.php        – Alap tesztosztály
├── Feature/            – Funkcionális tesztek
└── Unit/               – Egységtesztek
```

Futtatás:
```
php artisan test
```

## Fejlesztési lehetőségek

- **Valós idejű értesítések:** WebSocket (Laravel Echo + Pusher) integráció a push értesítésekhez.
- **API réteg:** A Sanctum tokenkezelés már előkészítve van – REST API kialakítása mobilalkalmazás számára.
- **Keresés és szűrés bővítése:** Elasticsearch vagy Laravel Scout integrálása a piactér keresőhöz.
- **Fizetési integráció:** Online fizetés a szerviz költségekhez (Stripe / SimplePay).
- **PDF generálás:** Munkalapok és számlák PDF exportja.
- **Többnyelvű felület:** Laravel lokalizáció kihasználása magyar/angol nyelvi támogatáshoz.
- **Naptár-szinkronizáció:** Google Calendar / Outlook integráció az időpontokhoz.
- **Részletesebb statisztikák:** Grafikonos elemzések a szervizek hatékonyságáról, bevételekről.
- **Képoptimalizálás:** Feltöltött képek automatikus tömörítése és átméretezése.
- **Felhasználói vélemények:** Szerviz-értékelési rendszer bevezetése.
