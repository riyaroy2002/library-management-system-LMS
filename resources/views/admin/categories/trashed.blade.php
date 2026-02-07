@extends('layouts.admin.app')
@section('title', 'Trashed Categories')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Trashed Categories</span>
        </h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-dark">
                <i class="bx bx-arrow-back"></i> Back to Categories
            </a>
        </div>

        <div class="card">
            <h5 class="card-header">Deleted Categories List</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @forelse ($categories as $index => $category)
                            <tr>

                                <td>
                                    {{ ($categories->currentPage() - 1) * $categories->perPage() + ($index + 1) }}
                                </td>

                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ Str::limit($category->description, 80) ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($category->status === 'active')
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">


                                            <a class="dropdown-item text-success" href="javascript:void(0)"
                                                data-bs-toggle="modal" data-bs-target="#restoreModal{{ $category->id }}">
                                                <i class="bx bx-refresh me-1"></i> Restore
                                            </a>

                                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                data-bs-toggle="modal"
                                                data-bs-target="#forceDeleteModal{{ $category->id }}">
                                                <i class="bx bx-trash me-1"></i> Delete Permanently
                                            </a>

                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <div class="modal fade" id="restoreModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title text-success">Confirm Restore</h5>

                                        </div>

                                        <div class="modal-body">
                                            Restore category <strong>{{ $category->name }}</strong>?
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-dark" data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <form action="{{ route('admin.categories.restore', $category->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bx bx-refresh"></i> Restore
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" id="forceDeleteModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Permanent Delete</h5>

                                        </div>

                                        <div class="modal-body">
                                            This action cannot be undone.<br>
                                            Permanently delete <strong>{{ $category->name }}</strong>?
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-dark" data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <form action="{{ route('admin.categories.permanent-delete', $category->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bx bx-trash"></i> Yes , Delete
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

            @if ($categories->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
