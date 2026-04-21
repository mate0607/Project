# Autonex

## MŰSZAKI DOKUMENTÁCIÓ

Patkos Dominik, Molnár Attila, Fodor Tamás

Verzió: 1.0
Dátum: 2026-04-20

---

## Tartalomjegyzék

1. Projekt áttekintés
2. Backend architektúra
3. Backend autentikáció
4. Backend fiókkezelés és e-mail folyamatok
5. Backend adatmodellek
6. Backend web + API referencia
7. Backend hibakezelés
8. Backend tranzakciós/fizetési folyamatok és piactéri állapotgép
9. Backend keresés, szűrés és tömegés műveletek
10. Backend munkafolyamatok
11. Backend fájltárolás
12. Backend üzenetsors, feladatok és ütemezés
13. Jogosultságok és hozzáférési matrix
14. Frontend architektúra
15. Frontend autentikáció és munkamenet
16. Frontend komponens- és nézettár
17. Frontend útvonalkezelés és navigáció
18. Frontend állapotkezelés és integráció
19. Frontend betöltőrendszer és UX visszajelzések
20. Frontend felhasználói folyamatok
21. Frontend stílusrendszer
22. Frontend űrlapok és validáció
23. Frontend hibakezelés és hibaterületek
24. Környezet és deployment
25. Helyi fejlesztési környezet
26. Hibaelhárítás
27. Összefoglalás
28. Melléklet A - Végpontmatrix
29. Melléklet B - adatmodell szótár
30. Melléklet C - Üzemeltetési ellenőrzőlisták

---

## 1. Projekt áttekintés

### 1.1 Célkitűzés
Az Autonex egy integrált autós szerviz- és piactéri rendszer. A platform két kulcsoproblémát kezel egyetlen rendszerben:

- jármű- és szervizfolyamatok adminisztrációja,
- használt jármű hirdetések kezelése többképés piactéri funkcióval.

A termék értéke az, hogy ugyanazon felhasználói fiókon belül egyesíti:

- a saját autó nyilvántartást,
- a hibajegykezelést,
- az időpontfoglalást,
- a szerviz állapotkovétést,
- a hirdetések kezelését,
- a valós idejű üzenetváltás jellégű kommunikációt.

### 1.2 Rendszerhátár
Az alkalmazás Laravel 12 alapú monolit webalkalmazás, Blade nézetekkel. A backend és frontend egy kódbázisban fut, külön SPA frontend jelenlegi nincs. A kitértség két rétegen történik:

- web route-ok (főbb funkcionalitás),
- egyszerű API route csoport a jármű tipízaló adatokhoz.

### 1.3 Fő modulok

- Felhasználó- és szerepkörkezelés (`admin`, normal user).
- Autó kezelés (CRUD, tulajdonosi kötés).
- Szerviz időpont kezelés (felhasználói + admin workflow).
- Hibajegy kezelés (issue modul).
- Piactér/hirdets kezelés (sales + sale képek).
- Üzenetzkelö (autóhoz és hirdetéshez kapcsolt beszélgetés).
- Értesítsi modul (admin notification + olvasatlan állapotok).
- Dashboardok (admin és user nézetek).

### 1.4 Nem-célok
A jelenlegi implementáció nem általános e-kereskedelmi rendszer:

- nincs teljes kártyaprocesszor integrált checkout,
- nincs külön mikrőszerviz architektúra,
- nincs külön mobil API verziókezelés.

### 1.5 Repositórium szerkezet

- `app/` domain logika: kontrollerek, modellek, policy-k, middleware.
- `routes/` web, api, console route definíciók.
- `resources/views/` blade oldallak és résznézetek.
- `database/` migrációk, factory-k, seederek.
- `config/` alkalmazás- és domain konfiguráció (`vehicles.php`).
- `public/` web root + statikus allományok.
- `storage/` futási állapot, log, feltöltött tartalom.

### 1.6 Fő domain entitások

- User
- Car
- Appointment
- Issue
- Sale
- SaleImage
- Message
- AdminNotification
- ServicePhoto

### 1.7 Üzleti képességek egy mondatban
A felhasználó rögzít egy autót, bejelent hibákat, időpontot foglal, követi a szerviz állapottat, közben hirdetést adhat fel, és az adminnal vagy más érintettel beágyazott beszélgetésben tud egyeztetni.

---

## 2. Backend architektúra

### 2.1 Technológiai stack

- PHP 8.2+
- Laravel 12
- MySQL/MariaDB (Eloquent ORM)
- Blade templating
- Laravel UI auth scaffold
- Vite build pipeline
- Bootstrap 5 + Sass
- Queue: database driver (dev scriptben queue listener)

### 2.2 Architektúrális modell
A projekt klasszikus MVC-rétegekből épül fel:

1. Route réteg (`routes/web.php`, `routes/api.php`)
2. Middleware réteg (auth, admin alias)
3. Controller réteg (Üzleti folyamatok koordinációja)
4. Request validáció réteg (FormRequest osztályok)
5. Model réteg (Eloquent relációk + castok + soft delete)
6. View réteg (Blade nézetek)

### 2.3 Kulcs Architektúrális döntések

- Admin jogosultság custom middleware alapon (`admin` alias).
- Finomabb jogosultságok policy osztályokkal.
- Resource route-okra épített konvenciónális CRUD.
- hirdetési képek külön táblában (`sale_images`) rendezett `sort_order` mezővel.
- Soft delete több fő entitásnál (`cars`, `appointments`, `sales`, `messages`).

### 2.4 Integrációs pontok

- E-mail küldés időpont visszaigaz oláshoz.
- Kliens oldali aszinkrón üzenetfrissítés JSON endpointokon.
- Konfigurált, de nem túlbonyolított API réteg jármű adatokhoz.

### 2.5 Monolit előnyei

- Egyszerű deployment.
- Egyertélmű tranzakciós határok.
- egyutas auth/session modell.
- Kisebb konzisztencia-kár, mint szétszdett mikrószolgáltatóknál.

### 2.6 Monolit korlátök

- Frontend és backend release nem füeggetlen.
- Nagyobb kódbázisnál erős modularizáció szukséges.
- Horizontálskala MVC monolitnál korlátozöttabb, mint külön API + frontend architektúranál.

---

## 3. Backend autentikáció

### 3.1 Alapmodell
Az alkalmazás alapvetően session/cookie alapú web autentikációt használ (`Auth::routes()`), nem token-centric API authot.

### 3.2 Route védelem
A web route-ok a következkő mintát követik:

- nyitott route: `/`
- auth protected: dashboard, profil, autók, időpontok, issue-k, üzenetek
- admin protected: admin dashboard, admin appointment management, admin notification management, hirdets módosítasi route-ok

### 3.3 Admin ellenőrzés
Az admin hozzáférést a custom middleware kezeli:

- belépési feltétel: bejelentkezett user + `isAdmin() == true`
- sikertelen esetben redirect `/` irányba

### 3.4 User modell szerepkör
A User modell `isAdmin()` helper métoddaladja vissza a szerepkört (`role === 'admin'`).

### 3.5 Security megjegyzés
Az admin middleware jelenleg redirectet ad nem jogosult esetben. API-jellégű végpontokknál hosszabb távon ajánlott lenne status code + JSON policy (403) fenntartása a következeteség miatt.

---

## 4. Backend fiókkezelés és e-mail folyamatok

### 4.1 Fiókfunkciók

- regisztráció / login / logout (Laravel UI auth)
- profil szerkesztés (`ProfileController`)
- felhasználói adatok kiegészítése (pl. telefon)

### 4.2 E-mail folyamat
Az időpont foglalas utan a rendszer visszaigaz oló e-mailt probal küldeni (`AppointmentConfirmationMail`).

Folyamat:

1. Appointment rekord letrejon.
2. `Mail::to(...)->send(...)` meghivodik.
3. Sikertelen küldés eseten log bejegyzés keszul, a foglalas nem vesz el.

### 4.3 Robustág
A mail küldés hibaturo modon mukodik:

- ha a mail küldés hibazik, a foglalási tranzakció már megtortent,
- a rendszer logban rögzíti a hibat (`APPOINTMENT_MAIL`).

### 4.4 User experience hatasa
A felhasználó nem vesziti el a foglalást akkor sem, ha eppen e-mail szolgaltato oldalrol hiba van.

---

## 5. Backend adatmodellek

### 5.1 User
Kulcs mező-k:

- name
- email
- phone
- password
- role
- welcome_email_sent_at

Relaciok:

- hasMany cars
- hasMany appointments
- hasMany sales (seller)
- hasMany sentMessages
- hasMany receivedMessages

### 5.2 Car
Kulcs mező-k:

- user_id
- make_model
- vin
- license_plate
- year

Relaciok:

- belongsTo user
- hasMany appointments
- hasMany issues
- hasMany sales
- hasMany messages

### 5.3 Appointment
Kulcs mező-k:

- user_id
- car_id
- date
- time
- status (`pending`, `confirmed`, `in_progress`, `completed`, `cancelled` jellégű workflow)
- service, service_stage
- mechanic_name
- total_cost
- service_report
- issues_found
- critical_warning
- work_number

Relaciok:

- belongsTo user
- belongsTo car
- hasMany servicePhotos

Megjegyzés:

- `booted()` creating hook automatikusan generál `work_number` értéket.

### 5.4 Issue
A hibajegy modul a jármuhoz kötött hibaleirásokat kezeli (CRUD), tulajdonosi/policy v\u00e9delmi logikval.

### 5.5 Sale
Kulcs mező-k:

- car_id (nullable-ra migralt)
- seller_id
- buyer_id
- vehicle_type
- brand
- model
- body_type
- engine_cc
- fuel_type
- documents_available
- document_type
- technical_inspection
- price
- description
- car_condition
- mileage
- is_active

Relaciók:

- belongsTo car
- belongsTo seller
- belongsTo buyer
- hasMany images
- hasMany messages

### 5.6 SaleImage
A hirdetési kép metadata külön tablaban tarolt:

- sale_id
- path
- sort_order

### 5.7 Message
Kulcs mező-k:

- car_id (optional kontextus)
- sale_id (optional kontextus)
- sender_id
- receiver_id
- message
- is_read

Relaciók:

- belongsTo sender
- belongsTo receiver
- belongsTo car
- belongsTo sale

### 5.8 AdminNotification
Felhasználóhoz kötött vagy globális értesítés:

- user_id (nullable -> globalis)
- title
- message
- is_read

### 5.9 ServicePhoto
Szervizfolyamat dokumentálása képés bizonyítékokkal:

- appointment_id
- title
- path

### 5.10 Soft delete stratégia
A soft delete lehetővé teszi:

- audit jellégű visszakövethetőséget,
- véletlen törlések utólagos kezelhetőséget,
- kapcsolt entitások konszolidált megőrzését.

---

## 6. Backend web + API referencia

### 6.1 Web route szerkezet
A rendszer gerincet a `web.php` adja. A route-ok 3 nagy blokkban rendezettek:

1. publikus/legacy route-ok,
2. admin-only route csoport,
3. auth user route csoport.

### 6.2 fő web endpoint csoportok

#### Publikus

- `GET /` -> nyitóoldal
- Laravel auth oldalak (`/login`, `/register`, stb.)
- `GET /home` -> legacy home

#### Auth user

- `GET /dashboard` (user dashboard)
- profile edit/update
- cars resource CRUD
- appointments (index/create/store/show + cancel/reschedule)
- sales (index/show)
- issues resource CRUD
- messaging endpointok autohoz és hirdetéshez
- notification read/read-all endpointok

#### Admin

- `GET /admin-dashboard`
- sales create/store/edit/update/destroy
- sale image törlés
- admin appointments teljes menedzment
- admin notifications teljes menedzment
- admin message center

### 6.3 API endpoint csoport
`/api/vehicles/*`:

- `types`
- `brands`
- `models`
- `body-types`

Ez az API csoport jelenlegi a frontend űrlapok dinamikus választóit tamogatja.

### 6.4 Resource + custom endpoint minta
A projekt egyensulyoz:

- resource route (konvencionalis CRUD)
- custom route (uzleti folyamat endpointok: cancel, reschedule, update-status)

### 6.5 API szerzödési forma
A web route-ok vegyesen adnak vissza:

- Blade view-t,
- JSON payloadot (fokent messaging/notification async endpointok).

### 6.6 Konszolidacios javaslat
Hosszu tavon erdemés lehet endpoint szerződésekét explicit retegbe szervezni:

- tiszta web response profile,
- tiszta JSON API profile,
- uniform error envelope.

---

## 7. Backend hibakezelés

### 7.1 Validációs hibakezelés
A rendszer több helyen request validációval dolgozik:

- FormRequest (`StoreSaleRequest`, `UpdateSaleRequest`, stb.)
- inline controller validáció (`$request->validate(...)`)

### 7.2 Üzleti ütközésketzelés
Időpontnal külön konfliktusvizsgalat fut:

- ugyanarra a datum+idő slotra ne legyen két `confirmed` foglalas.

### 7.3 jogosultsági hibak
Tipikus hibaforrasok:

- idegen autohoz tartozas
- inAktív hirdetéshez kuldes
- nem cimzett notification olvasas

### 7.4 Messaging védelmi esetek
A message endpointok explicit ellenőrzik:

- tulajdonos-e,
- admin-e,
- hirdetés aktiv-e,
- jogosult-e a beszelgetesre.

### 7.5 Fájlmuõveleti hibak
hirdetési/szerviz kép törlésnél a rendszer storage oldali torles-t is vegez; uzemeltetesi oldalon ajánlott rendszerés orphan-file ellenőrzes.

### 7.6 Megfigyelhetoőség
Mail kuldesnel logolt exception metadata segiti a hibafeltarast.

---

## 8. Backend tranzakciós/fizetési folyamatok és piactéri állapotgép

### 8.1 Jelenlegi állapot
Az Autonex piactéri modul jelenlegi nem Stripe jellégű payment checkout pipeline-ra épul, hanem hirdetés állapot- és tulajdonosi workflow-ra.

### 8.2 Sale állapotlogika
Kulcs állapotjelzo: `is_active`.

- `true`: hirdetés aktiv, nyilvanos kommunikacio engedett.
- `false`: hirdetés zart/inaktiv, kulso user számára kommunikacio korlatozott.

### 8.3 Tranzakció helyett folyamatkontroll
A hangsuly a k\u00f6vetkezokon van:

- ki hozhat letre hirdetést,
- ki modosithet,
- ki torolhet,
- ki uzenhet adott hirdetésrol.

### 8.4 Buyer/seller modellezese
A modell tamogatja a buyer_id mezőt is, ami jovobeli teljes adasveteli folyamat alapja lehet.

### 8.5 Kiterjesztés i pontok
k\u00f6vetkezo iteracióban integráció valósíhato meg:

- foglalasi állapot,
- fizetesi állapot,
- tranzakcios naplo,
- birtokbaadasi workflow.

---

## 9. Backend keresés, szűrés és tömegés műveletek

### 9.1 sales listázás
A piacter listázás lapozott (`paginate(10)`), és betölti a kapcsolt entitásokat (`car`, `buyer`, `seller`, `images`).

### 9.2 Admin appointment szűrok
Az admin időpontlista több mezőre szűr:

- ugyfel nev,
- auto marka/modell,
- rendszam,
- datum.

### 9.3 Vehicle konfiguracio mint domain dictionary
A `config/vehicles.php` több szaz marka/modell elemet tartalmaz. Ez gyakorlatilag egy domain szotar:

- vehicle_type -> brand -> model
- body_type listak vehicle_type szerint.

### 9.4 Tömegés jellégű műveletek
A kódban explicit bulk endpoint nincs minden modulban, de seederek szintjén jelentkezik tömegés adatbetöltés:

- auto + hirdetés + kép csomagolt eloallitas.

### 9.5 keresési fejlesztési lehetőségek

- fulltext index a hirdetés leirasokra,
- facetszeru szűrés (ar, marka, uzemanyag, állapot),
- elore indexelt dashboard mutatok.

---

## 10. Backend munkafolyamatok

### 10.1 User auto felvetele workflow

1. user belep
2. `cars.create` oldalon adatrögzites
3. validáció
4. `cars.store`
5. user sajat listaban megjelenik

### 10.2 User időpontfoglalás workflow

1. user saját auto valaszt
2. datum+idő megadas
3. konfliktusellenőrzes
4. appointment `pending` statusszal letrejon
5. visszaigaz oló e-mail kiserlet

### 10.3 User időpont atuőtemezés/lemondas

- csak `pending/confirmed` állapotban engedett,
- status és datum/idő frissites,
- admin notification letrehozas.

### 10.4 Admin időpontkezelés workflow

1. admin listaz szűrőkkel
2. reszletek + szerkesztes
3. status update (`confirmed/cancelled/completed`)
4. szerviz stage kezelés
5. service photo feltoltes/torles
6. `completed + ready` eseten user értesítéss

### 10.5 sales CRUD workflow

- admin letrehoz hirdetést,
- több képet tolthet fel,
- utolag képenkent torolhet,
- policy alapú update/delete.

### 10.6 Üzenet workflow auto kontextusban

- user/admin kuld üzenetet,
- receiver feloldas automatikusan megtortenik,
- read/unread állapot endpointokkal frissul,
- admin oldalon osszegzett unread badge.

### 10.7 Üzenet workflow hirdets kontextusban

- admin, seller, buyer szerepkor külön agon,
- inAktív hirdetésnel korlatozott kuldes,
- értesítés automatikus generalasa.

---

## 11. Backend fájltárolás

### 11.1 Storage modell
A rendszer a Laravel `public` diskét hasznalja:

- hirdetés képek: `sales/...`
- szerviz fotok: `service-photos/...`

### 11.2 Szinikronizacio
A publikus kiszolgalashoz kotelezo:

`php artisan storage:link`

### 11.3 Képtárolás és metadata
Maga a binaris tartalom a storage-ban van, az adatbazisban metadata:

- relatv path
- rendezés (`sort_order`)
- tulajdonosi kotés (`sale_id`, `appointment_id`)

### 11.4 Seeder képforrás
A hirdetés seed `képek` mappabol olvas prefix alapú egyeztetessel, majd a `public` diskre masol.

### 11.5 Típusok
Aktív seed logika avif képekre optimalizált, de request validáció oldalon jpeg/png/jpg/webp elfogadott.

### 11.6 Fájlrendszer köckazátok

- torolt DB rekord utan ott maradt f\u00e1jlok,
- manualis storage torlés utan DB-ben maradt path,
- nagy mennyisegu kép eseten tarhely menedzsment igeny.

---

## 12. Backend üzenetsorok, feladatok és ütemezes

### 12.1 Queue
A composer dev script futtat `queue:listen` folyamatot:

- `--tries=1`
- `--timeout=0`

Ez egyszeru lokalis hibaturo modot ad, de produkcios worker manager (supervisor/systemd) javasolt.

### 12.2 Scheduler
külön custom scheduler parancs jelenleg nincs a kodban olyan mértékben, mint egy reservation expiry rendszerben, de a Laravel scheduler infrastruktura rendelkezésre all.

### 12.3 Log stream
`php artisan pail` benne van a dev orchestration scriptben, ez gyors hibakeresést ad helyi fejlesztéskor.

### 12.4 Javasolt uzemeltetes

- queue worker process monitorozasa,
- failed_jobs periodikus kezelése,
- log retention policy bevezetlese.

---

## 13. Jogosultságok és hozzáférési matrix

### 13.1 rétegek

1. Route middleware (`auth`, `admin`)
2. Policy osztalyok (`CarPolicy`, `SalePolicy`, `AppointmentPolicy`, `MessagePolicy`, `IssuePolicy`)
3. Controller szintu egyedi guardok

### 13.2 Role matrix osszefoglalas

- Admin:
  - teljes admin dashboard,
  - appointment management,
  - sales management,
  - globalis message center,
  - notification management.

- bejelentkezett user:
  - sajat profile,
  - sajat auto CRUD,
  - sajat időpontok,
  - issue-k,
  - sales listing megtekintes,
  - üzenetküldés a feltételek szerint.

- Vendeg:
  - nyitóoldal,
  - auth oldalak.

### 13.3 Policy minták

- `SalePolicy::create` -> admin-only.
- `SalePolicy::update/delete` -> admin vagy seller.
- `CarPolicy::update/delete` -> admin vagy tulajdonos.
- `MessagePolicy::view` -> sender/receiver/admin.

### 13.4 Defense in depth
A kodban több ponton redundans vedelem van:

- policy,
- controller feltételek,
- route middleware.

Ez csokkenti a jogosulatlan hozzaferés kockázatat route-level hibakonfiguracio eseten is.

---

## 14. Frontend architektúra

### 14.1 Altalanos kép
A frontend server-side renderelt Blade rendszer, amelyet kis mértéku JS egeszit ki aszinkron üzenetfrissiteshez és dinamikus interakciokhoz.

### 14.2 Technologiak

- Blade templating
- Bootstrap 5
- Sass
- Vite
- minimalis `resources/js/app.js`

### 14.3 Oldalszerkezet
főbb nezetmappak:

- `layouts/`
- `dashboard/`
- `cars/`
- `appointments/`
- `issues/`
- `sales/`
- `messages/`
- `admin/`

### 14.4 Layout komponenselv
A közös oldalfej/menuszerkezet centralizalt layoutban van, ahol AppServiceProvider view composer injektalja a szükségés értesítéssi adatokat.

### 14.5 Frontend retegzes

- teljes oldalas navigation (klasszikus web app)
- endpoint szintu AJAX a chat/notification helyzetekben
- progressziv enhancement megkozelites

---

## 15. Frontend autentikáció és munkamenet

### 15.1 Session modell
A frontend auth állapota klasszikus Laravel session cookie alapú.

### 15.2 Auth UX folyamat

1. user login oldalon hitelesit
2. session letrejon
3. auth middleware vedi a route-okat
4. kijelentkezeskor session ervenytelenedik

### 15.3 Navigacios elkülönites

- admin dashboard külön endpoint,
- user dashboard külön endpoint,
- route-level auth guard.

### 15.4 Authorization feedback
Nem admin user admin route-ra lepve visszairanyitast kap gyokerre.

---

## 16. Frontend komponens- és nezetkonyvtar

### 16.1 Nezet-tipusok

- listazo oldalak (`index`)
- letrehozo oldalak (`create`)
- szerkeszto oldalak (`edit`)
- reszletoldalak (`show`)

### 16.2 Modulonkénti sablonok

- sales: `index/create/edit/show`
- appointments: user oldali flow oldalak
- admin appointments: külön mappa, külön UX
- messages: admin index + conversation

### 16.3 Ujrafelhasznalhatosag
A Blade resznézetekkel és közös layouttal a tipikus navigacios és visszajelzesi pattern ujrafelhasznalhato.

---

## 17. Frontend utvonalkezelés és navigacio

### 17.1 Navigacios topologia

- nyitóoldal -> login/register
- login utan role-fuggo dashboard
- dashboardról tematikus modulok

### 17.2 Legacy route tamogatas
`/home` route fenntartott kompatibilitasi celbol az auth scaffolding alap redirect miatt.

### 17.3 Contextual nav
A felhasználó a jármu, időpont, issue, sales modulok kozott válthat; admin oldalon külön menupontok adnak workflow központú navigációt.

---

## 18. Frontend állapotkezelés és integracio

### 18.1 SSR állapotforras
Az állapot tobbnyire szerverrol renderelt adat: Blade template valtozok.

### 18.2 AJAX állapot
Ahol valós idejű erzet kell:

- üzenetek olvasasa/kuldese,
- olvasatlan darabszam frissitese,
- notification read állapot.

### 18.3 API integracio tipusok

- web form submit (redirect + flash message)
- JSON endpoint hivas (`expectsJson` esetek)

### 18.4 adatbetöltési minta
A kontrollerek jellemzoen eager loadingot hasznalnak (`with(...)`) a N+1 csokkentesere.

---

## 19. Frontend betöltőrendszer és UX visszajelzesek

### 19.1 Visszajelzesi forma
A rendszer fő visszajelzesi csatornai:

- success flash üzenetek
- validation error blokkok
- inline állapotjelzesek
- badge/unread számlálótk

### 19.2 Admin valós idejű jelzes
Az admin navbarban olvasatlan üzenetszam jelenik meg, amit a view composer tolt fel.

### 19.3 User notification UX
felhasználói oldalon a nav értesítéssek user-specifikus és globalis (user_id null) elemekét egyesítenek.

### 19.4 konzisztencia
A redirect + flash mintazat egyszeru, konzisztens, tanulhato felhasználói folyamatot ad.

---

## 20. Frontend felhasználói folyamatok

### 20.1 fő flow: jármu rögzites

1. user beloginol
2. auto letrehozas
3. auto megjelenik sajat listaban

### 20.2 fő flow: időpont foglalas

1. user valaszt sajat autot
2. foglalasi urlap kitoltese
3. konfliktusellenőrzes
4. pending időpont letrejon
5. e-mail visszaigazolas kiserlet

### 20.3 fő flow: admin szervizkezelés

1. admin szuri/listazza időpontokat
2. szerkeszti statuszt és szerviz adatokat
3. service fotok csatolasa
4. kész állapotnal user értesítéss

### 20.4 fő flow: piacter

1. user megtekinti sales listat
2. hirdetés reszlet oldalon képgaleria + adatok
3. kapcsolodo kommunikacio sale message endpointon

### 20.5 fő flow: üzenet

- auto kontextusban vagy sale kontextusban indul,
- rendszer automatikusan feloldja a cimzettet,
- olvasatlan számlálótk frissulnek.

### 20.6 fő flow: értesítéss

- user olvassa az egyedi és globalis notification-t,
- read-all endpointtal egyszerre zarhatja.

---

## 21. Frontend stilusrendszer

### 21.1 Alapelvek

- bootstrap utility-first szerkezetek
- sajat sass/css kiegeszitesek
- blade oldalszintu stilusreszek

### 21.2 konzisztencia
A rendszer legnagyobb erőssege a konvencionalis, ismerős admin panel jellegu UI, amely gyors betanulast ad.

### 21.3 Tovabblepesi iranyok

- design tokenek formalizalasa,
- komponensszintu stilus standardok,
- sotet/vilagos tema opcio.

---

## 22. Frontend űrlapok és validáció

### 22.1 validációs rétegek

- szerver oldali validáció (fő)
- request osztalyok egyes moduloknal
- inline validáció más moduloknal

### 22.2 sales validáció
A sales store/update validáció kezeli:

- közös domain mezőkét,
- képfeltoltés korlatot (max 10),
- tamogatott mime tipusokat,
- meretlimitet.

### 22.3 Appointment validáció
Az időpont modulban datum, idő, auto ownership, állapot-átmenet és konfliktus logika egyszerre érvényesül.

### 22.4 Admin appointment validáció
Az admin oldal kibovitett mezőkét kezel (ugyfel + auto technikai adatok), és külön status update endpoint is van.

### 22.5 Hiba megjelenites
A validációs hibak tipikusan redirect + oldalon megjelenített error blokkokon keresztul látszanak.

---

## 23. Frontend hibakezel\u00e9s és hibateruletek

### 23.1 Hibateruletek

- URL validációs hiba
- jogosultsági hiba (403/redirect)
- uzleti konfliktus (időpont overlap)
- f\u00e1jlmuveleti hiba
- mail küldés hiba (degradalt uzemmod)

### 23.2 UX szempont
A rendszer altalaban nem "hard fail" modon viselkedik; a kritikus uzleti adat mentesre kerul, majd optional szolgaltatas (mail) hibaja logolodik.

### 23.3 Logging
E-mail kuldesnel explicit log metadata segit root-cause elemezni.

### 23.4 Javasolt tovabbi erősítéss

- egyssegés hibakod-konvencio,
- centralis exception mapping,
- strukturalt audit log.

---

## 24. környezet és deployment

### 24.1 Kotelezo komponensek

- PHP 8.2+
- Composer
- Node + npm
- MySQL/MariaDB

### 24.2 Build és futtatas
Backend + frontend assetek egyutt futnak a Laravel appon belül.

### 24.3 Storage elofeltétel
A képés funkciokhoz kotelezo `storage:link`.

### 24.4 Queue/log folyamatok
Dev script parhuzamosan futtat:

- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail`
- `npm run dev`

### 24.5 Production checklist

- APP_KEY/APP_ENV helyes
- DB connection rendben
- MAIL konfiguracio rendben
- FILESYSTEM diszk beallitas
- queue worker daemonized
- log rotacio beallitva
- backup policy letrehozva

---

## 25. Helyi fejlesztési környezet

### 25.1 Gyors setup

1. `composer install`
2. `npm install`
3. `.env` letrehozas `.env.example` alapjan
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan storage:link`

### 25.2 Napi futtatas

- `php artisan serve`
- `npm run dev`

vagy osszevontan:

- `composer run dev`

### 25.3 Seeder adatok
A seederek demo tartalommal toltik fel:

- felhasználók,
- autok,
- hirdetések (lokalis képforrasbol),
- issue-k,
- időpontok,
- üzenetek.

### 25.4 Tesztek
A standard tesztfuttatas a Laravel/PHPUnit pipeline-on keresztul tortenik.

---

## 26. hibaelh\u00e1r\u00edt\u00e1s

### 26.1 Storage képek nem látszanak
Tuenetek:

- 404 kép URL-ek,
- hirdetés galeria ures.

Lepesek:

1. `php artisan storage:link`
2. ellenorizd, hogy a `storage/app/public/sales` tartalmaz-e f\u00e1jlokat
3. jogosultságok ellenőrzese

### 26.2 időpont foglalas sikertelen
Tipikus ok:

- datum/idő konfliktus megerősítéstt foglalassal,
- idegen autohoz torteno foglalasi kiserlet.

### 26.3 üzenetküldés tiltva
Tipikus ok:

- user nem tulajdonos,
- hirdetés inaktiv,
- nincs jogosultság a kontextushoz.

### 26.4 Mail nem megy ki

1. ellenorizd a `MAIL_*` env valtozokat
2. futtass config clear-t
3. nezd meg a logot az `APPOINTMENT_MAIL` cimkere

### 26.5 Admin dashboard nem erheto el

- ellenorizd a user role mezőjet (`admin`)
- ellenorizd, hogy auth session aktiv

### 26.6 Migration/Seeder hibak

- FK konfliktus -> migrate fresh + seed
- képek hianya -> ellenorizd a `képek` mappat

### 26.7 Valtozo route viselkedes
Ha egy endpoint maskent viselkedik, ellenorizd:

- middleware csoportot,
- policy-t,
- controller egyedi guardot.

---

## 27. Osszefoglalas

### 27.1 fő eredmeny
Az Autonex egy jol strukturalt, uzletileg hasznalhato Laravel monolit, amely:

- lefedi a jármu + szerviz + piacter főbb use-case-eit,
- role alapú jogosultsággal dolgozik,
- külön admin operacios workflow-t ad,
- képekét és kommunikaciot valós rendszerfunkciokkal kezel.

### 27.2 Legfontosabb erőssegek

- tiszta MVC modulrendszer,
- eletszeru domain modellek,
- valós admin workflow-k,
- hibaturo e-mail kezelés,
- egyszeru, gyorsan telepitheto dev pipeline.

### 27.3 Legfontosabb kockázatok

- vegyes validációs megkozelités (FormRequest + inline),
- web/API response forma nem teljesen homogen,
- payment workflow nincs teljesen kiemelve külön modulba,
- üzenetfolyamatok komplexitasa novekedessel nehezebben karbantarthato lehet.

### 27.4 Prioritaslista tovabbfejlesztéshez

1. Egyssegés API/hiba envelope policy.
2. külön service layer a komplex controller workflow-kra.
3. Teszt lefedettseg novelese (feature + policy + integration).
4. üzenetmodul audit/monitoring bovitese.
5. Optional valós idejű csatorna (websocket) a polling jelleg helyett.

---

## 28. Melleklet A - Vegpontmatrix

### A.1 Public + auth alap

- GET /
- Auth::routes() altal adott auth endpointok
- GET /home

### A.2 User dashboard + profile

- GET /dashboard
- GET /profile
- PUT /profile

### A.3 Notification

- PATCH /notifications/{notification}/read
- PATCH /notifications/read-all

### A.4 Cars

- resource: cars (index/create/store/show/edit/update/destroy)

### A.5 Appointments (user)

- GET /appointments
- GET /appointments/create
- POST /appointments
- GET /appointments/{appointment}
- PATCH /appointments/{appointment}/cancel
- PATCH /appointments/{appointment}/reschedule

### A.6 Sales

- User oldali: GET /sales, GET /sales/{sale}
- Admin oldali: create/store/edit/update/destroy
- DELETE /sales/{sale}/images/{image}

### A.7 Issues

- resource: issues

### A.8 Messages

Auto kontextus:

- POST /cars/{car}/messages
- GET /cars/{car}/messages

hirdetés kontextus:

- POST /sales/{sale}/messages
- GET /sales/{sale}/messages

Badge:

- GET /messages/unread-count

Admin message center:

- GET /admin/messages
- GET /admin/messages/car/{car}

### A.9 Admin appointments

- GET /admin/appointments
- GET /admin/appointments/create
- POST /admin/appointments
- GET /admin/appointments/{appointment}
- GET /admin/appointments/{appointment}/edit
- PUT /admin/appointments/{appointment}
- PATCH /admin/appointments/{appointment}/update-status
- DELETE /admin/service-photos/{photo}
- DELETE /admin/appointments/{appointment}

### A.10 Admin notifications

- GET /admin/notifications
- GET /admin/notifications/create
- POST /admin/notifications
- DELETE /admin/notifications/{notification}

### A.11 API vehicles

- GET /api/vehicles/types
- GET /api/vehicles/brands
- GET /api/vehicles/models
- GET /api/vehicles/body-types

---

## 29. Melleklet B - adatmodell szotar

### B.1 users
Felelos: autentikáció, szerepkor, alap profil.

fő mezők:

- id
- name
- email
- phone
- password
- role
- email_verified_at
- welcome_email_sent_at
- created_at/updated_at

### B.2 cars
Felelos: felhasználói jármu torzs.

fő mezők:

- id
- user_id
- make_model
- vin
- license_plate
- year
- deleted_at

### B.3 appointments
Felelos: szerviz foglalas + uzemeltetesi állapotok.

fő mezők:

- id
- user_id
- car_id
- date
- time
- status
- service
- description
- service_stage
- mechanic_name
- total_cost
- service_report
- issues_found
- critical_warning
- work_number
- customer_* / car_* admin adatok
- deleted_at

### B.4 issues
Felelos: hibajegy nyilvantartas.

fő mezők:

- id
- user_id
- car_id
- title/leiras/status jellegu mezők (a konkret migracio szerint)
- deleted_at

### B.5 sales
Felelos: piacteri hirdetés.

fő mezők:

- id
- car_id
- seller_id
- buyer_id
- vehicle_type
- brand
- model
- body_type
- engine_cc
- fuel_type
- documents_available
- document_type
- technical_inspection
- price
- description
- car_condition
- mileage
- is_active
- deleted_at

### B.6 sale_images
Felelos: hirdetéshez tartozo több kép metadata.

fő mezők:

- id
- sale_id
- path
- sort_order
- timestamps

### B.7 messages
Felelos: autohoz/hirdetéshez kötött üzenetek.

fő mezők:

- id
- car_id
- sale_id
- sender_id
- receiver_id
- message
- is_read
- deleted_at

### B.8 admin_notifications
Felelos: rendszerüzenetek, olvasottsag.

fő mezők:

- id
- user_id (nullable)
- title
- message
- is_read
- timestamps

### B.9 service_photos
Felelos: szerviz folyamat dokumentacio.

fő mezők:

- id
- appointment_id
- title
- path
- timestamps

---

## 30. Melleklet C - Uzemeltetesi ellenőrzolistak

### C.1 Release elotti ellenőrzes

- [ ] env valtozok megfelelnek
- [ ] migration lefutott
- [ ] seed (ha demo környezet)
- [ ] storage link megvan
- [ ] queue worker fut
- [ ] logok tisztak kritikus exception nelkul
- [ ] admin és user login tesztelve
- [ ] képfeltoltés + torlés tesztelve
- [ ] időpont workflow tesztelve
- [ ] messaging workflow tesztelve

### C.2 Smoke teszt forgatokonyv

1. Login admin és user szerepkorral.
2. User letrehoz autot.
3. User foglal időpontot.
4. Admin megerősiti majd completed+ready állapotba teszi.
5. User oldalon notification ellenőrzes.
6. Admin letrehoz/modosit hirdetést több képpel.
7. User megtekinti hirdetést és üzenetet kuld.
8. Admin valaszol, unread badge ellenőrzes.

### C.3 Incident kezelés mini runbook

- Lepés 1: reprodukalhatosag ellenőrzese.
- Lepés 2: route + middleware + policy harmás ellenőrzese.
- Lepés 3: DB állapot ellenőrzés (`appointments`, `sales`, `messages`).
- Lepés 4: storage állapot ellenőrzés (`sales`, `service-photos`).
- Lepés 5: log elemés (`laravel.log`, pail stream).
- Lepés 6: hotfix vagy rollback dontes.

### C.4 fejlesztési quality gate

- kodstilus rendben,
- validáció nem lazult,
- jogosultság nem gyengult,
- N+1 regresszio nincs,
- route naming konzisztens,
- UI feedback egyertelmu.

---

## Zaradek
Ez a dokumentacio az Autonex aktualis kodállapotahoz igazodva keszult, a valós route-ok, kontrollerek, modellek, policy-k, seederek és migracios állapot figyelembevetelevel. A dokumentum celja, hogy fejlesztoi, uzemeltetoi és atadasi oldalrol egyarant hasznalhato referencia legyen.

---
---

## 31. Melleklet - Roviditett kiegeszitesek

### 31.1 Controller-specifikacio (osszefoglalo)

- SaleController: listázás, létrehozás, szerkesztés, képfeltöltés, képtörlés, policy-alapú törlés.
- AppointmentController: user foglalás, konfliktuskezelés, lemondás, átütemezés, e-mail visszaigazolás.
- AppointmentManagementController: admin szűrés, státuszfrissítés, service-fotók, kész állapot értesítés.
- MessageController: autó- és hirdetés-kontextusú beszélgetések, olvasatlan szám, admin üzenetközpont.
- DashboardController: user és admin KPI-k, napi/havi összesítések.

### 31.2 Migration és seeder osszefoglalo

- Migrációk fokozatosan bővítették az appointment/sales/message domaint.
- A sales képkezelés külön táblában (`sale_images`) történik.
- Seeder sorrend: userős -> cars -> sales -> issues -> appointments -> messages.
- A sale képek lokális `képek` forrásból kerülnek storage-ba.

### 31.3 Teszt és uzemeltetés roviden

- Kritikus tesztek: jogosultságok, appointment konfliktus, sales képkezelés, messaging receiver logika.
- Üzemeltetési fókusz: storage link, queue worker, log monitor, backup ellenőrzés.
- Release minimum: smoke teszt + role alapú hozzáférés-ellenőrzés.

### 31.4 Tovabbi fejlesztési iranyok

1. Egységés API hiba-válaszok.
2. Service layer a komplex controller logikák alá.
3. Nagyobb tesztlefedettség policy és workflow ágon.
4. Messaging teljesítmény-optimalizálás (pagination, indexek).
5. Opcionális valós idejű kommunikáció (WebSocket).

### 31.5 Word-export cel (40-45 oldal)

Javasolt formázás:

- Betű: Timés New Roman 12 vagy Calibri 11
- Sorköz: 1.15
- Margó: normál
- Címsorok: Heading 1/2/3
- Automatikus Tartalomjegyzék

Ezzel a rövidített változat tipikusan a kért tartományhoz közelít.

---

## 32. Melleklet D - Hibakod és valaszmatrix

### 32.1 Altalanos status kodok

A projektben a k\u00f6vetkezo status kodok tekinthetok iranyadonak:

- 200: sikerés JSON valasz
- 302: sikerés web redirect
- 401: bejelentkezés hianya
- 403: jogosultság hianya
- 404: nem letezo eroforras
- 422: validációs hiba

### 32.2 Appointment hibaképek

Tipikus hibak:

- date multbeli -> 422
- time format hiba -> 422
- idegen auto -> 403
- confirmed slot utkozés -> validációs hiba

### 32.3 sales hibaképek

Tipikus hibak:

- hianyzo kotelezo mező -> 422
- kép mime hiba -> 422
- kép meret limit tullepés -> 422
- nem jogosult modositas/torlés -> 403

### 32.4 Message hibaképek

Tipikus hibak:

- jogosulatlan küldés inAktív kontextusban -> 403
- hianyzo message text -> 422

### 32.5 Notification hibaképek

Tipikus hibak:

- más user notificationjanak olvasasa -> 403

### 32.6 Uzemeltetesi javaslat

A gyakorlatban hasznos egy rovid hibakod-szotar a support csapatnak, ahol endpointonkent szerepel:

- tipikus user hibaüzenet,
- valós backend ok,
- javasolt operator teendo.

---

## 33. Melleklet E - Bovitett endpoint-csoportositas

### 33.1 Auth és session

- Laravel auth route-ok
- session alapú hozzáférés
- admin route extra middleware

### 33.2 Profile és account

- profile edit/update
- account adatok karbantartasa

### 33.3 Cars domain

- teljes CRUD
- ownership policy
- kapcsolódás issue és appointment modulhoz

### 33.4 Appointment domain

- user oldali foglalas
- admin oldali menedzsment
- status workflow
- conflict v\u00e9delmi szabaly

### 33.5 sales domain

- listazo + reszlet user oldalon
- letrehozas/szerkesztés admin oldalon
- tobbképés media modell

### 33.6 Issue domain

- hiba bejelentes
- policy alapú hozzáférés

### 33.7 Messaging domain

- auto kontextus
- hirdetés kontextus
- olvasatlan badge
- admin conversation center

### 33.8 Notification domain

- egyedi értesítés olvasas
- osszés olvasottra allitas
- admin oldali kezelés

### 33.9 Utility API domain

- vehiclés tipus/marka/modell/body-type vegpontok
- URLap helper adatszolgaltatas

---

## 34. Melleklet F - Bovitett tesztforgatokonyv

### 34.1 Smoke teszt (rovid)

1. login user/admin
2. car letrehozas
3. appointment store
4. admin status update
5. sale listázás és reszlet
6. message küldés és olvasas
7. notification read-all

### 34.2 Funkcionalis teszt (reszletes)

Cars:

- create/update/delete ownership ellenőrzessel

Appointments:

- store konfliktusmentés slotra
- store konfliktusos slotra
- cancel és reschedule különbozo statuszoknal

Sales:

- admin create képekkel
- image delete guard
- update append image viselkedes

Messaging:

- car és sale kontextus receiver feloldas
- read flag update ellenőrzes

Notifications:

- sajat olvasas
- idegen elem tiltasa

### 34.3 Regreszios teszt

Minden release elott legalabb:

- policy regression,
- route v\u00e9delmi regression,
- storage elerés regression,
- dashboard aggregacio sanity check.

### 34.4 Tesztadat policy

A tesztekben ajánlott:

- külön admin és normal user fixture,
- legalabb két user + két auto + két sale minta,
- konfliktusra eloallitott appointment minták.

### 34.5 Exit kriterium

Release csak akkor mehet productionre, ha:

- blocker hiba nincs,
- kritikus workflow-k passzolnak,
- jogosultsági tesztek passzolnak.

---

## 35. Melleklet G - Uzemeltetesi ellenőrzés bovitve

### 35.1 Deployment elotti ellenőrzes

- env valtozok
- DB kapcsolat
- migration dry-run terv
- backup aktualitas

### 35.2 Deployment utani ellenőrzes

- application elérhetőség
- key endpointok valaszideje
- storage képelérés
- queue process állapot

### 35.3 Napi monitor checklist

- kritikus hiba log
- 5xx trend
- unread badge endpoint elérhetőség
- appointment store endpoint elérhetőség

### 35.4 Heti monitor checklist

- DB meret trend
- storage meret trend
- slow query minta
- failed job minta

### 35.5 Havi kontroll

- jogosultsági audit
- policy review
- dokumentacio frissites
- release checklist korrekcio

---

## 36. Zaro megjegyzés a terjedelemhez

Ez a verzio tudatosan roviditett, de tovabbra is teljes koru muszaki attekintest ad:

- architektúra,
- backend,
- frontend,
- domain modellek,
- endpointok,
- workflow-k,
- teszteles,
- uzemeltetes.

A túlméretezett mellekletek helyett a legfontosabb reszek maradtak benne, hogy a dokumentum Wordben varhatoan kozelebb essen a kert 40-45 oldalas tartomanyhoz.

Ha a vegso Word exportnal meg mindig 45 folott lenne, akkor a melleklet D-F pontok rovidithetok. Ha 40 alatt maradna, ugyanitt a tesztforgatokonyvek bovithetoek tovabbi use-case-ekkel.

