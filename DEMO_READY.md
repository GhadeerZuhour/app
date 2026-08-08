# Tazreem Demo Snapshot

This branch is the frozen demo snapshot prepared for local smoke testing.

## Branch

```bash
git checkout demo
git pull origin demo
```

## First run

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

Configure the central PostgreSQL database and tenancy database connection in `.env` before creating a tenant.

## Tenant database setup

After creating at least one active tenant, run the tenant migrations explicitly before loading demo data:

```bash
php artisan tenants:migrate --path=database/migrations/tenant
```

This step creates the tenant-side tables used by Tazreem, including the reference and cash-flow tables needed by the demo. Running only `php artisan migrate` migrates the central database and is not enough for an existing tenant database.

## Demo data

After the tenant migrations finish successfully, run:

```bash
php artisan optimize:clear
composer dump-autoload
php artisan db:seed --class="Database\Seeders\DemoSeeder"
```

The demo dataset includes:

- Main cash account
- Operating bank account
- Opening balances
- Incoming and outgoing cash transactions
- Incoming and outgoing bank transactions
- Six months of historical cash-flow activity
- Three scheduled customer checks

## Smoke-test order

1. Login
2. Switch English / Arabic
3. Tenant dashboard
4. Accounts
5. Transactions
6. Add cash transaction
7. Add bank transaction
8. Add multiple scheduled checks
9. Return to dashboard and verify KPIs
10. Support page

## Dashboard expectations

The tenant dashboard should show:

- Money In
- Money Out
- Net Cash Flow
- Available Balance
- Six-month cash-flow trend
- Upcoming Checks
- Recent Activity
- Account balances

## Important

- Do not merge this branch into `main` before the smoke test passes.
- `DemoSeeder` is manual and should not be run against production data.
- Always run tenant migrations before `DemoSeeder` on a new or existing demo tenant database.
- If any migration, tenant-domain, authentication, RTL, or Blade error appears during testing, capture the full error message and stack trace before changing the database manually.
