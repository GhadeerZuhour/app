# Tazreem Demo Runbook

This branch adds a small, repeatable demo setup for Tazreem.

## 1. Prepare the application

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

Configure the central database and tenancy database connection in `.env` before creating a tenant.

## 2. Create a tenant

Start the application and sign in as an admin. From the admin tenant screen create one active tenant with a local domain such as:

```text
demo-company.localhost
```

The existing tenant creation flow creates the tenant database and runs tenant migrations.

## 3. Add demo data

After at least one tenant exists, run:

```bash
php artisan db:seed --class=DemoSeeder
```

The seeder uses the first tenant and adds:

- ILS currency
- Demo bank
- Main Cash account
- Operating Bank Account
- Opening balances for the current month
- Cash transactions
- Bank transaction
- Three scheduled customer checks

The seeder is idempotent enough for repeated demo preparation and does not run automatically from `DatabaseSeeder`.

## 4. Demo login and flow

Use the owner account for the demo tenant, then browse to the tenant domain.

Recommended presentation flow:

1. **Dashboard** — explain that Tazreem gives a business a quick view of monthly activity and upcoming checks.
2. **Accounts** — show cash and bank accounts with their currencies.
3. **Transactions** — show cash, bank, and check-based entries in one place.
4. **Add transaction** — record a simple cash or bank entry.
5. **Checks** — demonstrate generating multiple scheduled checks from one entry.
6. **Dashboard again** — show how the overview reflects operational financial activity.

## Demo story

> A small business currently tracks cash, bank transfers, and post-dated checks in spreadsheets and WhatsApp messages. Tazreem centralizes these movements per company, keeps each tenant separated, and gives the owner a clearer view of what happened this month and what payments are coming next.

## Notes

- Demo data is intended for development/presentation environments only.
- Do not run the demo seeder against production data.
- The tenant dashboard is located at `resources/views/tenant-dashboard.blade.php`.
