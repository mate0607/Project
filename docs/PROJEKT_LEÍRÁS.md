# AutoNex - Autókezelés és Szervizpontok Menedzsment Rendszer

## 1. Projekt Áttekintése

Az **AutoNex** egy komplex, teljes körű webalkalmazás, amely autótulajdonosok és szervizpontok számára nyújt integrált megoldást az autókezeléshez, szervizidőpontok foglalásához és intelligens szolgáltatás-ajánlásokhoz. A rendszer egy modern tech stack-kel épített, amely React frontend-et, Node.js backend-et és MySQL adatbázist kombinál.

### Projekt Motivációja
Jelenleg sok autótulajdonos szétszórva tartja az autóhoz kapcsolódó információkat (szervizek, kiadások, problémák). Az AutoNex ezt egy helyre gyűjti, és intelligens ajánlási rendszeren keresztül segít abban, hogy a felhasználók tudják, milyen szerviz-kezelésre van szükségük, mekkora költséggel számolhatnak, és könnyen foglalhatnak időpontot.

---

## 2. Projekt Célja & Célcsoportok

### 2.1 Fő Célok
1. **Autótulajdonosok** - Központi helyre gyűjteni az autó-releváns adatokat, nyomonkövetni az esetleges problémákat, és egyszerűen szervizidőpontokat foglalni
2. **Szervizpontok** - Ügyfélbázist kezelni, foglalásokat nyomonkövetni és szolgáltatások tájékoztatását nyújtani
3. **Tudásmegosztás** - Egy átfogó tudásbázis (Knowledge Hub) ahol megismerhető a gyakori autóproblémák, karbantartási igények és megoldási lehetőségek
4. **Autók Értékesítése** - Felhasználók könnyen eladhatják autójukat egy beépített márkahelyen, vevőkkel közvetlenül kommunikálhatnak

### 2.2 Célcsoportok
- **Privát autótulajdonosok**: Akik szervezetten szeretnék kezelni autójuk szervízét
- **Szervizpontok**: Akik ügyfelek foglalásait szeretnék kezelni
- **Admin felhasználók**: Akik az egész rendszert felügyelik

---

## 3. Technológiai Stack

### 3.1 Frontend Technológiák

**React & Vite**
- React 18+ verzió modern Hook-alapú komponensek használatával
- Vite - ultragyors build tool és dev server (~300ms reload time)
- JSX szintaxis az intuitív UI komponensek írásához

**React Router v6**
- Deklaratív routing system
- Nested routes támogatás
- Private routes komponens a jogosultsághoz kötött oldalakhoz
- useParams, useNavigate hokok az URL paraméterek kezeléséhez

**Fetch API & Custom API kliens**
- Szinkron API kezelés async/await-tel
- JWT token automata kezelése Authorization headerben
- Error handling és response processing

**jsPDF Könyvtár**
- PDF dokumentumok dinamikus generálása JavaScript-ből
- Foglalások exportálása PDF formátumban
- Többoldalas támogatás automatikus page breaks-szel

**Styling megközelítés**
- Inline CSS objektumok a komponensekben
- Tailwind-inspirált color scheme (dark mode)
- CSS class-ok a globális stílusokhoz (App.css)
- Reszponzív design breakpoints

### 3.2 Backend Technológiák

**Node.js & Express.js**
- Express 4.x - lightweight HTTP szerver
- Middleware stack: CORS, body parser, custom auth middleware
- RESTful API design patterns

**Adatbázis - MySQL**
- Relációs adatbázis szezően támogatott séma
- mysql2/promise - async-capable MySQL driver
- Connection pooling a terhelés kezeléséhez
- Prepared statements az SQL injection elleni védelemhez

**Autentikáció & Biztonság**
- JWT (JSON Web Tokens) - token-based authentication
- bcrypt - salted password hashing (12 rounds)
- Middleware-based authorization különböző végpontokra
- Role-based access control (User vs Admin)

**Recommender Engine**
- Custom JavaScript-alapú ajánlási motor
- 20 különféle szerviz-kategória
- Reguláris kifejezések (regex) alapú pattern matching
- Dinamikus ár-becslés különféle kategóriákhoz
- Hungarian language keywords integrálva

### 3.3 Infrastruktúra

**Fejlesztői Környezet**
- Frontend: http://localhost:5173 (Vite dev server)
- Backend: http://localhost:4000 (Express server)
- API URL: http://localhost:4000/api

**Telepítési Konfiguráció**
- CORS beállítás: http://localhost:5173-ból érkező requestekre
- JWT_SECRET environment variable a tokenekhez
- MySQL adatbázis lokálisan vagy felhőben

---

## 4. Fő Funkciók Részletesen

### 4.1 Felhasználó Kezelés & Autentikáció

#### Regisztráció
```
POST /api/auth/register
Body: { name, email, password }
```
- Email alapú felhasználói fiók létrehozása
- Duplikát email ellenőrzés (409 Conflict ha már létezik)
- Jelszó titkosítás bcrypt-tel
- Automatikus JWT token generálás sikeres regisztrációkor
- User role alapértelmezetten ("user")

#### Bejelentkezés
```
POST /api/auth/login
Body: { email, password }
```
- Email + jelszó ellenőrzés
- bcrypt password comparison
- JWT token generálás (7 nap lejárat)
- Token localStorage-ben tárolása frontend-en

#### Profil Kezelés
```
GET /api/auth/profile
PUT /api/auth/profile
Body: { name, email, phone }
```
- Felhasználó adatok lekérése JWT tokenből
- Adatok frissítése (név, email, telefon)
- Auth middleware ellenőrzi a tokent minden híváskor

### 4.2 Autó Menedzsment

#### Autó Hozzáadása
```
POST /api/cars
Body: { make_model, vin, year }
```
- Márka/modell szöveges azonosítás
- VIN (Vehicle Identification Number) - 17 karakter
- Gyártás év (opcionális)
- Felhasználóhoz kötött (user_id) az adatbázisban

#### Autó Megtekintése
```
GET /api/cars
GET /api/cars/:carId
```
- Felhasználó összes autója
- Autó összes korábbi hibáját (issues) is lekéri

#### Autó Szerkesztése & Törlése
```
PUT /api/cars/:carId
DELETE /api/cars/:carId
```
- Felhasználó csak saját autóit szerkesztheti/törölheti
- Szoftver törlés (jelölés) vagy teljes törlés

### 4.3 Hiba Nyomonkövetés & Intelligens Ajánlások

#### Hiba Rögzítése
```
POST /api/cars/:carId/issues
Body: { category, description, urgency }
```

**Kategóriák (20 féle)**:

**Ajánlási rendszerbe integráltak (10)**:
1. **engine** (Motor) - Motorral kapcsolatos problémák
   - Keywords: rángat, erőtlen, dadog, vibráció, fogyasztás
2. **brake** (Fék) - Fékrendszer hibák
   - Keywords: csikorog, pedál, merev, nem fékez
3. **suspension** (Felfüggesztés) - Futómű és rugózás
   - Keywords: kopog, ráz, zörög, guruló
4. **electric** (Elektromosság) - Elektromos rendszer
   - Keywords: nem indul, akku, indítómotor, generátor
5. **transmission** (Sebességváltó) - Váltórendszer
   - Keywords: csúszik, recseg, nehéz kapcsolás
6. **cooling** (Hűtési rendszer) - Hűtőrendszer
   - Keywords: túlmelegszik, hűtővíz, termosztát
7. **exhaust** (Kipufogó rendszer) - Kipufogó
   - Keywords: hangos, füst, katalizátor, DPF
8. **tyres** (Gumiabroncsok) - Gumik és kerekek
   - Keywords: defekt, nyomás, rezgés, kiegyensúlyozás
9. **aircon** (Légkondicionálás) - Klímarendszer
   - Keywords: nem fúj, hideg, meleg levegő, gáz
10. **fluids** (Folyadékszivárgás) - Olaj/hűtőfolyadék
    - Keywords: szivárog, csöpög, folyadék, vérző

**További kategóriák (10)**:
11. **fuel** (Üzemanyag rendszer)
12. **steering** (Kormányzás)
13. **battery** (Akkumulátor)
14. **alternator** (Generátor)
15. **starter** (Indítómotor)
16. **lights** (Fények)
17. **wipers** (Ablaktörlők)
18. **interior** (Belső rész)
19. **exterior** (Külső rész)
20. **other** (Egyéb)

**Sürgősség szintek**:
- **low** (Alacsony) - Karbantartási jellegű, nem sürgős
- **medium** (Közepes) - Közeljövőben javítandó
- **high** (Nagy) - Biztonsági kockázat, azonnal javítandó

#### Intelligens Ajánlási Rendszer

```
GET /api/recommendations/:issueId
```

**Ajánlás folyamata**:
1. Backend lekéri az issue-t
2. Recommender.js feldolgozza a kategóriát és leírást
3. Regex pattern matching a description-ben
4. Első illeszkedő szabály kiválasztása
5. Szerviz ajánlás + magyarázat + költségbecslés

**20 Ajánlási Szabály**:

| Kategória | Ajánlott Szerviz | Min Ár | Max Ár | Magyarázat |
|-----------|------------------|--------|---------|-----------|
| Motor | OBD diagnosztika + motor hibakód | 7,000 | 20,000 | Motor panaszoknál hibakód kiolvasás gyorsan szűkíti a hibát |
| Fék | Fékrendszer átvizsgálás + betét/tárcsa | 8,000 | 45,000 | Biztonsági okból fékvizsgálat kötelező |
| Felfüggesztés | Futómű átvizsgálás (szilent, gömbfej) | 10,000 | 50,000 | Kopogás/rázás gyakran futómű kopás |
| Elektromosság | Akkuteszt + töltőrendszer ellenőrzés | 5,000 | 25,000 | Indítási gondoknál akku/generátor ellenőrzés |
| Sebességváltó | Váltó/kuplung vizsgálat | 15,000 | 120,000 | Recsegés/csúszás a váltó/kuplung hibájára utal |
| Hűtés | Hűtőrendszer diagnosztika | 8,000 | 60,000 | Melegedésnél termosztát vagy vízpumpa gond |
| Kipufogó | Kipufogó rendszer átvizsgálás | 10,000 | 150,000 | Füst/hangos kipufogó DPF/katalizátor gonddal |
| Gumiabroncsok | Kerék ellenőrzés + centrírozás | 3,000 | 15,000 | Rezgés 90% eséllyel kiegyensúlyozatlanság |
| Légkondicionálás | Klímarendszer ellenőrzés + töltés | 12,000 | 35,000 | Klíma hibáinak 80%-a gázhiány |
| Folyadék | Szivárgás felderítése + olajszint | 5,000 | 45,000 | Szivárgás hosszú távon motorkárt okoz |
| Üzemanyag | Üzemanyag rendszer + injektortisztítás | 8,000 | 35,000 | Üzemanyag rendszer hibája gyenge teljesítményt okoz |
| Kormányzás | Kormányzási rendszer átvizsgálása | 10,000 | 50,000 | Kormányzási problémák biztonsági kockázatot jelentenek |
| Akkumulátor | Akkumulátor kapacitás teszt + csere | 15,000 | 50,000 | Akku leromlása idővel természetes folyamat |
| Alternátor | Alternátor kimenet teszt + szint | 20,000 | 60,000 | Hiányos alternátor gyors akku lemerüléshez vezet |
| Indítómotor | Indítómotor átvizsgálása + megkerülő teszt | 15,000 | 55,000 | Indítómotor hibája az indítási gondok oka |
| Fények | Világítási rendszer átvizsgálása + izzó csere | 2,000 | 15,000 | Meghibásodott fények biztonsági kockázat |
| Ablaktörlők | Ablaktörlő szalag csere + töltés | 2,000 | 8,000 | Meghibásodott törlő csökkenti látási viszonyokat |
| Belső rész | Belső felületek tisztítása + javítások | 5,000 | 25,000 | Belső tisztántartása növeli az értéket |
| Külső rész | Külső felületek átvizsgálása + polírozás | 8,000 | 60,000 | Külső kezelése megakadályozza rozsdásodást |
| Egyéb | Általános állapotfelmérés + diagnosztika | 12,000 | 40,000 | Ismeretlen problémáknál általános vizsgálat |

### 4.4 Szervizidőpont Foglalás & Menedzsment

#### Foglalás Létrehozása
```
POST /api/appointments
Body: { car_id, date, time, service }
```

**Validációk**:
- Jármű kötelező (user saját autói közül)
- Dátum kötelező és jövőbeli
- Idő HH:MM formátumban
- Szolgáltatás 22 lehetőség közül
- Duplikált dátum/idő ellenőrzés (409 Conflict)

**Szolgáltatások (22 lehetőség)**:
1. Olajcsere
2. Fék ellenőrzés
3. Gumiabroncs csere
4. Diagnosztika
5. Általános szerviz
6. Szellőztetőfolyadék csere
7. Légszűrő csere
8. Gyújt gyertya csere
9. Féktöltés csere
10. Fékfolyadék csere
11. Sebességváltó szerviz
12. Akkumulátor csere
13. Alternátor vizsgálat
14. Indítómotor vizsgálat
15. Gumiabroncs forgat
16. Szögbeállítás
17. Felfüggesztés vizsgálat
18. Kipufogó vizsgálat
19. Légkondicionálás szerviz
20. Ablaktörlő csere
21. Fékmester vizsgálat
22. Biztosíték csere

#### Foglalás Státusza
- **pending** (Függőben) - Újonnan létrehozott, várakozó
- **confirmed** (Megerősített) - Admin által jóváhagyott
- **completed** (Kész) - Befejezett vagy automatikusan lezárt

#### Automatikus Státusz Frissítés
- Minden 60 másodpercben ellenőrzés
- Ha foglalás Start Time + 4 óra < Current Time
- Automatikus státusz váltás "completed"-re
- API hívás: `PUT /api/appointments/:id/status`

#### Foglalás Szerkesztése
```
PUT /api/appointments/:id
Body: { date, time, service }
```
- Dátum, idő, szolgáltatás módosítható
- Újabb duplum ellenőrzés az új időnél
- User csak saját foglalásait szerkesztheti

#### Foglalás Törlése
```
DELETE /api/appointments/:id
```
- Soft delete vagy hard delete
- User csak saját foglalásokat törölhet
- Admin bármely foglalást törölhet

#### PDF Export
```
exportToPDF()
```
**Funkciók**:
- Összes foglalás PDF-be
- Fejléc: "Foglalások Kivonat"
- Dátum: Exportálás napja
- Tartalom: Minden foglalás:
  - Sorszám + Szolgáltatás
  - Autó neve (opcionális)
  - Dátum + Idő
  - Státusz (Kész/Megerősített/Függőben)
- Automatikus oldaltörés > 270pt-nél
- Fájlnév: `foglalasok_[timestamp].pdf`
- jsPDF library használata

### 4.5 Tudásbázis (Knowledge Hub)

#### Szerkezet
- 3 kategória
- 24 cikk összesen
- Keresés & szűrés

#### Kategóriák

**1. Általános problémák (12 cikk)**
- Motor túlmelegedése
- Váltó csúszása
- Féktárcsa kopása
- Gyújtógyertya elérési élete
- Hűtőfolyadék csere
- Olajcsere szükségessége
- Akkumulátor lemerülése
- Légkondicionálás hiba
- Kipufogó rendszer hibája
- Gumiabroncs nyomása
- Fékfolyadék ellenőrzés
- Szűrők tisztítása/cseréje

**2. Karbantartás (12 cikk)**
- Rendszeres olajcsere (3000-5000 km-ként)
- Szűrőcsere (8000-12000 km-ként)
- Gyertya csere (20000-40000 km-ként)
- Fékfolyadék csere (2 évente)
- Hűtőfolyadék csere (3-5 évente)
- Féktárcsa ellenőrzés (40000 km-ként)
- Kopó alkatrészek ellenőrzése
- Gumiabroncs forgatas (10000-15000 km-ként)
- Szögbeállítás (megsérülés után)
- Klímaszűrő csere (évente)
- Légszűrő csere (10000-20000 km-ként)
- Akkumulátor tesztelés (3 évente)

**3. OBD Kódok (több mint 8 kód)**
- P0128: Thermostat malfunction
- P0171: System too lean
- P0301: Random/multiple cylinder misfire
- P0420: Catalyst system efficiency
- P0500: Vehicle speed sensor
- stb.

#### Cikk Tartalma
```json
{
  "id": 1,
  "title": "Motor túlmelegedése",
  "category": "common-issues",
  "summary": "A motor túlmelegedése veszélyes és azonnali figyelmet igényel",
  "cost": "8000-45000 Ft",
  "description": "Részletes leírás...",
  "causes": ["Hűtőfolyadék hiánya", "Termosztát hiba", "Vízpumpa meghibásodása"],
  "symptoms": ["Mérő túlmelegedést mutat", "Gőzölgés a motorháztető alól", "Teljesítmény csökkenés"],
  "tips": ["Azonnal álljon meg és hagyja lehűlni", "Ellenőrizze a hűtőfolyadék szintjét", "Hívjon szervízt"]
}
```

#### Keresés & Szűrés
- Cím alapú keresés
- Kategória szerinti szűrés
- Real-time, case-insensitive keresés

### 4.6 Admin Funkciók

#### Összes Foglalás Megtekintése
```
GET /api/appointments/admin
```
- Admin csak if req.user.role === "admin"
- Összes felhasználó foglalásai
- Felhasználó név + email + telefon
- Autó információ (márka/modell, VIN)

#### Foglalás Státusz Módosítása
```
PUT /api/appointments/:id/status
Body: { status }
```
- Csak admin módosíthat
- pending → confirmed → completed
- Timestamp rögzítése

---

## 4.7 Eladásra Bocsájtás & Hirdetések [NEW v2.0] ⭐

### Autó Eladásra Bocsájtása
```
POST /api/sales
Body: { car_id, price, description, car_condition, mileage }
```

**Paraméterek**:
- `car_id` - Felhasználó saját autójának ID-je (FK)
- `price` - Eladási ár (DECIMAL, >0)
- `description` - Hirdetés szövege (TEXT, opcionális)
- `car_condition` - Autó állapota (5 fokozat)
- `mileage` - Futásteljesítmény (INT, opcionális)

**Autó Állapot Kategóriák**:
1. **Kiváló** - Újszerű, minimális használat
2. **Jó** - Általános jó állapot, apróbb kopottas
3. **Elfogadható** - Működik, látható használat
4. **Javítást Igényel** - Működik, de szükséges karbantartás
5. **Nem Tudható** - Leírás szerint

**Validációk**:
- Felhasználó csak saját autóját bocsájthatja eladásra
- Price > 0 (érvényes ár)
- Egy autó csak egy aktív hirdetés (car_id UNIQUE)
- is_active automatikus TRUE-ra

#### Aktív Hirdetések Böngészése
```
GET /api/sales/all/active
```
- **Nyilvános végpont** (nincs autentifikáció szükséges)
- Összes is_active = TRUE hirdetés
- Autó képe, ár, márka-modell, év, futásteljesítmény
- Eladó neve és info
- Legfrissebb hirdetések elsőnek

**Response Formátum**:
```json
{
  "id": 1,
  "car_id": 5,
  "user_id": 2,
  "price": 3500000,
  "description": "Szép autó, jó állapot",
  "car_condition": "Jó",
  "mileage": 145000,
  "is_active": true,
  "created_at": "2026-01-23T10:30:00Z",
  "make_model": "Toyota Corolla",
  "year": 2019,
  "vin": "ABCD123456789EFG",
  "image_url": "/uploads/cars/img_123.jpg",
  "seller_name": "Karsai Márk"
}
```

#### Hirdetés Részletei
```
GET /api/sales/:saleId
```
- Teljes autó információ
- Eladó teljes neve
- Akár több fotó (opcionális bővítés)
- Forgalmi előzmények (opcionális bővítés)

#### Hirdetés Törlése (Szedezésből levétel)
```
DELETE /api/sales/:carId
```
- Csak eladó törölheti saját hirdetéseit
- is_active = FALSE (soft delete)
- Autó marad az adatbázisban

### 4.8 Üzenetrendszer - Vevő-Eladó Kommunikáció [NEW v2.0] ⭐

#### Üzenet Küldése
```
POST /api/messages
Body: { sale_id, message }
```

**Paraméterek**:
- `sale_id` - Hirdetés ID-je (FK)
- `message` - Üzenet szövege (TEXT, max 2000 char)

**Automata Mezők**:
- `sender_id` - Bejelentkezett felhasználó ID-je
- `receiver_id` - Hirdetés eladójának ID-je (auto lekérés)
- `is_read` - FALSE (alapértelmezés)
- `created_at` - Automatikus timestamp

**Validációk**:
- Csak bejelentkezett felhasználók küldhetnek üzenetet
- Vevő nem küldhet üzenetet saját magának (sender_id ≠ receiver_id)
- Hirdetés léteznie kell (sale_id validáció)
- Üzenet nem lehet üres

#### Hirdetéshez Tartozó Üzenetek
```
GET /api/messages/:saleId
```
- Autentifikáció szükséges
- Felhasználóhoz tartozó üzenetek (sender vagy receiver)
- Üzenetek listája sorrendben (CREATE ASC)
- Sender információ (neve, ID)
- Timestamp megjelenítéshez

**Response**:
```json
[
  {
    "id": 1,
    "sale_id": 3,
    "sender_id": 5,
    "receiver_id": 2,
    "sender_name": "Nagy János",
    "message": "Még elérhető az autó?",
    "is_read": true,
    "created_at": "2026-01-23T14:30:00Z"
  },
  {
    "id": 2,
    "sale_id": 3,
    "sender_id": 2,
    "receiver_id": 5,
    "sender_name": "Karsai Márk",
    "message": "Igen, még nincs elkelt!",
    "is_read": false,
    "created_at": "2026-01-23T14:35:00Z"
  }
]
```

**Auto Olvasottság Jelzés**:
- Hirdetés lekéréskor: `UPDATE messages SET is_read = TRUE WHERE receiver_id = user_id AND sale_id = X`
- Kétirányú kommunikáció támogatása
- Olvasattság nyomonkövetése (opcionális notifikáció)

#### Üzenetrendszer Jellemzői
- **Valós idejű**: Üzenetek azonnal megjelennek
- **Egyszerű**: Nincs chatroom, csak szekvenciális üzenetek
- **Biztonságos**: Csak a felek látják az üzeneteket
- **Archivált**: Üzenet előzmények megmaradnak
- **Egyedi vonala**: Hirdetésenként egy beszélgetés thread

---

## 5. Felhasználói Interfész & Dizájn

### 5.1 Design Filozófia
- **Dark Mode** - Modern, szem-barát sötét téma
- **Egységes layout** - Kártya alapú, konzisztens
- **Mobil-responsive** - Flexbox + media queries
- **Gyors feedback** - Real-time validáció, loading states

### 5.2 Szín Paletta
```
Háttér: #0F172A (Deep Navy)
Szöveg: #F1F5F9 (Off-white)
Másodlagos szöveg: #94A3B8 (Slate)
Lila (info/szerkesztés): #818CF8
Zöld (siker): #22C55E
Piros (hiba/veszély): #EF4444
Sárga (figyelmeztetés): #F59E0B
```

### 5.3 Komponens Típusok

**Kártyák**
- Egyenletes padding + border-radius
- Box shadow az mélységhez
- Hover effekt az interaktívaknak

**Gombok**
- Primary: Lila háttér (#818CF8)
- Secondary: Transzparens border
- Danger: Piros háttér (#EF4444)
- Loading state: Disabled + spinner

**Formok**
- Input/Select/Textarea egységes styling
- Label minden mező felett
- Inline error üzenetek (piros, 0.75rem)
- Fokuszált állapot: border/outline szín

**Badgek**
- Státusz jelzésekhez
- Szín a státusz alapján
- Szöveg-transform: capitalize

**Spinnerek**
- CSS animation loading indikátor
- Betöltési üzenetek

### 5.4 Fejlesztett Hibaellenőrzés

**Frontend Validáció**
```
validationErrors = {
  date: "A dátum nem lehet a múltban",
  time: "Érvénytelen időformátum (HH:MM)",
  service: "A szolgáltatás kiválasztása kötelező",
  car_id: "Járművet kell kiválasztani"
}
```

**Error Banner**
- Piros háttér (rgba(239, 68, 68, 0.1))
- Piros szöveg (#FCA5A5)
- X gomb az eltüntetéshez
- Auto-dismiss opcionális

**Success Notification**
- Zöld háttér (rgba(34, 197, 94, 0.1))
- Zöld szöveg (#86EFAC)
- 3 másodperces auto-dismiss

**Inline Field Errors**
- Érvénytelen input: piros border
- Hiba szöveg az input alatt (0.75rem)
- ✕ ikon az error előtt

---

## 6. Adatbázis Séma

### Felhasználók Tábla
```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  role ENUM('user', 'admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Autók Tábla
```sql
CREATE TABLE cars (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  make_model VARCHAR(255) NOT NULL,
  vin VARCHAR(17),
  year INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Hibák Tábla
```sql
CREATE TABLE issues (
  id INT PRIMARY KEY AUTO_INCREMENT,
  car_id INT NOT NULL,
  category VARCHAR(50) NOT NULL,
  description TEXT NOT NULL,
  urgency ENUM('low', 'medium', 'high') DEFAULT 'medium',
  service_name VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);
```

### Foglalások Tábla
```sql
CREATE TABLE appointments (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  car_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  service VARCHAR(255) NOT NULL,
  status ENUM('pending', 'confirmed', 'completed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (car_id) REFERENCES cars(id)
);
```

### Eladások Tábla [NEW v2.0]
```sql
CREATE TABLE sales (
  id INT PRIMARY KEY AUTO_INCREMENT,
  car_id INT NOT NULL UNIQUE,
  user_id INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  description TEXT,
  car_condition VARCHAR(50),
  mileage INT,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_is_active (is_active),
  INDEX idx_created_at (created_at)
);
```

### Üzenetek Tábla [NEW v2.0]
```sql
CREATE TABLE messages (
  id INT PRIMARY KEY AUTO_INCREMENT,
  sale_id INT NOT NULL,
  sender_id INT NOT NULL,
  receiver_id INT NOT NULL,
  message TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_sale_id (sale_id),
  INDEX idx_sender_id (sender_id),
  INDEX idx_receiver_id (receiver_id),
  INDEX idx_created_at (created_at)
);
```

---

## 7. API Dokumentáció

### Autentikáció

#### POST /api/auth/register
```
Request:
{
  "name": "Kiss János",
  "email": "kiss@example.com",
  "password": "jelszó123"
}

Response 200:
{
  "token": "eyJhbGc...",
  "user": {
    "id": 1,
    "name": "Kiss János",
    "email": "kiss@example.com",
    "role": "user"
  }
}

Response 409:
{
  "error": "Ez az email már foglalt."
}
```

#### POST /api/auth/login
```
Request:
{
  "email": "kiss@example.com",
  "password": "jelszó123"
}

Response 200:
{
  "token": "eyJhbGc...",
  "user": { ... }
}

Response 401:
{
  "error": "Hibás email vagy jelszó"
}
```

### Autók Kezelése

#### GET /api/cars
```
Response 200:
[
  {
    "id": 1,
    "user_id": 1,
    "make_model": "Toyota Corolla",
    "vin": "12345678901234567",
    "year": 2020
  }
]
```

#### POST /api/cars
```
Request:
{
  "make_model": "Toyota Corolla",
  "vin": "12345678901234567",
  "year": 2020
}

Response 200:
{
  "msg": "Autó hozzáadva",
  "carId": 1
}
```

### Foglalások

#### GET /api/appointments/my
```
Response 200:
[
  {
    "id": 1,
    "user_id": 1,
    "car_id": 1,
    "car_name": "Toyota Corolla",
    "date": "2026-02-01",
    "time": "14:30",
    "service": "Olajcsere",
    "status": "pending",
    "user_name": "Kiss János"
  }
]
```

#### POST /api/appointments
```
Request:
{
  "car_id": 1,
  "date": "2026-02-01",
  "time": "14:30",
  "service": "Olajcsere"
}

Response 200:
{
  "msg": "Foglalás rögzítve",
  "id": 1
}

Response 409:
{
  "error": "Ez az időpont már foglalt. Kérjük, válasszon másik időpontot."
}
```

#### PUT /api/appointments/:id
```
Request:
{
  "date": "2026-02-01",
  "time": "15:30",
  "service": "Fék ellenőrzés"
}

Response 200:
{
  "msg": "Foglalás frissítve"
}
```

#### DELETE /api/appointments/:id
```
Response 200:
{
  "msg": "Foglalás törölve"
}

Response 404:
{
  "error": "Foglalás nem található"
}
```

---

## 8. Alkalmazás Folyamatok

### 8.1 Regisztráció Folyamata
```
1. User kitölti regisztrációs formot (név, email, jelszó)
2. Frontend validálja az inputot
3. POST /api/auth/register
4. Backend:
   - Email duplikáció ellenőrzés
   - Jelszó bcrypt-tel titkosítása
   - User létrehozása DB-ben
   - JWT token generálása
5. Frontend:
   - Token localStorage-ben tárolása
   - Redirect /dashboard-ra
6. Success üzenet megjelenítése
```

### 8.2 Autó Hiba Rögzítés → Ajánlás Folyamata
```
1. User CarDetail-on kitölt hibaleírást
   - Kategória kiválasztása (20 féle)
   - Leírás megírása
   - Sürgősség megadása

2. Frontend: POST /api/cars/:carId/issues
   - Hiba DB-ben tárolása
   - Backend: recommender.js feldolgozza

3. Backend Recommender Logika:
   - category lower-case-é alakítása
   - description lower-case-é alakítása
   - Regex pattern matching a 20 szabályban
   - Első illeszkedő szabály kiválasztása
   - Fallback: általános ajánlás

4. Response: Ajánlott szerviz + magyarázat + költségbecslés

5. Frontend: Redirect /recommendations/:issueId-ra
   - Ajánlás megjelenítése
   - "Vissza az autóhoz" link

6. User lehetőség a foglalásra
```

### 8.3 Szervizidőpont Foglalás Folyamata
```
1. User BookAppointment oldalon:
   - Jármű kiválasztása
   - Dátum (jövőbeli)
   - Idő (HH:MM)
   - Szolgáltatás (22 féle)

2. Frontend Validáció:
   - Mindegyik mező kitöltött?
   - Dátum jövőbeli?
   - Idő helyes formátum?

3. POST /api/appointments
   - Backend: Duplikát dátum/idő ellenőrzés
   - Foglalás tárolása ("pending" státusszal)

4. Foglalás megjelenik /booking oldalon
   - Státusz: Függőben

5. Admin foglalást megerősíti:
   - PUT /api/appointments/:id/status
   - Státusz: Megerősített

6. Automatikus státusz frissítés:
   - 60s-ként backend ellenőrzés
   - Ha start_time + 4 óra < now
   - Status: Completed

7. User PDF exportálhat:
   - Összes foglalás PDF-be
   - Download: foglalasok_[timestamp].pdf
```

### 8.4 Tudásbázis Böngészés
```
1. User Knowledge Hub-ba kattint
2. GET /knowledge-hub
3. Frontend:
   - 24 cikk betöltése
   - 3 kategória filterezés

4. User:
   - Kategória szűrhet (dropdown)
   - Kereshet (szöveg input)
   - Cikk megnyithat (detailed view)

5. Cikk nézet:
   - Cím, Kategória
   - Okok (causes)
   - Tünetek (symptoms)
   - Megoldási tippek (tips)
   - Költségbecslés
```

### 8.5 Autó Eladásra Bocsájtás [NEW v2.0]
```
1. User autó részletei nézet
2. "Eladásra bocsájtás" gomb megjelenik
3. Form megnyílik:
   - Ár (required)
   - Autó állapota (5 fokozat)
   - Futásteljesítmény (opcionális)
   - Leírás (opcionális)

4. Frontend Validáció:
   - Ár > 0
   - Állapot kiválasztott

5. POST /api/sales
   - Backend validáció
   - Car_id UNIQUE ellenőrzés (duplikált hirdetés?)
   - Hirdetés mentése (is_active = TRUE)

6. Success üzenet
   - Hirdetés aktív
   - "Böngészje a hirdetéseket" gomb

7. Hirdetés megjelenik:
   - GET /api/sales/all/active
   - ForSale oldalon grid-ben
```

### 8.6 Hirdetés Böngészése [NEW v2.0]
```
1. User "Eladásra" menüponton kattint
2. ForSale.jsx oldal betölt
3. GET /api/sales/all/active (nyilvános)
4. Frontend:
   - Grid layout (responsive)
   - Autó kártya:
     - Kép
     - Márka/modell, év
     - Ár (formázott)
     - Futásteljesítmény
     - "Részletek" gomb

5. User "Részletek" gombra kattint
6. SaleDetail oldal:
   - /sales/:saleId route
   - Teljes autó adatok
   - Nagy fotó
   - Eladó info

7. Ha bejelentkezett:
   - Üzenetírási form megjelenik
   - Placeholder: "Írj egy üzenetet..."
   - "Küldés" gomb

8. Ha nincs bejelentkezve:
   - "Belépés" gomb az üzenethez
   - Átirányítás /login-ra
```

### 8.7 Üzenetváltás Eladóval [NEW v2.0]
```
1. User bejelentkezett, hirdetés oldal megnyitva
2. Üzenetírási form látható
3. User üzenetet ír (max 2000 char)
4. "Küldés" gombra kattint

5. Frontend Validáció:
   - Üzenet nem üres
   - Hirdetés ID érvényes

6. POST /api/messages
   - Backend validáció:
     - Sender_id ≠ receiver_id (nem saját magának)
     - Sale_id létezik
     - User bejelentkezett
   - Üzenet mentése

7. Frontend:
   - Success üzenet
   - Üzenet megjelenik a listában
   - Sender neve + üzenet + timestamp

8. GET /api/messages/:saleId
   - Összes üzenet betöltése
   - Szekvenciális megjelenítés
   - Auto mark as read (is_read = TRUE)

9. Eladó:
   - Saját hirdetéseinél üzeneteket lát
   - Válaszolhat

10. Mindkét fél:
    - Teljes beszélgetés előzmények
    - Vevő-eladó kommunikáció
    - Offline is mentett
```

---

## 9. Biztonsági Megoldások

### 9.1 Autentikáció
- **JWT Token-based**: Nincs session tárolás szerven
- **Token lejárat**: 7 nap
- **Secure storage**: localStorage (bár httpOnly cookie lenne jobb prod-ban)

### 9.2 Jelszó Kezelés
- **bcrypt hashing**: 12 rounds (salty + iterative)
- **Never plaintext**: Jelszó soha nem tárolódik/naplózódik
- **Comparison**: bcrypt.compare() konstans idő

### 9.3 Jogosultságok
- **Role-based**: User vs Admin
- **Resource ownership**: User csak saját autókat/foglalásokat szerkesztheti
- **Admin endpoints**: /appointments/admin csak admin-nak
- **Sales ownership**: User csak saját autóit bocsáthatja eladásra
- **Message privacy**: Üzenetek csak a felek között

### 9.4 API Biztonsság
- **CORS**: Csak http://localhost:5173-ből
- **JWT middleware**: Minden protected endpoint-en
- **Input validáció**: Frontend + Backend
- **SQL Injection protection**: Paraméteres queries

### 9.5 Adatbázis Biztonsága
- **Prepared statements**: SQL injection elleni védelem
- **Foreign keys**: Referenciális integritás
- **Constraints**: NOT NULL, UNIQUE, DEFAULT
- **Sales UNIQUE constraint**: car_id csak egy aktív hirdetésben

---

## 10. Performance & Optimizáció

### 10.1 Frontend Optimizáció
- **Vite**: Gyors HMR (Hot Module Replacement)
- **Code splitting**: Lazy loading componensek
- **Fetch caching**: Token localStorage-ben

### 10.2 Backend Optimizáció
- **Connection pooling**: MySQL pool 10 connections
- **60s intervallumok**: Foglalás ellenőrzés (nem másodpercenként)
- **Indexek**: DB user_id, email, car_id, appointment date/time

### 10.3 Adatátvitel Optimizáció
- **JSON compression**: Minimális payload
- **API response caching**: Client-side token caching

---

## 11. Jövőbeli Fejlesztési Lehetőségek

### 11.1 Rövidtávú (1-3 hónap)
1. **Email Értesítések**: Foglalás megerősítés, emlékeztetők
2. **SMS Értesítések**: Foglalás 24 óra előtti emlékeztetőt
3. **Karbantartási Emlékeztetők**: "Olajcsere szükséges" értesítés km alapján
4. **Szervizpont Értékelések**: User rating (1-5 csillag)

### 11.2 Középtávú (3-6 hónap)
1. **Költség Nyomonkövetés**: Összes kiadás grafikon
2. **Statisztika Dashboard**: Szerviz gyakoriság, költség trend
3. **Appointment Naptár**: Google Calendar integrációval
4. **Multi-jármű kezelés**: Family plan több autóhoz

### 11.3 Hosszútávú (6-12 hónap)
1. **Mobil App**: React Native iOS/Android verzió
2. **AI Prediktív Karbantartás**: ML alapú ajánlások
3. **Szervizpont Marketplace**: Szervizpontok szűrése, foglalása
4. **Biztosítás Integráció**: Biztosítási igények bejelentése
5. **Szociális Funkciók**: Felhasználók közötti tapasztalat megosztás

---

## 12. Telepítés & Üzemeltetés

### 12.1 Backend Telepítés
```bash
cd autonex-backend
npm install
# .env fájl: JWT_SECRET=your-secret-key
node server.js
# Port: 4000
```

### 12.2 Frontend Telepítés
```bash
cd autonex-frontend
npm install
npm run dev
# Port: 5173
```

### 12.3 Adatbázis Inicializálása
```sql
-- .sql fájl futtatása
SOURCE init-db.sql;
```

### 12.4 Termelési Üzemeltetés
- **Frontend**: Vercel vagy Netlify
- **Backend**: Railway, Render vagy Heroku
- **Adatbázis**: AWS RDS vagy DigitalOcean MySQL
- **Domain**: Cloudflare DNS + SSL certificate

---

## 13. Teszt Esetek

### 13.1 Funkcionális Teszt
```
1. Regisztráció/Login
   ✓ Duplikát email elutasítása
   ✓ Helyes jelszó validáció
   ✓ Token localStorage-ben

2. Autó kezelés
   ✓ Autó hozzáadása
   ✓ Autó szerkesztése
   ✓ Autó törlése
   ✓ Más user autói nem láthatók

3. Hiba rögzítés
   ✓ Kategória-based ajánlás
   ✓ Description-based ajánlás
   ✓ Fallback ajánlás

4. Foglalás
   ✓ Duplikát dátum/idő elutasítása
   ✓ Jövőbeli dátum validáció
   ✓ Automatikus státusz frissítés
   ✓ PDF export
```

---

## 14. Metrikák & KPI

### 14.1 User Engagement
- Daily Active Users (DAU)
- Monthly Active Users (MAU)
- Average Session Duration

### 14.2 Üzleti Metrikák
- Foglalások száma / hó
- Átlagos foglalásérték
- User retention rate (30-nap)

### 14.3 Technikai Metrikák
- API response time: < 200ms
- Frontend load time: < 2s
- Uptime: 99.5%+
- Error rate: < 0.1%

---

---

## 15. Legújabb Funkciók & Fejlesztések (v1.1)

### 15.1 UI/UX Fejlesztések

**Vibráns Szín Séma**
- Multi-color gradients (kék → lila → sötét) a háttérben
- Komponensek kijelölve: navbar, kártyák, gombok, badgek
- Glowing effects és color transitions
- Javított vizuális hierarchia

**Kompakt Lábléc (Footer)**
- Gyors linkek (Dashboard, Foglalás, Profil)
- Támogatási lehetőségek (email, telefon)
- Social media linkek (Facebook, Twitter)
- Flexbox layout a megfelelő elhelyezéshez
- 100% szélesség, fix pozíció a lap alján

**Animációs Fejlesztések**
- Oldal betöltési animációk (slideInUp)
- Kártya shimmer effekt hover-nél
- Gomb ripple effect
- Zökkenőmentes átmenet az összes komponensen

### 15.2 Mobil Responsivitás

**Reszponsív Breakpointok**
```
- 1024px: Tablet eszközök (tábla, kártyák optimálása)
- 768px: Normál mobil (navbar collapse, függőleges layout)
- 480px: Kicsi telefon (mini UI, szűk padding)
```

**Mobile-First Optimizáció**
- Touch-friendly gomb méretek (40px minimum)
- Nagyobb font méretek mobilon
- Függőleges navbar layout <= 768px
- Teljes szélességű gombok és formulák

### 15.3 Teljesítmény Optimalizálások

**API Caching Manager**
```javascript
// 5 perc TTL alapértelmezés
cachedFetch(url, options, 5 * 60 * 1000)
// Cache hit visszaadása
// Csak GET kéréseket cache-el
```

**Form Draft Auto-Save**
```javascript
// Automatikus mentés 2 másodperc után
draftManager.autosave('formId', formData, 2000)
// localStorage-ben vannak a draft-ok
```

**Adatbázis Indexelés**
```sql
-- 15+ index az 4 táblán
-- Gyorsabb SELECT lekérdezések
-- Jobb JOIN teljesítmény
-- Composite indexek az összetett filter-ekhez
```

### 15.4 Akadálymentesítés (Accessibility)

**Keyboard Navigation**
- Tab navigáció az összes interaktív elemhez
- Escape gomb modal bezáráshoz
- Enter gomb formok submitáshoz

**Screen Reader Support**
- ARIA labels (aria-label, aria-describedby)
- Role attributumok (button, group, status)
- Announcement zónák (role="status")
- Skip-to-content link

**Vizuális Akadálymentesítés**
- Focus visible outline (2px kék)
- Color contrast ellenőrzés (WCAG AA)
- :focus-visible pseudo-selector
- Disabled állapot jelzés

### 15.5 Szolgáltatás Ajánlás Felületek

**20 Szerviz Kategória**
1. motor, fék, futómű, elektromos, váltó
2. hűtőrendszer, kipufogó, kerekek, klíma, folyadék
3. üzemanyag, kormányzás, akkumulátor, alternátor
4. indítómotor, világítás, ablaktörlő, belső/külső
5. egyéb

**Intelligens Ajánlási Rendszer**
- Regex pattern matching a leírásban
- Automatikus kategória-detektálás
- Költség becslés (min-max Ft)
- Fallback ajánlás ha nincs match

### 15.6 Haladó Funkciók

**Költség Nyomon Követés (CostTracker)**
- Szolgáltatás költség kalkuláció
- Éves karbantartási költségvetés
- 12 hónapos költség előrejelzés
- Megtakarítási lehetőségek szimuláció

**Emlékeztetők & Értesítések (ReminderService)**
- Automatikus időpont emlékeztetők (24h, 2h előtte)
- Email/SMS/Push értesítés módok
- Felhasználó preferencia kezelés
- Szép HTML emailk sablonokkal

**Szolgáltatási Előzmények (ServiceHistory)**
- Vizuális timeline az összes szervizről
- Szín-kódolt kategóriák
- Költség és állapot követés
- Összegzett statisztikák

### 15.7 Egyéb Fejlesztések

**Service Options Konstansok**
```javascript
// Egy helyen definiált összes 20 szerviz opció
// Könnyű karbantartás és frissítés
// Service groups + priority mapping
```

**Loading States**
- Reusable LoadingSpinner komponens
- Full-screen és inline variációk
- Custom üzenetek
- Smooth spinner animáció

**Sötét/Világos Mód Toggle** (Ready-to-implement)
- ThemeContext a globális témához
- localStorage perzisztencia
- System preference detektálás
- CSS variable-ok a dinamikus stílushoz

---

## 16. Fájl Szerkezet (Aktuális)

```
autonex-frontend/src/
├── components/
│   ├── Footer.jsx           (✨ Új)
│   ├── LoadingSpinner.jsx   (✨ Új)
│   └── ThemeToggle.jsx      (✨ Új - Ready)
├── context/
│   └── ThemeContext.jsx     (✨ Új - Ready)
├── services/
│   ├── CostTracker.js       (✨ Új)
│   └── ReminderService.js   (✨ Új)
├── utils/
│   ├── cacheManager.js      (✨ Új)
│   ├── draftManager.js      (✨ Új)
│   └── accessibility.js     (✨ Új)
├── constants/
│   └── serviceOptions.js    (✨ Új)
├── pages/
│   ├── Dashboard.jsx
│   ├── Booking.jsx
│   ├── CarDetail.jsx
│   ├── Recommendation.jsx
│   └── ... (8 további lap)
├── App.jsx                  (Frissített: flexbox layout)
├── App.css                  (Frissített: vibráns színek)
└── api.js

autonex-backend/
├── database-indexes.sql     (✨ Új)
├── db.js
├── server.js
├── recommender.js           (20 ajánlási szabály)
├── routes/
│   ├── auth.js
│   └── appointments.js
└── package.json
```

---

## 17. Fejlesztői Iránymutatás

### 17.1 Új Funkciók Hozzáadása

**Backend API**
1. Route létrehozása `routes/feature.js`-ben
2. Database model a `db.js`-ben
3. Error handling middleware-vel

**Frontend Komponens**
1. React komponens `src/pages/` vagy `src/components/`
2. API hívás az `api.js`-ből
3. State management: useState, useEffect
4. Loading state hozzáadása LoadingSpinner-rel

**UI/UX Konzisztencia**
- Használj CSS gradient a háttérhez
- Glowing borders az akcentekhez
- Hover effektek minden linkre
- Mobile responsivitás @media-val

### 17.2 Kódminőség

**Frontend**
- React best practices (memoization, lazy loading)
- Async/await a fetch-hez
- Try/catch error handling
- PropTypes vagy TypeScript (opcionális)

**Backend**
- Express middleware réteg
- Input validation
- SQL injection védelem (prepared statements)
- Error response standardizálás

**CSS**
- BEM naming convention
- Gradients helyett hardcoded színek
- CSS variables a theme-khez
- Mobile-first megközelítés

### 17.3 Testing

```bash
# Frontend unit teszt
npm test -- src/components/Footer.test.js

# Backend integrációs teszt
npm test -- tests/api.test.js

# E2E teszt (Playwright)
npm run test:e2e
```

---

## 18. Ismert Problémák & Korlátok

### 18.1 Jelenlegi Korlátok
1. **Single database instance** - Nincs read replica
2. **Synchronous email** - Valós idejű email küldés (ajánlott: async queue)
3. **No session management** - JWT csak token-alapú (nincs refresh token)
4. **Limited search** - Egyszerű LIKE query (ajánlott: full-text search)

### 18.2 Performance Bottlenecks
- Nagy adathalmazoknál az oldal lassú (>10k rekord)
- PDF generálás memória-intenzív
- API rate limiting nincs implementálva

### 18.3 Biztonsági Megjegyzések
- HTTPS szükséges produkción
- CORS policy szigorú legyen
- Rate limiting hozzáadása
- Input sanitization fokozása

---

## 19. Jövőbeni Fejlesztések (Roadmap)

### Phase 2 (Q2 2026)
- [ ] Mobile app (React Native)
- [ ] Valós idejű chat szupplesz (WebSocket)
- [ ] Advanced analytics dashboard
- [ ] Multi-language support (i18n)

### Phase 3 (Q3 2026)
- [ ] Blockchain-alapú szerviz igazolás
- [ ] AI chatbot ügyfélszolgálathoz
- [ ] Video tutorial library
- [ ] Partnership integrációk (insurance, parts)

### Phase 4 (Q4 2026)
- [ ] Machine learning cost prediction
- [ ] Predictive maintenance
- [ ] Integration API harmadik féllel
- [ ] Enterprise version

---

## Zárszó

Az **AutoNex** egy komplex, teljes körű szoftverrendszer, amely modern technológiákat és felhasználó-orientált design-t kombinál. A komprehenzív hibakezelés, intelligens ajánlási rendszer, 24 cikkből álló tudásbázis és felhőséges üzemeltetési lehetőségek együttesen egy professzionális, skálázható megoldást nyújtanak autótulajdonosok és szervizpontok számára.

**Létrehozás dátuma**: 2025. nov 06.
**Státusz**: Aktív fejlesztés
**Szerzők**: Karsai Márk, Nagy Zsolt, Szabó Máté