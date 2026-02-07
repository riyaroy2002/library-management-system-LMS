@extends('layouts.admin.app')
@section('title', 'Edit Gallery')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Edit Gallery</h5>
            <div class="card-body">
                <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                         <div class="col-md-6 mb-3">
                            <label class="form-label">Upload File</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($gallery->image_url)
                                <img src="{{ $gallery->image_url}}" alt="CMS Image"
                                    class="img-fluid mt-2" style="max-height: 100px;">
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Enter title" value="{{ old('title', $gallery->title) }}" id="title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter content">{{ old('description', $gallery->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-dark">
                            Back
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bx bx-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
