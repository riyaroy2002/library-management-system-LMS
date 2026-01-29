@extends('layouts.admin.app')
@section('title', 'Issued Books List')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Issued Books List</span>
        </h4>

        <div class="card">
            <h5 class="card-header">Issued Books List</h5>

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
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bookIssues as $index => $issue)

                            @php
                                $languages = json_decode($issue->book->languages, true) ?? [];
                            @endphp

                            <tr>
                                <td>
                                    {{ ($bookIssues->currentPage() - 1) * $bookIssues->perPage() + $index + 1 }}
                                </td>

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
                                    <span>
                                        {{ $issue->member->user->full_name }}
                                    </span>
                                </td>

                                <td>
                                    @if ($issue->librarian)
                                        <span>
                                            {{ $issue->librarian->user->full_name }}
                                        </span>
                                    @else
                                        <span>N/A</span>
                                    @endif
                                </td>

                                <td>{{ $issue->issue_date ?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $issue->due_date   ?->format('d M Y') ?? '-' }}</td>
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
                            </tr>

                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-danger">
                                    <i class="bx bx-error-circle me-1"></i> No issue history found
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
