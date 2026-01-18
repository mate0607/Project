# Autó Képfeltöltés - Setup Guide

## 🎯 Bevezetés
Sikeresen beállítottam az autó képfeltöltési rendszert. Íme az implementáció lépésesei:

## 📋 Megvalósított Komponensek

### 1. **Frontend Komponens** (`src/components/CarImageUpload.jsx`)
- ✅ Fájl kiválasztás UI
- ✅ Kép előnézete (preview)
- ✅ Fájltípus ellenőrzés (JPG, PNG, WebP)
- ✅ Fájlméret validáció (max 5MB)
- ✅ Feltöltés állapot (loading indicator)
- ✅ Hiba kezelés és megjelenítés
- ✅ Feltöltés + Mégsem gombok

### 2. **Backend Routes** (`routes/imageUpload.js`)
- ✅ Multer konfiguráció
- ✅ POST `/api/cars/upload-image` - Kép feltöltés
- ✅ DELETE `/api/cars/:carId/image` - Kép törlés
- ✅ JWT hitelesítés
- ✅ Autó tulajdonjog ellenőrzés
- ✅ Fájl validáció (MIME type, méret)
- ✅ Biztonságos fájl mentés

### 3. **Szerver Integrációs** (`server.js`)
- ✅ Image upload routes importálása és regisztrálása
- ✅ `/uploads` statikus könyvtár konfigurálása
- ✅ Car detail endpoint frissítése (image_url mező)

### 4. **Felhasználó Felület** (`pages/CarDetail.jsx`)
- ✅ CarImageUpload komponens integrálása
- ✅ Feltöltött kép megjelenítése
- ✅ onImageUploaded callback kezelése

### 5. **Adatbázis Migráció** (`migrations/001_add_image_support.sql`)
- ✅ `image_url` oszlop hozzáadása
- ✅ `image_uploaded_at` oszlop hozzáadása
- ✅ Index létrehozása gyorsabb lekérdezésekhez

---

## 🔧 Telepítési Lépések

### Lépés 1: Multer Függőség Telepítése
Backend könyvtárban (`autonex-backend`):
```bash
npm install multer
```

### Lépés 2: Adatbázis Migráció
A `add-image-support.sql` fájl tartalmát futtasd az adatbázis felügyelő eszközödben (phpMyAdmin, MySQL Workbench, stb.):

```sql
ALTER TABLE cars ADD COLUMN image_url VARCHAR(255) DEFAULT NULL AFTER year;
ALTER TABLE cars ADD COLUMN image_uploaded_at TIMESTAMP DEFAULT NULL AFTER image_url;
CREATE INDEX idx_cars_image ON cars(image_url);
```

### Lépés 3: Upload Könyvtár Létrehozása
Backend könyvtárban hozd létre az `/uploads/cars` könyvtárat:

**Windows (PowerShell vagy Command Prompt):**
```bash
mkdir uploads\cars
```

**Linux/Mac:**
```bash
mkdir -p uploads/cars
```

### Lépés 4: Backend Indítása
```bash
cd autonex-backend
npm run dev
```

### Lépés 5: Frontend Indítása
Új terminálon:
```bash
cd autonex-frontend
npm run dev
```

---

## 🧪 Tesztelés

1. **Bejelentkezés**: Navigálj a Login oldalra
2. **Autó kiválasztása**: Válassz egy autót az adatbázisodból
3. **Kép feltöltés**: 
   - Kattints a "Feltöltés" gombra
   - Válassz egy JPG, PNG vagy WebP képet
   - Max 5MB méretű képet tölthetsz fel
   - Látni fogod az előnézetet
4. **Mentés**: Kattints a "Feltöltés" gombra
5. **Ellenőrzés**: Az autó részleteit frissítve, a kép megjelenik

---

## 📁 Fájl Szerkezet

```
autonex-backend/
├── routes/
│   ├── imageUpload.js        ← Új upload API endpointok
│   └── auth.js               ← JWT hitelesítés
├── middleware/
│   └── auth.js               ← Token ellenőrzés
├── migrations/
│   └── 001_add_image_support.sql  ← Adatbázis migráció
├── uploads/
│   └── cars/                 ← Képek tárolása (létre kell hozni!)
├── server.js                 ← Frissítve: upload routes és statikus könyvtár
└── package.json              ← Új: multer függőség

autonex-frontend/
├── src/
│   ├── components/
│   │   └── CarImageUpload.jsx  ← Upload UI komponens
│   └── pages/
│       └── CarDetail.jsx       ← Frissítve: upload integrálva
```

---

## 🔒 Biztonsági Funkciók

✅ **JWT Hitelesítés**: Csak bejelentkezett felhasználók tölthetenek fel képet
✅ **Tulajdonjog Ellenőrzés**: Az autó tulajdonosa csak a saját autó képét módosíthatja
✅ **Fájltípus Validáció**: 
   - Frontend: HTML5 `accept` attribútum
   - Backend: MIME type ellenőrzés (image/jpeg, image/png, image/webp)
✅ **Fájlméret Limit**: Multer 5MB-os limit
✅ **Biztonságos Fájl Mentés**: 
   - Egyedi nevek: `carId_timestamp.ext`
   - Dedikált könyvtár: `/uploads/cars/`
   - Fájl kiterjesztés ellenőrzés

---

## 🎨 Felhasználói Felület

**CarImageUpload Komponens:**
- 📸 Fájl kiválasztó (drag-and-drop stílus)
- 👁️ Kép előnézete (max 200x200px)
- 📤 Feltöltés gomb
- ❌ Mégsem gomb
- ⚠️ Hiba üzenetek
- ⏳ Betöltés indikátor

**CarDetail Integrációja:**
```jsx
<CarImageUpload 
  carId={carId} 
  onImageUploaded={(imageUrl) => {
    setCar({ ...car, image_url: imageUrl });
  }} 
/>
```

---

## 🐛 Hibaelhárítás

### "Multer not found" hiba
**Megoldás**: Futtasd le: `npm install multer`

### "ENOENT: no such file or directory" (uploads/cars)
**Megoldás**: Hozd létre az `/uploads/cars` könyvtárat

### "Unauthorized" hiba feltöltéskor
**Megoldás**: Ellenőrizd, hogy helyesen van-e küldve a JWT token a fejlécekben

### "File type not allowed" hiba
**Megoldás**: Csak JPG, PNG vagy WebP fájlokat tölthetsz fel

### 413 Payload Too Large
**Megoldás**: A fájl nagyobb, mint 5MB. Kisebb képet használj.

---

## 🔄 API Endpointok

### Feltöltés
```http
POST /api/cars/upload-image
Authorization: Bearer <JWT_TOKEN>
Content-Type: multipart/form-data

Body:
- carId: (szám)
- image: (fájl)

Response:
{
  "imageUrl": "/uploads/cars/carId_timestamp.jpg"
}
```

### Törlés
```http
DELETE /api/cars/:carId/image
Authorization: Bearer <JWT_TOKEN>

Response:
{
  "msg": "Kép törölve"
}
```

---

## 📊 Adatbázis Séma

```sql
ALTER TABLE cars ADD COLUMN image_url VARCHAR(255) DEFAULT NULL;
ALTER TABLE cars ADD COLUMN image_uploaded_at TIMESTAMP DEFAULT NULL;
CREATE INDEX idx_cars_image ON cars(image_url);
```

**Mezők:**
- `image_url`: Képfájl relatív útvonala (`/uploads/cars/carId_timestamp.ext`)
- `image_uploaded_at`: Feltöltés időbélyege (automatikus, UTC)

---

## ✅ Telepítés Ellenőrzés Listája

- [ ] Multer telepítve: `npm install multer`
- [ ] Adatbázis migráció futtatva
- [ ] `/uploads/cars` könyvtár létrehozva
- [ ] `server.js` frissítve (imageUpload routes + statikus könyvtár)
- [ ] `CarDetail.jsx` frissítve (CarImageUpload import és render)
- [ ] Backend indítva: `npm run dev`
- [ ] Frontend indítva: `npm run dev`
- [ ] Sikeres bejelentkezés
- [ ] Autó kiválasztása
- [ ] Kép feltöltésének tesztelése
- [ ] Kép megjelenésének ellenőrzése a CarDetail-ben

---

## 🚀 Következő Lépések (Opcionális)

1. **Több Kép Támogatása**: Lépj fel képgaléria-ra
2. **Kép Optimalizáció**: Képet tömörítés feltöltéskor
3. **Képszerkesztés**: Vágás/forgatás funkciók
4. **CDN Integráció**: AWS S3 vagy hasonló használata
5. **Képteremtés**: Miniatűrök és teljes méretű verziók

---

## 📞 Támogatás

Ha problémáid vannak:
1. Ellenőrizd a böngésző konzolt (DevTools)
2. Ellenőrizd a backend logokat
3. Győződj meg, hogy az összes fájl megtalálható
4. Tesztelj a Postmanban vagy cURLvel

Sikeresen beállítottad az autó képfeltöltési rendszert! 🎉
