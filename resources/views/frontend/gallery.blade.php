@extends('layouts.frontend.app')
@section('title', 'Gallery')
@section('content')
    <section class="about-us-header">
        <h1>Gallery</h1>
        <p>Home &nbsp;<i class="fas fa-arrow-right font-sm"></i>
            <a href="#" style="text-decoration: none;">Gallery</a>
        </p>
    </section>

    <section class="wrapper bg-light py-5">
        <div class="container">
            <div class="row g-4">

                @forelse($gallery as $gl)
                    <div class="col-md-4">
                        <div class="blog-card h-100 shadow-sm">

                            <img src="{{ $gl->image_url }}" class="img-fluid" alt="{{ $gl->title }}">

                            <div class="blog-body p-4">
                                <h5>{{ $gl->title }}</h5>

                                <div class="meta mb-2">

                                    <span><strong>Date:</strong>
                                        {{ $gl->created_at->format('d/m/Y') }}
                                    </span>
                                </div>

                                <p>
                                    {{ Str::limit($gl->description, 120) }}
                                </p>

                                <a href="#" class="btn btn-danger w-100">
                                    READ MORE &nbsp;<i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-danger">
                        <i class="bx bx-error-circle me-2"></i> No Image Found
                    </div>
                @endforelse

            </div>
        </div>
    </section>

@endsection
