<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TenantReceiptController extends Controller
{
   public function show(Tenant $tenant)
    {
        $tenant->load('meta', 'domains');

        $meta = $tenant->meta;
        abort_unless($meta, 404);

        $endsAt = $meta->computedEndsAt();

        $data = [
            'tenant' => $tenant,
            'meta' => $meta,
            'domain' => optional($tenant->domains->first())->domain,
            'endsAt' => $endsAt,
            // لاحقًا: amount/currency/receipt_no...
        ];

        return Pdf::loadView('admin.tenants.receipt', $data)
            ->stream("subscription-receipt-{$tenant->id}.pdf");
    }
}
