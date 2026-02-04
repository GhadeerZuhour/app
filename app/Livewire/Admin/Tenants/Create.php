<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\View\View;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public string $owner_name = '';

    public string $owner_email = '';

    public string $password = '';

    public string $tenant_name = '';

    public ?string $tenant_type = null;

    public string $subscription_period = 'monthly';

    public ?string $subscription_ends_at = null;

    public bool $is_active = true;

    public function save()
    {
        $this->validate([
            'owner_name' => 'required',
            'owner_email' => 'required|email|unique:users,email',
            'tenant_name' => 'required',
        ]);

        // 1) Create user (subscriber)
        $user = User::create([
            'name' => $this->owner_name,
            'email' => $this->owner_email,
            'password' => Hash::make(Str::random(32)), // will be reset by email
            'role' => 'subscriber',
        ]);

        // 2) Create tenant record (central DB)
        $tenant = Tenant::create([
            'tenant_name' => $this->tenant_name,
            'tenant_type' => $this->tenant_type,
            'subscription' => [
                'period' => $this->subscription_period,
                'ends_at' => $this->subscription_ends_at,
                'is_active' => $this->is_active,
            ],
        ]);

        // Link user -> tenant
        $user->update(['tenant_id' => $tenant->id]);

        // 3) Create domain
        $slug = Str::slug($this->tenant_name).'-'.Str::lower(Str::random(4));
        $domain = "{$slug}.localhost";

        $tenant->domains()->create(['domain' => $domain]);

        // 4) ✅ Create tenant database (PostgreSQL-safe: no transaction)
        DB::connection(config('tenancy.database.central_connection'))->transaction(function () {
            // transaction only for central records (safe)
        });

        // Create DB OUTSIDE transaction:
        $tenant->database()->makeCredentials();
        $tenant->database()->manager()->createDatabase($tenant);  // ✅ ينشئ قاعدة البيانات

        // ✅ Run tenant migrations
        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
        // 6) Send reset password email
        Password::sendResetLink(['email' => $user->email]);

        session()->flash('success', "Tenant created: {$domain} — reset email sent.");

      return redirect()->route('admin.tenants.index');
    }

    public function render(): View
    {
        return view('livewire.admin.tenants.create');
    }
}
