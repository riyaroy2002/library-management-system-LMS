@extends('layouts.admin.app')
@section('title', 'View Reply')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">View Reply</h5>

        <div class="card-body">
            <form>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ $contact->name }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $contact->email }}" readonly>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" value="{{ $contact->subject }}" readonly>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" readonly>{{ $contact->message }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Reply Message <span class="text-danger">*</span></label>
                        <textarea name="replyBack" rows="5"
                            class="form-control @error('replyBack') is-invalid @enderror"
                            placeholder="Write your reply here..." readonly>{{ old('replyBack', $contact->replyBack) }}</textarea>

                        @error('replyBack')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-dark">
                        Back
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
