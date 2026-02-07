@extends('layouts.frontend.app')
@section('title', 'Contact Us')
@section('content')

    <section class="about-us-header">
        <h1>Contact Us</h1>
        <p>Home &nbsp;<i class="fas fa-arrow-right font-sm"></i> <a href="#" style="text-decoration: none;">Contact
                Us</a>
        </p>
    </section>

    <section class="about-us-content">
        <div class="about-us-text">
            <h2><span class="underline-3 style-3 green">{{ $contact_us?->title ?? 'Contact Us' }}</span></h2>

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
            <div class="contact-card">
                <form name="contactForm" action="{{ route('contact-us.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Your Name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Your Email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                            placeholder="Subject" value="{{ old('subject') }}">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                            placeholder="Your Message">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark btn-telegram">
                        <i class="fab fa-telegram-plane"></i> &nbsp;
                        Send Message
                    </button>
                </form>

            </div>
        </div>
        <div class="about-us-image">
            @if ($contact_us->image_url)
                <img src="{{ $contact_us->image_url }}">
            @endif
        </div>
    </section>

@endsection
