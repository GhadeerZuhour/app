<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use App\Models\TenantMeta;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    public string $owner_name = '';
    public string $owner_email = '';
    public string $password = '';

    public string $tenant_name = '';
    public ?string $tenant_type = null;

    public string $subscription_period = 'monthly';
    public ?string $subscription_start_at = null;

    public bool $is_active = true;

    public function save()
    {

    //  dd($this->subscription_start_at);
        $this->validate([
            'owner_name'          => 'required|string|min:2',
            'owner_email'         => 'required|email|unique:users,email',
            'tenant_name'         => 'required|string|min:2',
            'subscription_period' => 'required|in:monthly,quarterly,semiannual,yearly',
            'subscription_start_at' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // 1) Create owner user
            $user = User::create([
                'name'     => $this->owner_name,
                'email'    => $this->owner_email,
                'password' => Hash::make($this->password ?: Str::random(32)),
                'role'     => 'subscriber',
            ]);

            // 2) Create tenant (Stancl-owned table) — بدون data/profile/owner/subscription
            $tenant = Tenant::create();

            // 3) Store our business data in tenant_meta (OUR table)
            TenantMeta::create([
                'tenant_id'            => $tenant->id,
                'name'                 => $this->tenant_name,
                'type'                 => $this->tenant_type,
                'owner_user_id'        => $user->id,
                'owner_email'          => $user->email,
                'subscription_period'  => $this->subscription_period,
                'subscription_start_at' => $this->subscription_start_at,
                'is_active'            => (bool) $this->is_active,
            ]);

            // 4) Link user -> tenant_id (if exists)
            if (Schema::hasColumn('users', 'tenant_id')) {
                $user->update(['tenant_id' => $tenant->id]);
            }

            // 5) Domain
            $slug = Str::slug($this->tenant_name) . '-' . Str::lower(Str::random(4));
            $domain = "{$slug}.localhost";
            $tenant->domains()->create(['domain' => $domain]);

            // 6) Create tenant DB name
            $tenant->database()->makeCredentials();
            $tenant->save(); // saves tenancy_db_name

            DB::commit();

            // 7) Create DB + migrate
            $tenant->database()->manager()->createDatabase($tenant);

            Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--path'    => 'database/migrations/tenant',
                '--force'   => true,
            ]);

            // 8) Send reset password
            Password::sendResetLink(['email' => $user->email]);

            session()->flash('success', "Tenant created: {$domain} — reset email sent.");
            return $this->redirectRoute('admin.tenants.index');
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            $this->addError('create', $e->getMessage());
            return null;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.tenants.create');
    }
}
