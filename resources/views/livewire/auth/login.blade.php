<x-layouts.auth>
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <div class="card mt-6">
                <div class="card-header pb-0 text-start">
                    <h4 class="font-weight-bolder mb-0">Sign In</h4>
                    <p class="mb-0 text-sm">Enter your email and password to sign in</p>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control form-control-lg"
                                placeholder="Email"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-3">
                            <input
                                type="password"
                                name="password"
                                class="form-control form-control-lg"
                                placeholder="Password"
                                required
                            >
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn bg-gradient-dark w-100 my-3">
                                Sign in
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-sm text-dark font-weight-bold">
                                Forgot your password?
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    @if (Route::has('register'))
                        <p class="mb-0 text-sm mx-auto">
                            Don’t have an account?
                            <a href="{{ route('register') }}" class="text-dark font-weight-bold">Sign up</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
