@extends('layouts.admin.auth')
@section('title', 'Reset Password')
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

                <h4 class="mb-4 text-center">Reset Password</h4>
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

                <form id="formAuthentication" class="mb-3" action="{{ route('admin.post.reset-password') }}"
                    method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" value="{{ session('email') }}"
                            class="form-control form-control-md @error('email') is-invalid @enderror"
                            placeholder="email address" readonly>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">New Password</label>
                        </div>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="Enter New Password" aria-describedby="password" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Confirm Password</label>
                        </div>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password_confirmation"
                                placeholder="Enter Confirm Password" aria-describedby="password" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-success d-grid w-100" type="submit">Update Password</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
