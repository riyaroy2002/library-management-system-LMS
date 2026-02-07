@extends('layouts.admin.app')

@section('title', 'Create Book')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Create Book</h5>

            <div class="card-body">


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Authors <span class="text-danger">*</span></label>
                            <select name="authors[]" class="form-select @error('authors') is-invalid @enderror" multiple>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ in_array($author->id, old('authors', [])) ? 'selected' : '' }}>
                                        {{ $author->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('authors')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categories <span class="text-danger">*</span></label>
                            <select name="categories[]" class="form-select @error('categories') is-invalid @enderror"
                                multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="col-md-4 mb-3">
                            <label class="form-label">Book Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Enter book name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" placeholder="Enter title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug') }}" readonly>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="ISBN" class="form-control @error('ISBN') is-invalid @enderror"
                                value="{{ old('ISBN') }}" placeholder="ISBN number">
                            @error('ISBN')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">Publish Year</label>
                            <input type="text" name="publish_year"
                                class="form-control @error('publish_year') is-invalid @enderror"
                                value="{{ old('publish_year') }}" placeholder="YYYY"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,4);">
                            @error('publish_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Edition</label>
                            <input type="text" name="edition" class="form-control @error('edition') is-invalid @enderror"
                                value="{{ old('edition') }}" placeholder="Edition">
                            @error('edition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Languages <span class="text-danger">*</span></label>
                            @php
                                $allLanguages = ['English', 'Spanish', 'French', 'German', 'Bengali'];
                                $selectedLanguages = old(
                                    'languages',
                                    isset($book) ? json_decode($book->languages, true) : [],
                                );
                            @endphp

                            <select name="languages[]" class="form-select @error('languages') is-invalid @enderror"
                                multiple>
                                @foreach ($allLanguages as $lang)
                                    <option value="{{ $lang }}"
                                        {{ in_array($lang, $selectedLanguages) ? 'selected' : '' }}>
                                        {{ $lang }}
                                    </option>
                                @endforeach
                            </select>

                            @error('languages')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Copies <span class="text-danger">*</span></label>
                            <input type="text" name="total_copies"
                                class="form-control @error('total_copies') is-invalid @enderror"
                                value="{{ old('total_copies') }}">
                            @error('total_copies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cover Image</label>
                            <input type="file" name="cover_image"
                                class="form-control @error('cover_image') is-invalid @enderror">
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.books.index') }}" class="btn btn-dark">Back</a>
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
        document.querySelector('input[name="title"]').addEventListener('keyup', function() {
            document.querySelector('input[name="slug"]').value =
                this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        });
    </script>
@endpush
