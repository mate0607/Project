# Autonex

## Követelmények

- PHP >= 8.2
- Composer
- Node.js + npm

## Telepítés

```bash
# 1. Függőségek telepítése és projekt beállítása (env, kulcs, migráció, Vite build)
composer setup

# 2. Adatbázis feltöltése tesztadatokkal (opcionális)
php artisan db:seed
```

> Az alapértelmezett adatbázis **SQLite** – nem kell MySQL-t konfigurálni.

## Futtatás

```bash
composer dev
```

Ez egyszerre elindítja:
- **Laravel szerver** – `http://localhost:8000`
- **Vite** dev szerver (hot reload)
- **Queue listener** (email küldéshez)
- **Pail** (log figyelés)

## Tesztek

```bash
composer test
```

## Bejelentkezés (seeder adatok)

| Szerepkör | Email             | Jelszó     |
|-----------|-------------------|------------|
| Admin     | admin@admin.com   | admin123   |
| User      | *(seeder email)*  | password   |

## Email setup (opcionális)

Alapértelmezetten az emailek **log**-ba íródnak (`storage/logs/laravel.log`), nem kell SMTP-t konfigurálni.

Ha valódi emaileket szeretnél küldeni, állítsd át a `.env`-ben:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="Autonex"
```