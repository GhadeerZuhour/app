<?php

use App\Livewire\Admin\Tenants\Show;
use App\Models\Tenant;
use App\Models\User;

it('redirects guests to login', function () {
    $tenant = Tenant::create([
        'data' => [
            'tenant_name' => 'Acme Co',
            'subscription' => [
                'period' => 'monthly',
                'ends_at' => null,
                'is_active' => true,
            ],
        ],
    ]);

    $this->get(route('admin.tenants.show', $tenant))
        ->assertRedirect(route('login'));
});

it('shows tenant details to authenticated users', function () {
    $user = User::factory()->create();

    $tenant = Tenant::create([
        'data' => [
            'tenant_name' => 'Acme Co',
            'subscription' => [
                'period' => 'monthly',
                'ends_at' => null,
                'is_active' => true,
            ],
        ],
    ]);

    $tenant->domains()->create(['domain' => 'acme.localhost']);

    $this->actingAs($user)
        ->get(route('admin.tenants.show', $tenant))
        ->assertSuccessful()
        ->assertSeeLivewire(Show::class)
        ->assertSee('Tenant Details')
        ->assertSee('Acme Co')
        ->assertSee('acme.localhost');
});
