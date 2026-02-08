<?php

use App\Models\Tenant;
use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class FakeTenantDatabaseManager implements TenantDatabaseManager
{
    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        return true;
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        return true;
    }

    public function databaseExists(string $name): bool
    {
        return false;
    }

    public function makeConnectionConfig(array $baseConfig, string $databaseName): array
    {
        return array_merge($baseConfig, ['database' => $databaseName]);
    }

    public function setConnection(string $connection): void {}
}

function useFakeTenantDatabaseManager(): void
{
    $driver = config('database.default');
    config()->set("tenancy.database.managers.{$driver}", FakeTenantDatabaseManager::class);
}

it('defaults tenant data to an empty object', function () {
    useFakeTenantDatabaseManager();

    $tenant = Tenant::create();

    expect($tenant->data)->toBeArray()->toBeEmpty();
});

it('exposes raw data via dataArray', function () {
    useFakeTenantDatabaseManager();

    $tenant = Tenant::create();
    $tenant->setDataPath('subscription.period', 'monthly');

    expect($tenant->refresh()->dataArray())->toMatchArray([
        'subscription' => ['period' => 'monthly'],
    ]);
});
