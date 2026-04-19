# Autonex

## Követelmények

- PHP >= 8.2
- Composer
- Node.js + npm
- MySQL / MariaDB

## Telepítés

```bash
# 1. Függőségek telepítése
composer install
npm install

# 2. Környezeti fájl beállítása
cp .env.example .env
php artisan key:generate

# 3. Adatbázis beállítása (.env fájlban)
# DB_CONNECTION=mysql
# DB_DATABASE=autonex
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Migrációk és tesztadatok
php artisan migrate --seed

# 5. Storage link (képfeltöltésekhez)
php artisan storage:link
```

## Futtatás

```bash
php artisan serve
npm run dev
```

Az alkalmazás elérhető: `http://localhost:8000`

## Funkciók

- **Gépjármű-nyilvántartás** – saját autók CRUD kezelése
- **Időpont-foglalás** – szervizidőpont létrehozás, ütközésvizsgálat, e-mail visszaigazolás
- **Hibajegy-kezelés** – hibabejelentések járművenként
- **Autópiactér** – többképes hirdetések, szűrés
- **Üzenetrendszer** – autó-alapú inline chat az adminnal (autó- és hirdetésoldalon), AJAX betöltéssel, automatikus értesítéssel
- **Értesítések** – rendszerszintű + üzenet értesítések (harang ikon)
- **Admin felület** – naptár, szervizfolyamat, üzenetkezelés (piros badge), statisztikák

## Tesztek

```bash
php artisan test
```

## Bejelentkezés (seeder adatok)

| Szerepkör | Email             | Jelszó     |
|-----------|-------------------|------------|
| Admin     | admin@admin.com   | admin123   |
| User      | *(seeder email)*  | password   |
