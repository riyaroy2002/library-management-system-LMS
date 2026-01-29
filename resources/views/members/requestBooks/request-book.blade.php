@push('styles')
    <style>
        .wrapper.image-wrapper {
            min-height: 230px;
            background-size: contain;
        }

        .book-image-wrapper {
            width: 100%;
            height: 445px;
            padding: 12px;
            border-radius: 12px;
            overflow: hidden;

        }

        .book-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            display: block;
            transition: transform 0.3s ease;
        }
    </style>
@endpush

@extends('layouts.members.app')
@section('title', 'Create Request')

@section('content')
    <section class="wrapper bg-light py-5">
        <div class="container">
            <div class="row g-4">


                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
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

                @forelse ($books as $book)

                    @php
                        $member = auth()->user()->members()->first();
                        $memberId = optional($member)->id;

                        $requested = $member
                            ? \App\Models\BookIssue::where('book_id', $book->id)
                                ->where('member_id', $memberId)
                                ->whereIn('status', ['requested', 'issued'])
                                ->exists()
                            : false;

                        $languages = is_array($book->languages)
                            ? $book->languages
                            : json_decode($book->languages, true);
                    @endphp

                    <div class="col-lg-4 col-md-6">
                        <div class="card book-card h-100 shadow-sm">
                            <div class="book-image-wrapper">
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
                            </div>

                            <div class="card-body p-3">

                                <h6 class="fw-bold mb-2">
                                    {{ Str::limit($book->title, 60) }}
                                </h6>

                                <div class="book-meta">
                                    <strong>ISBN :</strong>
                                    {{ filled($book->ISBN) ? $book->ISBN : 'N/A' }}
                                </div>

                                <div class="book-meta">
                                    <strong>Author :</strong>
                                    @forelse ($book->authors as $author)
                                        {{ $author->full_name }}
                                    @empty
                                        <span class="badge bg-secondary">N/A</span>
                                    @endforelse
                                </div>

                                <div class="book-meta">
                                    <strong>Category :</strong>
                                    @forelse ($book->categories as $category)
                                        {{ $category->name }}
                                    @empty
                                        <span class="badge bg-secondary">N/A</span>
                                    @endforelse
                                </div>

                                <div class="book-meta mb-3">
                                    <strong>Language :</strong>
                                    @forelse ($languages ?? [] as $lang)
                                        <span class="badge bg-success text-dark me-1">{{ $lang }}</span>
                                    @empty
                                        <span class="badge bg-secondary">N/A</span>
                                    @endforelse
                                </div>


                                @if (!$requested)
                                    <button type="button" class="btn btn-dark w-100" data-bs-toggle="modal"
                                        data-bs-target="#requestBookModal{{ $book->id }}">
                                        Request Book
                                    </button>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>
                                        Requested
                                    </button>
                                @endif

                            </div>
                        </div>
                    </div>
                    @if (!$requested)
                        <div class="modal fade" id="requestBookModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Book Request</h5>

                                    </div>

                                    <div class="modal-body">
                                        Are you sure you want to request
                                        <strong>{{ $book->title }}</strong>?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <form method="POST" action="{{ route('books-request.store') }}">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">

                                            <button type="submit" class="btn btn-success">
                                                Yes, Request
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                @empty
                    <div class="col-12 text-center text-danger">
                        <i class="fa fa-book-open me-1"></i>
                        No books available right now
                    </div>
                @endforelse

            </div>


            @if ($books->hasPages())
                <div class="d-flex justify-content-end mt-4">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
