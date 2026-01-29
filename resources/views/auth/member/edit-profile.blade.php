@extends('layouts.frontend.app')
@section('title', 'Edit Profile')

@section('content')

    @php
        $member = $user->members->first();
        $address = $member?->address;
    @endphp

    <section class="container my-5">
        <div class="row g-4">

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

            <div class="col-lg-9 mx-auto">
                <div class="profile-card p-4">

                    <h4 class="mb-4">Edit Profile</h4>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">

                            {{-- Profile Image --}}
                            <div class="col-md-3 text-center">
                                <img id="previewImage"
                                    src="{{ $user->image_url ? asset($user->image_url) : asset('assets/img/default-user.webp') }}"
                                    class="profile-img mb-2">

                                <label class="btn btn-black btn-sm w-100">
                                    Upload Image
                                    <input type="file" name="profile_image" class="d-none" accept="image/*"
                                        onchange="previewProfileImage(event)">
                                </label>

                                <div class="small text-dark mt-1">JPG, PNG, JPEG, WEBP (Max 2MB)</div>
                            </div>

                            {{-- Form Fields --}}
                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input class="form-control" name="first_name"
                                            value="{{ old('first_name', $user->first_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input class="form-control" name="last_name"
                                            value="{{ old('last_name', $user->last_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contact No</label>
                                        <input class="form-control" name="contact_no"
                                            value="{{ old('contact_no', $user->contact_no) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Alt Contact No</label>
                                        <input class="form-control" name="alt_contact_no"
                                            value="{{ old('alt_contact_no', $member?->alt_contact_no) }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $user->email) }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Address Line 1</label>
                                        <input class="form-control" name="address_line1"
                                            value="{{ old('address_line1', $address?->address_line1) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Address Line 2</label>
                                        <input class="form-control" name="address_line2"
                                            value="{{ old('address_line2', $address?->address_line2) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input class="form-control" name="city"
                                            value="{{ old('city', $address?->city) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">State</label>
                                        <select class="form-select" name="state_id" id="state">
                                            <option value="">-- Select State --</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ old('state_id', $address?->state_id) == $state->id ? 'selected' : '' }}>
                                                    {{ $state->state }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">District</label>
                                        <select class="form-select" name="district_id" id="district">
                                            <option value="">-- Select District --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pincode</label>
                                        <input class="form-control" name="pincode"
                                            value="{{ old('pincode', $address?->pincode) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select" name="gender">
                                            <option value="Male"
                                                {{ old('gender', $member?->gender) == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female"
                                                {{ old('gender', $member?->gender) == 'Female' ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="Other"
                                                {{ old('gender', $member?->gender) == 'Other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth"
                                            value="{{ old('date_of_birth', $member?->date_of_birth?->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-12 text-end mt-3">
                                        <button class="btn btn-black btn-sm">
                                            Update <i class="fa fa-check-circle ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        function previewProfileImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('previewImage').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <script>
        $(document).ready(function() {

            function loadDistrict(stateId, selectedDistrictId = null) {

                let $district = $('#district');
                $district.empty().append('<option value="">-- Select District --</option>');

                if (!stateId) return;

                $.ajax({
                    url: '/district/' + stateId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(districts) {

                        $.each(districts, function(i, district) {

                            let isSelected =
                                selectedDistrictId !== null &&
                                Number(selectedDistrictId) === Number(district.id);

                            $district.append(
                                $('<option>', {
                                    value: district.id,
                                    text: district.name,
                                    selected: isSelected
                                })
                            );
                        });
                    },
                    error: function() {
                        console.error('District load failed');
                    }
                });
            }

            $('#state').on('change', function() {
                loadDistrict(this.value);
            });

            window.editStateId = "{{ old('state_id', $address?->state_id) }}";
            window.editDistrictId = "{{ old('district_id', $address?->district_id) }}";

            if (editStateId) {
                loadDistrict(editStateId, editDistrictId);
            }

            window.reloadDistrictOnEdit = function() {
                if (editStateId) {
                    loadDistrict(editStateId, editDistrictId);
                }
            };
        });
    </script>
@endpush
