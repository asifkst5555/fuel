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

1. Create a new MySQL database and database user in cPanel.
2. Upload the project files to your account, typically outside `public_html`.
3. Point your domain or subdomain document root to the Laravel `public` directory.
4. In `.env`, set:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_database
DB_USERNAME=your_cpanel_user
DB_PASSWORD=your_secure_password
```

5. In cPanel Terminal or SSH, run:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

6. Ensure these directories are writable:

- `storage/`
- `bootstrap/cache/`

7. If your hosting does not allow the domain root to point directly to `public`, move the contents of `public/` into `public_html/` and update `index.php` paths to the Laravel app location.

## Notes

- The UI uses Bootstrap CDN, so the core pages work without compiling frontend assets.
- If you want to use the default Laravel Vite pipeline later, upgrade Node.js to `20.19+` and run `npm install && npm run build`.
