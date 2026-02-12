<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Subscription Receipt</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .h { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .box { border: 1px solid #ddd; padding: 12px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 0; vertical-align: top; }
        .label { color: #666; width: 180px; }
    </style>
</head>
<body>

<div class="h">Subscription Receipt</div>

<div class="box">
    <table>
        <tr>
            <td class="label">Tenant</td>
            <td>{{ $meta->name }}</td>
        </tr>
        <tr>
            <td class="label">Owner Email</td>
            <td>{{ $meta->owner_email ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Period</td>
            <td>{{ strtoupper($meta->subscription_period) }}</td>
        </tr>
        <tr>
            <td class="label">Start Date</td>
            <td>{{ optional($meta->subscription_started_at ?? $meta->created_at)->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td class="label">End Date</td>
            <td>{{ optional($endsAt)->format('Y-m-d') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>{{ $meta->is_active ? 'ACTIVE' : 'SUSPENDED' }}</td>
        </tr>
        <tr>
            <td class="label">Domain</td>
            <td>{{ $domain ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Issued At</td>
            <td>{{ now()->format('Y-m-d H:i') }}</td>
        </tr>
    </table>
</div>

</body>
</html>
