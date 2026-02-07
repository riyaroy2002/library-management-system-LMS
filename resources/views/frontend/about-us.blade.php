@extends('layouts.frontend.app')
@section('title', 'About US')
@section('content')
    <section class="about-us-header">
        <h1>About Us</h1>
        <p>Home &nbsp;<i class="fas fa-arrow-right font-sm"></i> <a href="#" style="text-decoration: none;">About Us</a>
        </p>
    </section>
    <section class="about-us-content">
        <div class="about-us-text">
            <h2>
                <span class="underline-3 style-3 green">
                    {{ $about?->title ?? 'About Us' }}
                </span>
            </h2>
            @php
                $extra = json_decode($about->extra, true);
            @endphp
            <h3>
                <b>{{ $extra['subtitle'] ?? 'Save Life Donate Blood' }}</b>
            </h3>

            <p class="text-dark">
                {{ $extra['content'] }}
            </p>
        </div>

        <div class="about-us-image">
            @if ($about->image_url)
                <img src="{{ $about->image_url }}">
            @endif
        </div>
    </section>
@endsection
