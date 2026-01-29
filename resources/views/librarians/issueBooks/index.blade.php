@extends('layouts.librarians.app')
@section('title', 'Issue Book')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage Issued Books</span>
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
            <h5 class="card-header">Issued Books</h5>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Book</th>
                            <th>Categories</th>
                            <th>Authors</th>
                            <th>Languages</th>
                            <th>Member</th>
                            <th>Librarian</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bookIssues as $index => $issue)

                            @php
                                $languages = json_decode($issue->book->languages, true) ?? [];
                            @endphp

                            <tr>
                                <td>{{ ($bookIssues->currentPage() - 1) * $bookIssues->perPage() + $index + 1 }}</td>

                                <td>
                                    <strong>{{ $issue->book->name }}</strong><br>
                                    <small class="text-muted">{{ $issue->book->title }}</small>
                                </td>

                                <td>
                                    @foreach ($issue->book->categories as $category)
                                        <span class="badge bg-info me-1">{{ $category->name }}</span>
                                    @endforeach
                                </td>

                                <td>
                                    @foreach ($issue->book->authors as $author)
                                        <span class="badge bg-primary me-1">{{ $author->full_name }}</span>
                                    @endforeach
                                </td>

                                <td>
                                    @foreach ($languages as $lang)
                                        <span class="badge bg-secondary me-1">{{ $lang }}</span>
                                    @endforeach
                                </td>

                                <td>
                                    <span>{{ $issue->member->user->full_name }}</span>
                                </td>

                                <td>
                                    @if ($issue->librarian)
                                        <span>{{ $issue->librarian->user->full_name }}</span>
                                    @else
                                        <span>N/A</span>
                                    @endif
                                </td>

                                <td>{{ $issue->issue_date?->format('d M Y')  ?? '-' }}</td>
                                <td>{{ $issue->due_date?->format('d M Y')    ?? '-' }}</td>
                                <td>{{ $issue->return_date?->format('d M Y') ?? '-' }}</td>

                                <td class="text-center">
                                    @if ($issue->status === 'requested')
                                        <span class="badge bg-info text-dark">Requested</span>
                                    @elseif ($issue->status === 'issued')
                                        <span class="badge bg-success">Issued</span>
                                    @elseif ($issue->status === 'returned')
                                        <span class="badge bg-warning">Returned</span>
                                    @else
                                        <span class="badge bg-danger">Lost</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">

                                            <button type="button" class="dropdown-item text-success" data-bs-toggle="modal"
                                                data-bs-target="#issueModal{{ $issue->id }}">
                                                <i class="bx bx-book-open me-1"></i> Issue
                                            </button>


                                            <button type="button" class="dropdown-item text-warning" data-bs-toggle="modal"
                                                data-bs-target="#returnModal{{ $issue->id }}">
                                                <i class="bx bx-undo me-1"></i> Return
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <div class="modal fade" id="issueModal{{ $issue->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Issue Book: {{ $issue->book->title }}</h5>
                                        </div>

                                        <form method="POST" action="{{ route('librarian.issue-books.issue', $issue->id) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Issue Date</label>
                                                    <input type="date" name="issue_date" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Due Date</label>
                                                    <input type="date" name="due_date" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Issue Book</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" id="returnModal{{ $issue->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Return Book: {{ $issue->book->title }}</h5>

                                        </div>

                                        <form method="POST" action="{{ route('librarian.issue-books.return', $issue->id) }}">
                                            @csrf
                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label>Issue Date</label>
                                                    <input type="date" name="issue_date" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Return Date</label>
                                                    <input type="date" name="return_date" class="form-control"  required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Return Book</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-danger">
                                    <i class="bx bx-error-circle me-1"></i> No data found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bookIssues->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $bookIssues->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
