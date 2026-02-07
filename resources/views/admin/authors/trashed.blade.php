@extends('layouts.admin.app')
@section('title', 'Trashed Authors')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Trashed Authors</span>
        </h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.authors.index') }}" class="btn btn-dark">
                <i class="bx bx-arrow-back"></i> Back to Authors
            </a>
        </div>

        <div class="card">
            <h5 class="card-header">Deleted Authors List</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Alt Contact</th>
                            <th>Gender</th>
                            <th>Bio</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @forelse ($authors as $index => $author)
                            <tr>
                                <td>{{ ($authors->currentPage() - 1) * $authors->perPage() + ($index + 1) }}</td>
                                <td>{{ $author->full_name }}</td>
                                <td>{{ $author->email }}</td>
                                <td>{{ $author->contact_no }}</td>
                                <td>{{ $author->alt_contact_no ?? '-' }}</td>
                                <td class="text-capitalize">{{ $author->gender }}</td>
                                <td>{{ Str::limit($author->bio, 100) }}</td>


                                <td class="text-center">
                                    @if ($author->status == 'active')
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>


                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">

                                            <a class="dropdown-item text-success" href="javascript:void(0)"
                                                data-bs-toggle="modal" data-bs-target="#restoreModal{{ $author->id }}">
                                                <i class="bx bx-refresh me-1"></i> Restore
                                            </a>


                                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                data-bs-toggle="modal"
                                                data-bs-target="#forceDeleteModal{{ $author->id }}">
                                                <i class="bx bx-trash me-1"></i> Delete Permanently
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <div class="modal fade" id="restoreModal{{ $author->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title text-success">Restore Author</h5>
                                        </div>

                                        <div class="modal-body">
                                            Are you sure you want to restore
                                            <strong>{{ $author->full_name }}</strong>?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <form method="POST"
                                                action="{{ route('admin.authors.restore', $author->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bx bx-refresh"></i> Restore
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" id="forceDeleteModal{{ $author->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Permanent Delete</h5>
                                        </div>

                                        <div class="modal-body">
                                            This action cannot be undone! <br>
                                            Permanently delete
                                            <strong>{{ $author->full_name }}</strong>?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <form method="POST"
                                                action="{{ route('admin.authors.permanent-delete', $author->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bx bx-trash"></i> Yes, Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-danger">
                                    <i class="bx bx-error-circle me-2"></i> No Trashed Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($authors->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $authors->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
