<x-templating.auth.layout>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                <div class="w-100">
                    <div class="d-flex flex-column h-100 py-0 py-xl-3">
                        <div class="text-center mb-4">
                            <a href="index.html" class="">
                                <img src="assets/images/logo-dark.png" alt="" height="22" class="auth-logo logo-dark mx-auto">
                                <img src="assets/images/logo-light.png" alt="" height="22" class="auth-logo logo-light mx-auto">
                            </a>
                            <p class="text-muted mt-2">User Experience & Interface Design Strategy Saas Solution</p>
                        </div>

                        <div class="card my-auto overflow-hidden">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <div class="bg-overlay bg-primary"></div>
                                        <div class="h-100 bg-auth align-items-end">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="p-lg-5 p-4">
                                            <div>
                                                <div class="text-center mt-1">
                                                    <h4 class="font-size-18">Welcome Back !</h4>
                                                    <p class="text-muted">Sign in to continue to Tocly.</p>
                                                </div>

                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <b>Error!</b> : {{ $errors->first() }}
                                                    </div>
                                                @endif
                                                <form action="{{ route('login') }}" method="POST" class="auth-input">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="password-input">Password</label>
                                                        <input type="password" class="form-control" placeholder="Enter password" name="password">
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                                        <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                                    </div>
                                                    <div class="mt-3">
                                                        <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <p class="mb-0">Don't have an account ? <a href="{{ route('register') }}" class="fw-medium text-primary"> Register </a> </p>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <!-- end card -->

                        <div class="mt-5 text-center">
                            <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Tocly. Crafted with <i class="mdi mdi-heart text-danger"></i> by RA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</x-templating.auth.layout>
