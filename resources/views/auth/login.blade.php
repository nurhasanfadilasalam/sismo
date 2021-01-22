@extends('auth.layout') @section('content')

<div class="container-login100">
    

        <div class="container">
            <div class="row">
                {{-- <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4"> --}}
                <div
                    class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <h2>SISMO PERANGKAT JARINGAN</h4>
                    </div>

                    <div class="card card-primary">
                        <!-- <div class="card-header text-center"><h4></h4></div> -->
                        <div class="card-body">
                            <form
                                method="POST"
                                action="{{ route('login') }}"
                                class="needs-validation"
                                novalidate="">
                                @csrf
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input
                                        id="username"
                                        type="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        name="username"
                                        tabindex="1"
                                        required="required"
                                        autofocus="autofocus">
                                    <div class="invalid-feedback">Please fill in your username</div>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert"></span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password" class="control-label">{{ __('Password') }}</label>
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        tabindex="2"
                                        required="required">
                                    <div class="invalid-feedback">please fill in your password</div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            type="checkbox"
                                            name="remember"
                                            class="custom-control-input"
                                            tabindex="3"
                                            id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">{{ __('Login') }}</button>
                                </div>
                                <div class="text-center mt-4 mb-3">
                                    <!-- <div class="text-job text-muted"><a href="{{ route('register') }}">{{
                                    __('Register') }}</a></div> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    
</div>
@endsection