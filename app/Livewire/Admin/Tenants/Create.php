<?php

namespace App\Livewire\Admin\Tenants;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;

class Create extends Component
{
    public string $owner_name = '';
    public string $owner_email = '';
    public string $password = '';

    public string $business_name = '';
    public ?string $business_type = null;
    public ?string $business_phone = null;
    public ?string $business_address = null;

    public string $subscription_period = 'monthly';
    public ?string $subscription_ends_at = null;
    public bool $is_active = true;

   public function save()
{
    logger()->info('CreateTenant: save() start');

    $data = $this->validate([
        'owner_name' => ['required','string','max:255'],
        'owner_email' => ['required','email','unique:users,email'],
        'password' => ['required','min:8'],
        'business_name' => ['required','string','max:255'],
        'business_type' => ['nullable','string','max:255'],
        'subscription_period' => ['required','in:monthly,yearly'],
        'subscription_ends_at' => ['nullable','date'],
        'is_active' => ['boolean'],
    ]);

    $tenant = null;
    $user = null;

    try {
        // ✅ transaction فقط للـ central records
        [$tenant, $user, $domain] = \DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => Hash::make($data['password']),
                'role' => 'subscriber',
            ]);

            $tenant = Tenant::create([
                'id' => (string) \Str::uuid(),
                'user_id' => $user->id,
                'is_active' => $data['is_active'],
                'data' => [
                    'business_name' => $data['business_name'],
                    'business_type' => $data['business_type'],
                    'subscription_period' => $data['subscription_period'],
                    'subscription_ends_at' => $data['subscription_ends_at'],
                ],
            ]);

            $slug = \Str::slug($data['business_name']).'-'.\Str::lower(\Str::random(4));
            $domain = "{$slug}.localhost";

            $tenant->domains()->create(['domain' => $domain]);

            return [$tenant, $user, $domain];
        });

        logger()->info('CreateTenant: central records ok', ['tenant_id' => $tenant->id]);

        // ✅ PostgreSQL: CREATE DATABASE خارج أي transaction
        $tenant->database()->makeCredentials();
        $tenant->database()->manager()->createDatabase($tenant);

        logger()->info('CreateTenant: database created', ['tenant_id' => $tenant->id]);

        // ✅ migrate tenants (أفضل من migrate العادي)
        \Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        logger()->info('CreateTenant: migrate output '.\Artisan::output());

        session()->flash('success', "Tenant created successfully: {$domain}");
        return redirect()->route('admin.tenants.index');

    } catch (\Throwable $e) {
        logger()->error('CreateTenant FAILED: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
        $this->addError('create', $e->getMessage());
        return;
    }
}


    public function render()
    {
        return view('livewire.admin.tenants.create');
    }
}
