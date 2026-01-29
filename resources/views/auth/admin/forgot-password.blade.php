@extends('layouts.admin.auth')
@section('title', 'Forgot Password')
@section('auth')
    <div class="authentication-inner">
        <div class="card">
            <div class="card-body">

                <div class="app-brand justify-content-center">
                    <a href="#" class="app-brand-link gap-1">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('assets/img/logo.png') }}" width="40px" />
                        </span>
                        <span class="app-brand-text demo text-body fw-bolder text-uppercase">LMS</span>
                    </a>
                </div>


                <h4 class="text-center">Forgot Password</h4>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                <form id="formAuthentication" class="mb-3" action="{{ route('admin.reset-link') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control form-control-md @error('email') is-invalid @enderror"
                            placeholder="Enter email address">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-success  d-grid w-100" type="submit">Send Reset Link</button>
                    </div>

                </form>

                <a href="{{ route('admin.login') }}" class="forgot-link">Login</a>

                <form method="POST" action="{{ route('admin.resend-link') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                    <button type="submit" class="btn btn-link p-0 text-decoration-none small text-dark">
                        Resend reset link
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
