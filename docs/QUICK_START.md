# 🚀 Gyors Start Útmutató - Képfeltöltés

## 3 Perces Telepítés

### 1️⃣ Backend Függőség (30 sec)
```bash
cd autonex-backend
npm install multer
```

### 2️⃣ Adatbázis Migráció (1 min)
Nyisd meg a `autonex-backend/migrations/001_add_image_support.sql` fájlt, és futtasd le az SQL parancsokat az adatbázis felügyelőben.

Vagy a MySQL CLI-ban:
```bash
mysql -u root -p autonex < migrations/001_add_image_support.sql
```

### 3️⃣ Könyvtár Létrehozása (30 sec)
**Windows:**
```powershell
mkdir uploads\cars
```

**Linux/Mac:**
```bash
mkdir -p uploads/cars
```

### 4️⃣ Indítás (30 sec)
**Terminal 1 - Backend:**
```bash
cd autonex-backend
npm run dev
```

**Terminal 2 - Frontend:**
```bash
cd autonex-frontend
npm run dev
```

✅ **Kész!** Az alkalmazás fut a [http://localhost:5173](http://localhost:5173) címen.

---

##  Képfeltöltés Tesztelése

1. Lépj be a [Login oldalra](http://localhost:5173)
2. Jelentkezz be a felhasználóddal
3. Válassz egy autót az adatbázisodból
4. A CarDetail oldal megnyílik
5. Görgesson le a "Autó képe feltöltése" szekciót
6. Válassz egy JPG/PNG/WebP képet (max 5MB)
7. Kattints "Feltöltés" gombra
8. ✅ Kép megjelenik az oldal tetején

---

## Probléma? Ellenőrizd:

| ❌ | ✅ |
|----|----|
| Multer nincs telepítve | `npm install multer` |
| Nincs `/uploads/cars` | `mkdir -p uploads/cars` |
| DB migration nem futott | Futtasd le a `.sql` fájlt |
| Backend nem indul | Ellenőrizd a portot (4000) |
| Frontend nem indul | Ellenőrizd a portot (5173) |

---

## Mit Hozza az Implementáció?

✅ Frontend komponens (`CarImageUpload.jsx`)
✅ Backend API (`routes/imageUpload.js`)
✅ Adatbázis schema (`migrations/001_add_image_support.sql`)
✅ Integrálva a CarDetail oldalba
✅ Teljes biztonsági ellenőrzés (JWT, ownership)
✅ Fájlvalidáció (típus, méret)

---

## Mi történik a feltöltéskor?

1. Felhasználó fájlt választ
2. Frontend validálja (típus, méret)
3. Képet küld a `/api/cars/upload-image` endpointra JWT-vel
4. Backend:
   - Ellenőrzi a JWT tokent
   - Ellenőrzi az autó tulajdonjogát
   - Validálja a fájl típusát
   - Menti az `/uploads/cars/` mappába
   - Frissíti az adatbázist
5. Frontend megjeleníti az eredményt

---

## Tippek

- **JPG** használj a legjobb kompromisszumért (kis méret, jó minőség)
- **5MB limit** miatt nagyobb képeket méretezz le előbb
- **Upload könyvtárat** biztosan létrehozd, különben error lesz
- **Multer telepítés** az utolsó lépés, amit kell elvégezni

---

## Ha Nem Működik

1. **Böngésző konzol** nyiss (F12) és ellenőrizd az error üzeneteket
2. **Backend logok** nézzd meg (npm run dev)
3. **Fájlok létezésének** ellenőrzése:
   - `/uploads/cars/` mappa létezik-e?
   - `middleware/auth.js` létezik-e?
   - `routes/imageUpload.js` létezik-e?

---

**Ready to go!**
