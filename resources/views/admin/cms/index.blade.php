@extends('layouts.admin.app')
@section('title', 'CMS')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage CMS</span>
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


        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.cms.create') }}" class="btn btn-success">
                <i class="bx bx-plus"></i> Add CMS
            </a>
        </div>

        <div class="card">
            <h5 class="card-header">CMS List</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Text Content</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($cms as $index => $item)
                            <tr>
                                <td>
                                    {{ ($cms->currentPage() - 1) * $cms->perPage() + ($index + 1) }}
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>{{ Str::limit($item->text_content, 50) }}</td>

                                <td class="text-center">
                                    @if ($item->status == 'published')
                                        <span class="badge bg-label-success me-1">Published</span>
                                    @else
                                        <span class="badge bg-label-warning me-1">Draft</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.cms.edit', $item->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
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
            @if ($cms->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $cms->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection
