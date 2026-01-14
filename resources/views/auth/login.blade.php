<x-layouts.auth>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card mt-7">
                <div class="card-header text-center">
                    <h5 class="mb-0">Sign in</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="Email" required autofocus>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password"
                                   class="form-control" placeholder="Password" required>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button class="btn bg-gradient-dark w-100">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
