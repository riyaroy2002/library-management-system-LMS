@extends('layouts.frontend.app')
@section('title', 'Forgot Password')
@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="login-card">
            <h3 class="text-center mb-4">Forgot Password</h3>
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
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('librarian.reset-link') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Enter email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="d-grid">
                    <button type="submit" class="btn btn-black">Send Reset Link</button>
                </div>
            </form>

            <div class="d-flex justify-content-center gap-3 mt-2">
                <a href="{{ route('librarian.login') }}" class="forgot-link">Login</a>
                <span>|</span>
                <form method="POST" action="{{ route('librarian.resend-link') }}">
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
