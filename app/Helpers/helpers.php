<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('tenant_id')) {
    function tenant_id()
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();

        return $user->role === 'subscriber'
            ? $user->id
            : $user->parent_id;
    }
}
