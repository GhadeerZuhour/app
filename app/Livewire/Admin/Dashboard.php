<?php 
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\TenantMeta;

class Dashboard extends Component
{
    public $search = null;

    public function render()
    {
        $metas = TenantMeta::with('tenant')->get();

        $stats = [
            'total' => $metas->count(),
            'active' => $metas->filter(fn($m)=>$m->statusLabel()==='ACTIVE')->count(),
            'expired' => $metas->filter(fn($m)=>$m->statusLabel()==='EXPIRED')->count(),
            'expiringSoon' => $metas->filter(fn($m)=>$m->daysLeft()<=3 && $m->daysLeft()>=0)->count(),
            'monthly' => $metas->where('subscription_period','monthly')->count(),
            'yearly' => $metas->where('subscription_period','yearly')->count(),
        ];

        $expiring = $metas
            ->filter(fn($m)=>$m->daysLeft()<=7 && $m->daysLeft()>=0)
            ->sortBy(fn($m)=>$m->daysLeft());

        $latestTenants = Tenant::with('meta')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'stats',
            'expiring',
            'latestTenants'
        ));
    }
}
