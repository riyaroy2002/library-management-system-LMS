@extends('layouts.admin.app')
@section('title', 'Librarians')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">Manage Librarians</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-end gap-2 mb-3">
            <a href="{{ route('admin.librarians.create') }}" class="btn btn-success">
                <i class="bx bx-plus"></i> Add Librarian
            </a>
        </div>

        <div class="card">
            <h5 class="card-header">Librarians List</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Alt Contact No</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Joining Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($librarians as $index => $librarian)
                            <tr>

                                <td>
                                    {{ ($librarians->currentPage() - 1) * $librarians->perPage() + ($index + 1) }}
                                </td>

                                <td>{{ $librarian->user->full_name }}</td>
                                <td>{{ $librarian->user->email }}</td>
                                <td>{{ $librarian->contact_no }}</td>
                                <td>{{ $librarian->alt_contact_no ?? '-' }}</td>
                                <td>{{ ucfirst($librarian->gender) }}</td>

                                <td>
                                    {{ $librarian->date_of_birth ? $librarian->date_of_birth->format('d/m/Y') : '-' }}
                                </td>

                                <td>
                                    {{ $librarian->joining_date ? $librarian->joining_date->format('d/m/Y') : '-' }}
                                </td>


                                <td class="text-center">
                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                        data-bs-target="#statusModal{{ $librarian->user->id }}">
                                        @if ($librarian->user->is_block == 0)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-danger">Blocked</span>
                                        @endif
                                    </a>
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.librarians.edit', $librarian->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </td>

                            </tr>

                            <div class="modal fade" id="statusModal{{ $librarian->user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Change Status</h5>
                                        </div>

                                        <div class="modal-body">
                                            Are you sure you want to
                                            <strong>
                                                {{ $librarian->user->is_block == 1 ? 'Active' : 'Block' }}
                                            </strong>
                                            this librarian?
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>

                                            <form
                                                action="{{ route('admin.librarians.toggle-librarian', $librarian->user->id) }}"
                                                method="POST">
                                                @csrf
                                                <button class="btn btn-danger">
                                                    Yes, Confirm
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-danger">
                                    <i class="bx bx-error-circle"></i> No Librarians Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($librarians->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $librarians->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
