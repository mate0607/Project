# Autonex

## Mi szükséges az indításhoz

- PHP 8.2 vagy újabb
- Composer
- Node.js és npm
- MySQL vagy MariaDB
- Git (ajánlott)

## Gyors telepítés és indítás

1. Projekt letöltése és belépés a mappába.

2. Függőségek telepítése.

	composer install
	npm install

3. Környezeti fájl létrehozása.

	Linux/macOS:
	cp .env.example .env

	Windows PowerShell:
	Copy-Item .env.example .env

4. Kötelező .env beállítások megadása.

	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=autonex
	DB_USERNAME=root
	DB_PASSWORD=

5. Alkalmazáskulcs generálása.

	php artisan key:generate

6. Adatbázis táblák és seed adatok létrehozása.

	php artisan migrate --seed

7. Storage link létrehozása (képekhez/fájlokhoz).

	php artisan storage:link

8. Projekt indítása két külön terminálban.

	1. terminál:
	php artisan serve

	2. terminál:
	npm run dev

9. Ha a projekt queued feladatokat használ (pl. e-mail), indítsd el a queue workert is egy 3. terminálban.

	php artisan queue:work

Az oldal alapértelmezett címe: http://localhost:8000

## Egyparancsos fejlesztői indítás

Ha minden szükséges csomag telepítve van, futtathatod ezt is:

composer run dev

Ez egyszerre indítja a Laravel szervert, a queue figyelőt, a log figyelőt és a Vite-ot.

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
