## URL Shortener Assessment

This project implements a multi-tenant URL shortener built with Laravel 12 and SQLite. The assignment constraints listed in the prompt are all enforced: a single company namespace, role-based permissions, invitation rules, private short links, and the requested test coverage.

### Requirements

- PHP 8.2+
- Composer
- SQLite (already bundled with PHP)

### Getting Started

```bash
composer install --ignore-platform-req=ext-fileinfo
cp .env.example .env
# create the SQLite database file if it does not exist yet
php -r "touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

The Super Admin account seeded by `Database\Seeders\SuperAdminSeeder`:

- Email: `superadmin@example.com`
- Password: `password`

### Running Tests

```bash
php artisan test
```

Feature tests cover URL creation permissions, visibility rules for each role, and private redirect behaviour.

### Architecture Notes

- Roles are defined in `app/Enums/Role.php`.
- `UrlController` enforces creation restrictions and listing scopes per role.
- `ShortUrlController` only resolves links for authenticated users who satisfy the role rules; anonymous traffic is redirected to `/login`.
- `DashboardController` renders a simple HTML dashboard tailored to the user's role.
- SQLite is used by default; switch to MySQL by updating the `DB_*` values in `.env`.

### Acceptable AI Usage Disclosure

This solution was authored with personal reasoning and implementation effort. GPT-5-Codex (Preview) was used for syntax recall and refactoring suggestions while developing controllers, tests, and documentation.
