# Tazreem Demo Runbook

This branch prepares a repeatable investor/customer demo for Tazreem without changing the normal production seeding flow.

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

Start the application and sign in as an admin. From the admin tenant screen create one active tenant.

The existing tenant creation flow creates the tenant database and runs the tenant migrations. For an existing tenant, make sure the new demo migration has run:

```bash
php artisan tenants:migrate --path=database/migrations/tenant --force
```

## 3. Add demo data

After at least one tenant exists, run:

```bash
php artisan db:seed --class=DemoSeeder
```

The seeder uses the first tenant and adds realistic presentation data:

- ILS currency and a demo bank
- Main Cash and Operating Bank accounts
- Opening balances and account balances
- Incoming cash sales and bank transfers
- Outgoing supplier and operating expenses
- Six months of historical cash-flow data
- Three scheduled customer checks
- References and descriptions for transaction search/demo

The seeder is designed for repeatable demo preparation and is not called automatically from `DatabaseSeeder`.

## 4. Recommended demo flow

### Opening — 30 seconds

Explain the problem first:

> Small businesses often know how much they sold, but they do not have one simple place showing cash, bank movements, post-dated checks, and what is coming next. Tazreem turns those daily movements into a clear cash-flow picture.

### Dashboard — 60 seconds

Show these four KPIs:

1. **Money in** — what entered the business this month.
2. **Money out** — operating outflows and supplier payments.
3. **Net cash flow** — the difference between incoming and outgoing money.
4. **Available across accounts** — current cash and bank balances.

Then point to the six-month trend and explain that Tazreem helps the owner identify whether cash flow is improving or tightening.

### Upcoming checks — 30 seconds

Show the scheduled checks and explain that Tazreem makes future cash visible instead of leaving post-dated checks in spreadsheets or physical files.

### Accounts — 30 seconds

Open **Accounts** and show that cash and bank accounts are managed separately with currency and balance information.

### Transactions — 60 seconds

Open **Transactions** and show:

- Reference number
- Description
- Cash / bank / check payment method
- Incoming / outgoing direction
- Positive and negative financial movement
- Month, type, search, and archive filters

### Add transaction — 60 seconds

Create one transaction live. The purpose is to demonstrate that daily data entry is simple and does not require accounting expertise.

### Checks — 60 seconds

Create a check entry with multiple scheduled checks. Show how Tazreem generates the payment schedule and allows each check to be reviewed.

### Return to dashboard — 30 seconds

Finish by returning to the dashboard and reinforcing the product value:

> Tazreem is not another accounting ERP. It gives the business owner a simple operational view of liquidity: what came in, what went out, what is available, and what is expected next.

## Demo story

A useful fictional customer is **Zaitouna Trading**, a small Palestinian trading company with cash sales, bank transfers, supplier expenses, and customer post-dated checks. The owner currently tracks these in Excel and WhatsApp. Tazreem provides one tenant-isolated workspace with a much clearer view of liquidity.

## Before presenting

- Run all central and tenant migrations.
- Run `DemoSeeder` once.
- Confirm the tenant owner can sign in.
- Open Dashboard, Accounts, Transactions, and Create Transaction before the meeting.
- Keep one incoming transaction ready to enter live.
- Avoid showing admin/developer screens unless the audience asks about SaaS tenant management.

## Notes

- Demo data is for development/presentation environments only.
- Do not run the demo seeder against production data.
- The dashboard is at `resources/views/tenant-dashboard.blade.php`.
- The demo migration adds `direction` to tenant `entries` so cash flow can distinguish incoming and outgoing movements.
