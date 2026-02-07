@extends('layouts.admin.app')
@section('title', 'Authors')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage Authors</span>
        </h4>

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
            <a href="{{ route('admin.authors.create') }}" class="btn btn-success">
                <i class="bx bx-plus"></i> Add Author
            </a>
            <a href="{{ route('admin.authors.trash') }}" class="btn btn-danger">
                <i class="bx bx-trash"></i> Trashed Author
            </a>
        </div>

        <div class="card">
            <h5 class="card-header">Authors List</h5>

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
                                <td>{{ Str::limit($author->bio, 40) }}</td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.authors.edit', $author->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>

                                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $author->id }}">
                                                <i class="bx bx-trash-alt me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="deleteModal{{ $author->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Are you sure</h5>

                                        </div>

                                        <div class="modal-body">
                                             Want to delete
                                            <strong>{{ $author->full_name }}</strong>?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <form action="{{ route('admin.authors.delete', $author->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                   Yes, Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            @if ($authors->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $authors->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
