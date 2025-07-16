@extends('layouts.app')

@section('style')
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .card {
            background-color: #1e1e1e;
            border: 1px solid #2c2c2c;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
        }

        .card-header {
            background-color: #272727;
            color: #ffffff;
            font-weight: bold;
        }

        .form-control {
            background-color: #2b2b2b;
            color: #ffffff;
            border: 1px solid #444;
        }

        .form-control:focus {
            background-color: #333;
            color: #ffffff;
            border-color: #007bff;
            box-shadow: none;
        }

        .form-check-label {
            color: #ccc;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-link {
            color: #00bcd4;
        }

        .btn-link:hover {
            color: #00acc1;
        }

        .invalid-feedback {
            color: #ff6b6b;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            background-color: #2b2b2b !important;
            color: #ffffff !important;
            -webkit-box-shadow: 0 0 0 30px #2b2b2b inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @if (session('auth.fail.errors')) is-invalid @endif" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @if (session('auth.fail.errors')) is-invalid @endif" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4 d-flex gap-2 align-items-center">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        {{session()->forget('auth.fail.errors')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
