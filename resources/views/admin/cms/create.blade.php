@extends('layouts.admin.app')
@section('title', 'Create CMS')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Create CMS</h5>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('admin.cms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror" placeholder="Enter title"
                                value="{{ old('title') }}">

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" id="slug" name="slug"
                                class="form-control @error('slug') is-invalid @enderror" readonly
                                value="{{ old('slug') }}">

                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-12 mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="text_content" rows="5" class="form-control @error('text_content') is-invalid @enderror"
                                placeholder="Enter content">{{ old('text_content') }}</textarea>

                            @error('text_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">Upload File</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">

                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-8 mb-3">
                            <label class="form-label">Extra (JSON)</label>
                            <textarea name="extra" rows="3" class="form-control @error('extra') is-invalid @enderror"
                                placeholder='{"key":"value"}'>{{ old('extra') }}</textarea>

                            @error('extra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-dark">
                                Optional JSON data (for sections, meta, buttons etc.)
                            </small>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.cms.index') }}" class="btn btn-dark">
                            Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-save"></i> Save
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('title').addEventListener('input', function() {
            let title = this.value;
            let slug = title
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endpush
