@extends('layouts.frontend.app')
@section('title', 'Verify Email')
@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100 flex-column gap-3">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif
        <div class="otp-card">
            <div class="text-center mb-4">
                <h3 class="text-dark mb-2"><strong>Verify Your Email</strong></h3>
                <p class="text-dark small">
                    We have sent a verification link to your email address.
                    Please check your inbox and verify your email to continue.
                </p>
            </div>

            <div class="text-center mt-3">
                <p class="small text-dark mb-2">Didn’t receive the email?</p>
                <form method="POST" action="{{ route('resend-email-link') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                    <button type="submit" class="btn btn-black btn-sm">
                        Resend Verification Email
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
