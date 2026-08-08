<x-layouts.auth>
    <div class="row g-4 align-items-stretch justify-content-center">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="tazreem-value-card p-5 d-flex flex-column justify-content-between">
                <div>
                    <div class="tazreem-brand-mark mb-4">T</div>
                    <h1 class="text-white mb-3" style="font-size:2.35rem;">Tazreem</h1>
                    <p class="text-white-50 mb-0" style="font-size:1.05rem; max-width:34rem;">
                        {{ __('general.brand_tagline') }}
                    </p>
                </div>

                <div class="mt-5">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="rounded-4 p-3" style="background:rgba(255,255,255,.08)">
                                <div class="fw-bold text-white">Cash</div>
                                <small class="text-white-50">Daily visibility</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-4 p-3" style="background:rgba(255,255,255,.08)">
                                <div class="fw-bold text-white">Bank</div>
                                <small class="text-white-50">One workspace</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-4 p-3" style="background:rgba(255,255,255,.08)">
                                <div class="fw-bold text-white">Checks</div>
                                <small class="text-white-50">Know what's next</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card tazreem-auth-card h-100">
                <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <div class="d-lg-none tazreem-brand-mark mb-3">T</div>
                            <h3 class="mb-1">{{ app()->getLocale() === 'ar' ? 'مرحباً بعودتك' : 'Welcome back' }}</h3>
                            <p class="text-muted mb-0">{{ app()->getLocale() === 'ar' ? 'سجّل الدخول للوصول إلى مساحة شركتك.' : 'Sign in to access your company workspace.' }}</p>
                        </div>
                        <a class="btn btn-sm btn-outline-dark mb-0" href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
                            {{ __('general.language') }}
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger text-white" role="alert">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <label class="form-label fw-semibold">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email address' }}</label>
                        <div class="mb-3">
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="name@company.com" required autofocus>
                        </div>

                        <label class="form-label fw-semibold">{{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">{{ app()->getLocale() === 'ar' ? 'تذكرني' : 'Remember me' }}</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-weight-bold text-dark">
                                    {{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot password?' }}
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn bg-gradient-dark w-100 btn-lg mb-0">
                            {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign in to Tazreem' }}
                        </button>
                    </form>

                    <p class="text-center text-muted text-xs mt-4 mb-0">
                        {{ app()->getLocale() === 'ar' ? 'إدارة التدفق النقدي للشركات الصغيرة والمتوسطة.' : 'Cash-flow clarity for growing businesses.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
