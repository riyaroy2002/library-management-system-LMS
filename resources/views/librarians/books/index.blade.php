@extends('layouts.librarians.app')
@section('title', 'Books')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage Books</span>
        </h4>


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

        <div class="card">
            <h5 class="card-header">Books List</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Categories</th>
                            <th>Authors</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>ISBN</th>
                            <th>Year</th>
                            <th>Edition</th>
                            <th>Languages</th>
                            <th>Total Copies</th>
                            <th>Cover Image</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        @forelse ($books as $index => $book)
                            <tr>

                                <td>{{ ($books->currentPage() - 1) * $books->perPage() + $index + 1 }}</td>

                                <td>
                                    @foreach ($book->categories as $category)
                                        <span>{{ $category->name }}</span>
                                    @endforeach
                                </td>


                                <td>
                                    @foreach ($book->authors as $author)
                                        <span class="badge bg-primary me-1">
                                            {{ $author->full_name }}
                                        </span>
                                    @endforeach
                                </td>


                                <td>{{ $book->name }}</td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->ISBN ?? '-' }}</td>
                                <td>{{ $book->publish_year ?? '-' }}</td>
                                <td>{{ $book->edition ?? '-' }}</td>
                                @php
                                    $badgeColors = [
                                        'primary',
                                        'success',
                                        'danger',
                                        'warning',
                                        'info',
                                        'secondary',
                                        'dark',
                                    ];
                                    $languages = json_decode($book->languages, true) ?? [];
                                @endphp

                                <td>
                                    @foreach ($languages as $lang)
                                        @php $color = $badgeColors[array_rand($badgeColors)]; @endphp
                                        <span class="badge bg-{{ $color }}">{{ $lang }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $book->total_copies }}</td>


                                <td>
                                    @if ($book->cover_image)
                                        <img src="{{ $book->image_url }}" alt="cover" width="50">
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($book->status == 'available')
                                        <span class="badge bg-label-success me-1">Available</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">Unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-danger">
                                    <i class="bx bx-error-circle me-2"></i> No Data Found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>


            @if ($books->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $books->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
