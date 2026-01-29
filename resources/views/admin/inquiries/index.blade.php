@extends('layouts.admin.app')
@section('title', 'Inquiries')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage Inquiries</span>
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
            <h5 class="card-header">Inquiries List</h5>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th class="text-center">Replied Message</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @forelse($contacts as $index => $contact)
                            <tr>
                                <td>
                                    {{ ($contacts->currentPage() - 1) * $contacts->perPage() + $index + 1 }}
                                </td>

                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->subject }}</td>
                                <td>{{ Str::limit($contact->message, 20) }}</td>
                                <td>{{ Str::limit($contact->replyBack ?? '-', 20) }}</td>

                                <td class="text-center">
                                    @if ($contact->replyBack)
                                        <span class="badge bg-success">Replied</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                @if (!$contact->replyBack)
                                    <td class="text-center">
                                        <a href="{{ route('admin.inquiries.reply-back', $contact->id) }}"
                                            class="btn btn-sm btn-dark">
                                            Reply
                                        </a>
                                    </td>
                                    @else
                                    <td class="text-center">
                                        <a href="{{ route('admin.inquiries.view', $contact->id) }}"
                                            class="btn btn-sm btn-dark">
                                             View
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">
                                    <i class="bx bx-error-circle me-2"></i> No Inquiries Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($contacts->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>


    </div>
@endsection
