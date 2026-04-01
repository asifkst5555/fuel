# Fuel Monitoring Web Application

A Laravel-based fuel monitoring system for tracking station-level octane and diesel availability with a public live-status page and a protected admin dashboard.

## Features

- Public station listing with live fuel availability
- Auto-refreshing status cards every 15 seconds
- Admin login using Laravel authentication
- Station create, edit, delete, and inline fuel-status updates
- One-to-one `Station` to `FuelStatus` relationship
- Search/filter support on the public page and admin panel
- JSON API endpoint for mobile or third-party clients
- Demo seed data and admin user

## Local Setup

1. Install PHP, Composer, MySQL, and Node.js 20.19+.
2. From the project root, run:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. Update `.env` with your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuel_monitor
DB_USERNAME=root
DB_PASSWORD=
```

4. Run migrations and seed demo data:

```bash
php artisan migrate:fresh --seed
```

5. Start the application:

```bash
php artisan serve
```

6. Open:

- Public site: `http://127.0.0.1:8000`
- Admin login: `http://127.0.0.1:8000/login`

## Demo Admin Account

- Email: `admin@fuelmonitor.test`
- Password: `password`

## API Endpoint

- `GET /api/stations`

## cPanel Deployment

The safest shared-hosting setup for this project is:

1. Create the repository in cPanel Git Version Control outside `public_html`, for example `/home/USERNAME/repositories/fuel`.
2. Point the domain or subdomain document root to that repository's `public` directory:

```text
/home/USERNAME/repositories/fuel/public
```

3. In cPanel MultiPHP Manager, set the domain to PHP 8.3 or newer.
4. In cPanel, create a MySQL database and database user.
5. Create the production `.env` file in the repository root with values like:

```env
APP_NAME="Fuel Monitor"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://fuel.hathazari.info

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpanel_database_name
DB_USERNAME=cpanel_database_user
DB_PASSWORD=your_secure_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

6. In cPanel Terminal or SSH, run this once before the first deploy:

```bash
cd /home/USERNAME/repositories/fuel
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
```

7. Commit and push this repository. The included `.cpanel.yml` will call `deploy/cpanel-deploy.sh` whenever you deploy through cPanel Git Version Control.
8. After each push, open cPanel Git Version Control and use `Pull or Deploy`. The deploy script will run:

```bash
composer install --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

9. Ensure these paths are writable by PHP:

- `storage/`
- `bootstrap/cache/`

### Why 500 Happens On cPanel

The most common causes for this app are:

- The domain is not pointing to the Laravel `public` directory.
- The cPanel site is running PHP 8.2 or older while this project requires PHP 8.3+.
- `vendor/` was never installed on the server.
- `.env` is missing or `APP_KEY` was never generated.
- MySQL credentials in `.env` are wrong.
- `storage/` or `bootstrap/cache/` is not writable.
- Migrations were not run after deployment.

### Quick Recovery Checklist

If the site still returns HTTP 500, run:

```bash
cd /home/USERNAME/repositories/fuel
php artisan optimize:clear
php artisan migrate --force
tail -n 100 storage/logs/laravel.log
```

Check the latest error in `storage/logs/laravel.log`. On shared hosting, that file usually tells you the exact failure immediately.

## Notes

- The UI uses Bootstrap CDN, so the core pages work without compiling frontend assets.
- If you want to use the default Laravel Vite pipeline later, upgrade Node.js to `20.19+` and run `npm install && npm run build`.
