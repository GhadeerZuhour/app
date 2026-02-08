<?php

use App\Livewire\Admin\Tenants\Create;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class FakeTenantDatabaseManager implements TenantDatabaseManager
{
    public static int $createCalls = 0;

    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        self::$createCalls++;

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
    FakeTenantDatabaseManager::$createCalls = 0;

    $driver = config('database.default');
    config()->set("tenancy.database.managers.{$driver}", FakeTenantDatabaseManager::class);
}

it('creates a tenant with a provided password', function () {
    useFakeTenantDatabaseManager();

    $admin = User::factory()->create();

    Artisan::shouldReceive('call')->once()->andReturn(0);
    Password::shouldReceive('sendResetLink')->never();

    $this->actingAs($admin);

    Livewire::test(Create::class)
        ->set('owner_name', 'Jane Owner')
        ->set('owner_email', 'jane@example.com')
        ->set('password', 'Secret123')
        ->set('tenant_name', 'Acme Co')
        ->set('subscription_period', 'monthly')
        ->set('subscription_ends_at', '2030-01-01')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.tenants.index'));

    expect(Tenant::count())->toBe(1);

    $tenant = Tenant::first();
    $domain = $tenant->domains()->first();

    expect($domain)->not->toBeNull();
    expect(str_ends_with($domain->domain, '.localhost'))->toBeTrue();
    expect(FakeTenantDatabaseManager::$createCalls)->toBe(1);

    $owner = User::where('email', 'jane@example.com')->first();

    expect($owner)->not->toBeNull();
    expect(Hash::check('Secret123', $owner->password))->toBeTrue();
    expect($owner->tenant_id)->toBe($tenant->id);
});

it('sends a reset link when no password is provided', function () {
    useFakeTenantDatabaseManager();

    $admin = User::factory()->create();

    Artisan::shouldReceive('call')->once()->andReturn(0);
    Password::shouldReceive('sendResetLink')->once()->andReturn(Password::RESET_LINK_SENT);

    $this->actingAs($admin);

    Livewire::test(Create::class)
        ->set('owner_name', 'John Owner')
        ->set('owner_email', 'john@example.com')
        ->set('tenant_name', 'Beta Co')
        ->set('subscription_period', 'yearly')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.tenants.index'));

    expect(Tenant::count())->toBe(1);
    expect(FakeTenantDatabaseManager::$createCalls)->toBe(1);
});
