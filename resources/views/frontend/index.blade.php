@extends('layouts.frontend.app')
@section('title', 'LIBRARY MANAGEMENT SYSTEM-LMS | Home')
@section('content')

    @if ($hero)
        <section class="wrapper image-wrapper bg-cover bg-image bg-xs-none bg-gray"
            data-image-src="{{ asset($hero->image_url) }}">

            <div class="container pt-17 pb-15 py-sm-17 py-xxl-20">
                <div class="row">
                    <div class="col-sm-6 col-xxl-5 text-center text-sm-start">
                        <h2 class="display-1 fs-56 mb-4 mt-0 mt-lg-5 ls-xs pe-xl-5 pe-xxl-0">
                            {{ trim($titleWithoutHighlight) }}
                            <span class="underline-3 style-3 green">{{ $highlight }}</span>
                        </h2>
                        <div>
                            @php
                                $extra = json_decode($hero->extra, true);
                            @endphp
                            <a href="{{ url($extra['button_link'] ?? '/explore-now') }}"
                                class="btn btn-lg btn-primary rounded">
                                {{ $extra['button_text'] ?? 'Explore Now' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="wrapper bg-light">
        <div class="container py-15 py-md-10">
            <div class="row text-center mb-9">
                <div class="col-md-10 col-lg-9 col-xxl-8 mx-auto">
                    <h3 class="display-3 ls-sm mb-9 px-xl-11">
                        <span class="underline-3 style-3 green">{{ $mission?->title ?? 'Our Mission' }}</span>
                    </h3>
                </div>
            </div>

            <div class="row align-items-center gx-lg-8 gx-xl-12 gy-8">
                <div class="col-lg-6">
                    <figure class="rounded">
                        <img src="{{ asset($mission->image_url) }}" alt="{{ $mission?->title }}"
                            class="img-fluid rounded shadow-sm">
                    </figure>
                </div>
                <div class="col-lg-6">
                    @forelse($missionItems as $item)
                        <div class="d-flex flex-row mb-6">
                           <div class="me-4 mt-1">
                                {!! $item->icon !!}
                            </div>
                            <div>
                                <h4 class="fs-20 ls-sm">{{ $item->heading }}</h4>
                                <p class="mb-0">{{ $item->text }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <i class="bx bx-error-circle"></i> No mission items found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="wrapper bg-light">
        <div class="container py-15 py-md-10">

            <div class="row text-center mb-9">
                <div class="col-md-10 col-lg-9 col-xxl-8 mx-auto">
                    <h3 class="display-3 ls-sm mb-9 px-xl-11">
                        <span class="underline-3 style-3 green">{{ $vision->title ?? 'Our Vision' }}</span>
                    </h3>
                </div>
            </div>

            <div class="row align-items-center gx-lg-8 gx-xl-12 gy-8">

                <div class="col-lg-6">
                    @forelse(json_decode($vision->extra, true)['points'] ?? [] as $point)
                        <div class="d-flex flex-row mb-6">
                            <div class="me-4 mt-1">
                                {!! $point['icon'] !!}
                            </div>
                            <div>
                                <h4 class="fs-20 ls-sm">{{ $point['title'] }}</h4>
                                <p class="mb-0">{{ $point['description'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <i class="bx bx-error-circle"></i> No vision points found.
                        </div>
                    @endforelse
                </div>

                <div class="col-lg-6">
                    <figure class="rounded">
                        <img src="{{ $vision->image_url }}" alt="{{ $vision->title ?? 'Vision Image' }}"
                            class="img-fluid rounded">
                    </figure>
                </div>

            </div>

        </div>
    </section>



@endsection
