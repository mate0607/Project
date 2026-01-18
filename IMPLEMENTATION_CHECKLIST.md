# Autó Képfeltöltés - Teljes Implementáció

## Mit Kaptál?

### **Frontend**
- ✅ `CarImageUpload.jsx` - Teljes UI komponens előnézettel
- ✅ CarDetail.jsx integrálása - Kép megjelenítése és feltöltés
- ✅ JWT hitelesítés az upload során

### **Backend**
- ✅ `imageUpload.js` - Multer routes (upload + delete)
- ✅ `auth.js` middleware - JWT token ellenőrzés
- ✅ File validation (MIME type, size)
- ✅ Secure storage (unique filenames)
- ✅ Ownership verification

### **Adatbázis**
- ✅ Migration file - `image_url` és `image_uploaded_at` oszlopok
- ✅ Indexing - `idx_cars_image` gyors lekérdezéshez

### **Dokumentáció**
- ✅ IMAGE_UPLOAD_SETUP.md - Teljes setup guide
- ✅ IMAGE_UPLOAD_SUMMARY.md - Technikai összegzés
- ✅ QUICK_START.md - Gyors indulás

---

## 🔧 Telepítés Checklist

### **1. Multer NPM Csomag** (30 sec)
```bash
cd c:\Users\markk\OneDrive\Asztali gép\autonex(VADKAN)\autonex-backend
npm install multer
```
- [ ] Multer telepítve (check: `npm ls multer`)
- [ ] package.json frissült

### **2. Adatbázis Migráció** (1 min)
Fájl: `autonex-backend/migrations/001_add_image_support.sql`

```sql
ALTER TABLE cars ADD COLUMN image_url VARCHAR(255) DEFAULT NULL;
ALTER TABLE cars ADD COLUMN image_uploaded_at TIMESTAMP DEFAULT NULL;
CREATE INDEX idx_cars_image ON cars(image_url);
```

Lehetőségek:
- [ ] phpMyAdmin - File import
- [ ] MySQL Workbench - SQL tab, paste & execute
- [ ] Command line: `mysql -u root -p autonex < migrations/001_add_image_support.sql`
- [ ] Verify: `DESCRIBE cars;` - új oszlopok láthatók?

### **3. Upload Könyvtár Létrehozása** (30 sec)

**Windows PowerShell/CMD:**
```bash
mkdir "c:\Users\markk\OneDrive\Asztali gép\autonex(VADKAN)\autonex-backend\uploads\cars"
```
- [ ] Könyvtár létezik

**Linux/Mac:**
```bash
mkdir -p uploads/cars
```

### **4. Backend Indítása** (1 min)
```bash
cd autonex-backend
npm run dev
```
- [ ] "Backend: http://localhost:4000" üzenet látható
- [ ] Nincs "multer" error

### **5. Frontend Indítása** (1 min)
Új terminal:
```bash
cd autonex-frontend
npm run dev
```
- [ ] "VITE v..." és "Local: http://localhost:5173" üzenet

---

## Funkcionális Teszt

### **Test 1: Bejelentkezés**
- [ ] Menj: http://localhost:5173/login
- [ ] Email: `test@example.com`
- [ ] Jelszó: `password123`
- [ ] Kattints "Login"
- [ ] Dashboard nyílik meg

### **Test 2: Autó Kiválasztása**
- [ ] Dashboard oldalon vagy a Navigation-ban válassz egy autót
- [ ] CarDetail oldal megnyílik
- [ ] Látod az autó adatait (make_model, VIN, year)

### **Test 3: Képfeltöltés**
- [ ] Görgesson le az "Autó képe feltöltése" szekciójához
- [ ] Kattints a "Fájl kiválasztása" gombra
- [ ] Válassz egy JPG vagy PNG képet (max 5MB)
- [ ] Látod az előnézetet
- [ ] Kattints "Feltöltés"
- [ ] Betöltés indikátor jelenik meg
- [ ] Zöld success üzenet vagy alert

### **Test 4: Kép Megjelenítése**
- [ ] Az oldal tetején a feltöltött kép látható
- [ ] Frissítsd az oldalt (F5)
- [ ] A kép még mindig ott van (adatbázisból betöltött)

### **Test 5: Hibaesetek**
- [ ] Próbálj fel MIDI/MP3 fájlt (error: "Csak képfájlok...")
- [ ] Próbálj fel nagy (>5MB) fájlt (error: "A fájl mérete...")
- [ ] Próbálj egy másik autóhoz feltölteni (403 Forbidden)

---

## Fájlok Ellenőrzése

### **Backend Fájlok**

**server.js** - Ellenőrizd ezeket a sorokat:
```javascript
const imageUploadRoutes = require("./routes/imageUpload");
app.use('/uploads', express.static('uploads'));
app.use("/api/cars", imageUploadRoutes);
```

**routes/imageUpload.js** - Létezik és teljes?
```bash
ls -l routes/imageUpload.js
# Mérete: ~154 sorok
```

**middleware/auth.js** - Létezik?
```bash
ls -l middleware/auth.js
# Export: { verifyToken }
```

### **Frontend Fájlok**

**src/components/CarImageUpload.jsx** - Létezik?
```bash
ls -l src/components/CarImageUpload.jsx
# Mérete: ~189 sorok
```

**src/pages/CarDetail.jsx** - Frissítve?
```jsx
// Ezeket keress meg:
import CarImageUpload from "../components/CarImageUpload.jsx";
<CarImageUpload carId={carId} onImageUploaded={...} />
```

### **Adatbázis**

**cars table** - Új oszlopok:
```sql
DESCRIBE cars;
-- Keresd: image_url, image_uploaded_at
```

---

## 🔍 Debugging Útmutató

### **"Cannot find module 'multer'"**
```bash
cd autonex-backend
npm install multer
npm ls multer  # Ellenőrzés
```

### **"ENOENT: no such file or directory" (uploads/cars)**
```bash
mkdir -p uploads/cars  # Könyvtár létrehozása
ls uploads/cars         # Ellenőrzés
```

### **"Failed to upload image"** (Frontend)
1. Nyisd meg a böngésző konzolt (F12)
2. Nézd meg a Network tab-ot
3. Keresd az upload request-et
4. Mit ad vissza? (status code, response body)

### **Adatbázis hibák**
```sql
-- Ellenőrizd a struktúrát
DESCRIBE cars;
-- Ellenőrizd az adatokat
SELECT * FROM cars LIMIT 1;
```

---

## Teljesítmény & Storage

- **Frontend bundle size**: ~1.2KB extra
- **Backend code size**: ~154 lines
- **Per image**: ~2-500KB (tipikus 100-300KB)
- **Upload speed**: 5MB ~ 2 sec (szokásos internet-en)
- **DB space**: Neglible (string paths)

---

## Biztonsági Ellenőrzés

- [ ] JWT token validálva upload-nál
- [ ] Autó tulajdonjoga ellenőrzött
- [ ] Fájl MIME type validálva
- [ ] 5MB size limit érvényes
- [ ] Unique filenames (ütközés lehetetlen)
- [ ] File cleanup on error

---

## Előrehaladás Nyomonkövetés

| Feladat | Status | Ellenőrzés |
|---------|--------|-----------|
| Multer telepítés | ✅ | `npm ls multer` |
| DB migráció | ⏳ | SQL futtassa |
| Könyvtár létre | ⏳ | `ls uploads/cars` |
| Backend indít | ⏳ | 4000-es port |
| Frontend indít | ⏳ | 5173-as port |
| Bejelentkezés | ⏳ | 200 OK |
| Autó betöltés | ⏳ | CarDetail látható |
| Képfeltöltés | ⏳ | Success alert |
| Kép megjelenés | ⏳ | Kép látható |

---

## Hasznos Parancsok

**Backend status:**
```bash
cd autonex-backend
npm list          # Függőségek
node -v           # Node verzió
npm run dev       # Indítás
```

**Könyvtár ellenőrzés:**
```bash
dir uploads\cars        # Windows
ls -la uploads/cars     # Linux/Mac
du -sh uploads/cars     # Storage size
```

**Adatbázis:**
```sql
SELECT COUNT(*) FROM cars WHERE image_url IS NOT NULL;
SELECT image_url FROM cars LIMIT 5;
```

---

## Megoldás Fa

```
Képfeltöltés nem működik?
├─ Frontend error?
│  ├─ File validation error? → Ellenőrizz MIME/size-t
│  ├─ Network error? → F12 Network tab
│  └─ Token error? → Bejelentkezel?
├─ Backend error?
│  ├─ 404? → server.js imageUpload routes?
│  ├─ 401? → middleware/auth.js érvényes?
│  ├─ 500? → multer telepítve? mkdir uploads/cars?
│  └─ Multer error? → npm install multer
└─ Database error?
   ├─ Oszlopok? → DESCRIBE cars;
   └─ Jogosultság? → user_id = token id?
```

---

## Támogatási Pontok

1. **Dokumentáció**: IMAGE_UPLOAD_SETUP.md
2. **Gyors Start**: QUICK_START.md
3. **Technikai Info**: IMAGE_UPLOAD_SUMMARY.md
4. **Kód**: Minden fájl inline dokumentációval

---

## Végső Ellenőrzés

```
Válaszd: Ha "IGEN" minden sorra, akkor kész vagy!

1. "Multer telepítve?" (npm ls multer)                        [ ] IGEN
2. "DB migráció futott?" (DESCRIBE cars - image_url létezik) [ ] IGEN
3. "/uploads/cars könyvtár létezik?" (ls uploads/cars)        [ ] IGEN
4. "Backend indul?" (npm run dev - port 4000)                 [ ] IGEN
5. "Frontend indul?" (npm run dev - port 5173)                [ ] IGEN
6. "Bejelentkezés működik?"                                    [ ] IGEN
7. "CarDetail oldal megnyílik?"                               [ ] IGEN
8. "CarImageUpload komponens látható?"                         [ ] IGEN
9. "Kép feltöltés működik?" (alert: "Sikeresen feltöltve")   [ ] IGEN
10. "Kép megjelenik az oldalon?"                              [ ] IGEN

HA MIND IGEN: 🎉 GRATULÁLOK! Kész az implementáció!
```

---

## Következő Lépések (Opcionális)

- [ ] Több kép per autó support
- [ ] Kép galeria/carousel
- [ ] Drag & drop upload
- [ ] Kép szerkesztés (crop, rotate)
- [ ] Kép tömörítés (Sharp lib)
- [ ] Thumbnail generálás
- [ ] AWS S3 integráció

---

**Sikeresen implementálva! 🎉**

Az AutoNex alkalmazás most már teljes képfeltöltési támogatással rendelkezik.
