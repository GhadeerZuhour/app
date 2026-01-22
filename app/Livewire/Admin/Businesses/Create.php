<?php

namespace App\Livewire\Admin\Businesses;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Tenant;
use Artisan;

class Create extends Component
{
    public string $owner_name = '';
    public string $owner_email = '';
    public string $password = '';

    public string $business_name = '';
    public ?string $business_type = null;

    public string $subscription_period = 'monthly';
    public ?string $subscription_ends_at = null;
    public bool $is_active = true;

    public function save()
    {
       

        $this->validate([
            'owner_name' => 'required',
            'owner_email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'business_name' => 'required',
        ]);

        // 1️⃣ Owner (subscriber)
        $user = User::create([
            'name' => $this->owner_name,
            'email' => $this->owner_email,
            'password' => Hash::make($this->password),
            'role' => 'subscriber',
        ]);

        // 2️⃣ Tenant (Business)
        $tenant = Tenant::create([
            'user_id' => $user->id,
            'business_name' => $this->business_name,
            'business_type' => $this->business_type,
            'subscription_period' => $this->subscription_period,
            'subscription_ends_at' => $this->subscription_ends_at,
            'is_active' => $this->is_active,
        ]);

        // 3️⃣ Domain
        $slug = Str::slug($this->business_name) . '-' . Str::random(4);
        $tenant->domains()->create([
            'domain' => "{$slug}.localhost",
        ]);
        // 4️⃣ Provision DB + Tenant Migrations
        $tenant->run(function () {
            Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
        });

        return redirect()->route('admin.businesses.index');
    }

    public function render()
    {
        return view('livewire.admin.businesses.create');
    }
}
