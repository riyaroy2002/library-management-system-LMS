@extends('layouts.admin.app')
@section('title', 'Edit Author')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Edit Author</h5>


            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.authors.update', $author->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name', $author->first_name) }}">

                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $author->last_name) }}">

                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Contact No <span class="text-danger">*</span></label>
                            <input type="text" name="contact_no"
                                class="form-control @error('contact_no') is-invalid @enderror"
                                value="{{ old('contact_no', $author->contact_no) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">

                            @error('contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Alt Contact No</label>
                            <input type="text" name="alt_contact_no"
                                class="form-control @error('alt_contact_no') is-invalid @enderror"
                                value="{{ old('alt_contact_no', $author->alt_contact_no) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">

                            @error('alt_contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $author->email) }}">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender', $author->gender) == 'male' ? 'selected' : '' }}>
                                    Male
                                </option>
                                <option value="female" {{ old('gender', $author->gender) == 'female' ? 'selected' : '' }}>
                                    Female
                                </option>
                                <option value="other" {{ old('gender', $author->gender) == 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>

                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug"
                                class="form-control @error('slug') is-invalid @enderror" readonly
                                value="{{ old('slug', $author->slug) }}">

                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $author->bio) }}</textarea>

                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.authors.index') }}" class="btn btn-dark">Back</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bx bx-save"></i> Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.querySelector('input[name="first_name"]').addEventListener('input', generateSlug);
        document.querySelector('input[name="last_name"]').addEventListener('input', generateSlug);

        function generateSlug() {
            let first = document.querySelector('input[name="first_name"]').value;
            let last = document.querySelector('input[name="last_name"]').value;

            document.getElementById('slug').value =
                (first + ' ' + last)
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }
    </script>
@endpush
