@extends('layouts.members.app')
@section('title', 'Request Books')

@section('content')
    <section class="wrapper bg-light py-5">
        <div class="container">
            <div class="row g-4">

                @forelse ($books as $book)
                    @php
                        $member = auth()->user()->member;

                        $requested = false;

                        if ($member) {
                            $requested = \App\Models\BookIssue::where('book_id', $book->id)
                                ->where('member_id', $member->id)
                                ->whereIn('status', ['requested', 'issued'])
                                ->exists();
                        }
                    @endphp

                    <div class="col-md-4">
                        <div class="blog-card h-100 shadow-sm">
                            <div class="book-image-wrapper">
                                <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}">
                            </div>

                            <div class="blog-body p-4">
                                <h5>{{ $book->title }}</h5>

                                <p class="text-dark">
                                    ISBN : {{ $book->isbn ?? 'N/A' }} |

                                    @php
                                        $languages = is_array($book->languages)
                                            ? $book->languages
                                            : json_decode($book->languages, true);
                                    @endphp

                                    Language :
                                    @forelse ($languages ?? [] as $lang)
                                        <span class="badge bg-info text-dark me-1">
                                            {{ $lang }}
                                        </span>
                                    @empty
                                        <span class="badge bg-secondary">N/A</span>
                                    @endforelse

                                </p>

                                <form method="POST" action="{{ route('books-request.store') }}">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                                    <button type="submit"
                                        class="btn w-100
                                    {{ $requested ? 'btn-secondary' : 'btn-dark' }}"
                                        {{ $requested ? 'disabled' : '' }}>
                                        {{ $requested ? 'Requested' : 'Request Book' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="col-12 text-center text-danger">
                        <i class="fa fa-book-open me-1"></i>
                        No books available right now
                    </div>
                @endforelse

            </div>


            <div class="mt-4 d-flex justify-content-center">
                {{ $books->links() }}
            </div>
        </div>
    </section>
@endsection
