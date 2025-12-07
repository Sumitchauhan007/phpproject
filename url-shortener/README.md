## URL Shortener

Multi-tenant URL shortener built with Laravel 12, SQLite, and Vite-powered React views. The app supports multiple companies, role-based permissions, invitation flows, and public short-link redirects. 

### Requirements

- PHP 8.2 or newer
- Composer
- Node.js 18+ and npm (for the Vite frontend)
- SQLite (bundled with PHP)

### Quick Start

Clone the repository and install dependencies:

```bash
git clone https://github.com/Sumitchauhan007/phpproject.git
cd phpproject/url-shortener
composer install --ignore-platform-req=ext-fileinfo
npm install
```

Prepare the environment and database:

```bash
cp .env.example .env
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate --force
php artisan db:seed
```

Build assets or run the dev server:

- Development (hot reload): `npm run dev`
- Production build: `npm run build`

Run the Laravel HTTP server (in a separate terminal):

```bash
php artisan serve
```

Visit http://127.0.0.1:8000 and log in with a seeded account.

### Seeded Accounts

- Super Admin: `superadmin@example.com` / `password`
- Admin: `admin@example.com` / `password`
- Sales: `sales@example.com` / `password`
- Member: `member@example.com` / `password`

The seeder also creates a secondary company (`Globex Labs`) and a pending member invitation (`new.member@globexlabs.example`).

### Running Tests

```bash
php artisan test
```

This suite verifies:

- Admin and Member short URL creation
- Super Admin cannot create short URLs
- Per-role URL listing scopes
- Public short-link redirects
- Invitation permissions for Super Admin and Admin roles

### Architecture Notes

- Roles live in `app/Enums/Role.php` and gate controller policies.
- `UrlController` scopes listings per role and company.
- `ShortUrlController` resolves slugs publicly while tracking visits.
- Multi-company data separation uses `company_id` on `urls`, `users`, and `invitations`.
- Switch to MySQL by updating `.env` and migrating the schema (`php artisan migrate`).

### Useful Composer Scripts

- `composer run setup` – end-to-end environment bootstrap
- `composer run dev` – launch local dev stack (Laravel, queue listener, logs, Vite)
- `composer run test` – clear config cache and execute the test suite

### License

MIT



