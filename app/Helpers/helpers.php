<?php

use Illuminate\Support\Facades\Auth;


function tenant_id(): ?int
{
    $u = Auth::user();
    if (!$u) return null;

    // إذا المستخدم subscriber فهو نفسه الـ tenant
    if (($u->role ?? null) === 'subscriber') {
        return $u->id;
    }

    // إذا admin/staff تابع لمشترك (tenant)
    return $u->tenant_id ?? $u->parent_id ?? $u->id;
}

