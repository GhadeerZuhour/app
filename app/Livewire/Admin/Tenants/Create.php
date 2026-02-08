<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    public ?string $subscription_ends_at = null;

    public bool $is_active = true;

    public function save(): void
    {
        $this->validate([
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8',
            'tenant_name' => 'required|string|max:255',
            'tenant_type' => 'nullable|string|max:255',
            'subscription_period' => 'required|in:monthly,yearly',
            'subscription_ends_at' => 'nullable|date_format:Y-m-d',
            'is_active' => 'boolean',
        ]);

        $user = null;
        $tenant = null;

        try {
            $generatedPassword = $this->password === '';
            $password = $generatedPassword ? Str::random(32) : $this->password;

            $user = User::create([
                'name' => $this->owner_name,
                'email' => $this->owner_email,
                'password' => Hash::make($password),
                'role' => 'subscriber',
            ]);

            $tenant = Tenant::create([
                'tenant_name' => $this->tenant_name,
                'tenant_type' => $this->tenant_type,
                'subscription' => [
                    'period' => $this->subscription_period,
                    'ends_at' => $this->subscription_ends_at,
                    'is_active' => $this->is_active,
                ],
            ]);

            $user->tenant_id = $tenant->id;
            $user->save();

            $slug = Str::slug($this->tenant_name).'-'.Str::lower(Str::random(4));
            $domain = "{$slug}.localhost";

            $tenant->domains()->create(['domain' => $domain]);

            $tenant->database()->makeCredentials();
            $tenant->database()->manager()->createDatabase($tenant);

            Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            if ($generatedPassword) {
                Password::sendResetLink(['email' => $user->email]);
                session()->flash('success', "Tenant created: {$domain} — reset email sent.");
            } else {
                session()->flash('success', "Tenant created: {$domain}.");
            }

            $this->redirectRoute('admin.tenants.index');

            return;
        } catch (Throwable $exception) {
            if ($tenant) {
                $tenant->domains()->delete();
                $tenant->delete();
            }

            if ($user) {
                $user->delete();
            }

            report($exception);
            $this->addError('create', 'Failed to create tenant. Please try again.');

            return;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.tenants.create');
    }
}
