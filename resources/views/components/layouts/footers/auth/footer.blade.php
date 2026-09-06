<footer class="footer py-4">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <div class="text-sm text-muted">
                © {{ now()->year }} Tazreem. {{ app()->getLocale() === 'ar' ? 'إدارة أوضح للتدفق النقدي.' : 'Clearer cash-flow management.' }}
            </div>
            <div class="text-xs text-muted">
                {{ app()->getLocale() === 'ar' ? 'النقد • البنوك • الشيكات • الإغلاق الشهري' : 'Cash • Bank • Checks • Monthly close' }}
            </div>
        </div>
    </div>
</footer>
