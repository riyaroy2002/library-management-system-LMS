@extends('layouts.admin.app')
@section('title', 'Gallery')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage Galleries</span>
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

        <div class="d-flex justify-content-end gap-2 mb-3">
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-success">
                <i class="bx bx-plus"></i> Add Gallery
            </a>

            <a href="{{ route('admin.gallery.trash') }}" class="btn btn-danger">
                <i class="bx bx-trash"></i> Trashed Gallery
            </a>
        </div>

        <div class="card">
            <h5 class="card-header">Galleries List</h5>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @forelse($gallery as $index => $item)
                            <tr>
                                <td>
                                    {{ ($gallery->currentPage() - 1) * $gallery->perPage() + $index + 1 }}
                                </td>

                                <td>{{ $item->title }}</td>

                                <td>{{ Str::limit($item->description, 80) }}</td>

                                <td class="text-center">
                                    @if ($item->image_url)
                                        <img src="{{ $item->image_url }}"
                                            alt="{{ $item->title }}"
                                            class="rounded"
                                            style="width:70px;height:50px;object-fit:cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.gallery.edit', $item->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>

                                            <button type="button"
                                                class="dropdown-item text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $item->id }}">
                                                <i class="bx bx-trash-alt me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>


                                    <div class="modal fade"
                                        id="deleteModal{{ $item->id }}"
                                        tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Gallery</h5>

                                                </div>

                                                <div class="modal-body">
                                                    Are you sure you want to delete
                                                    <strong>{{ $item->title }}</strong> ?
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button"
                                                        class="btn btn-dark"
                                                        data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                    <form action="{{ route('admin.gallery.delete', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">
                                                            Yes, Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-danger">
                                    <i class="bx bx-error-circle me-2"></i> No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($gallery->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $gallery->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
