@extends('layouts.admin.app')
@section('title', 'Edit Librarian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Edit Librarian</h5>

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

                <form action="{{ route('admin.librarians.update', $librarian->id) }}" method="POST">
                    @csrf

                    <div class="row g-4">


                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control"
                                value="{{ old('first_name', $librarian->user?->first_name) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control"
                                value="{{ old('last_name', $librarian->user?->last_name) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Contact No.</label>
                            <input type="text" name="contact_no" class="form-control"
                                value="{{ old('contact_no', $librarian->contact_no) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Alt Contact</label>
                            <input type="text" name="alt_contact_no" class="form-control"
                                value="{{ old('alt_contact_no', $librarian->alt_contact_no) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email ID</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $librarian->user?->email) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male" {{ old('gender', $librarian->gender) == 'male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="female"
                                    {{ old('gender', $librarian->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $librarian->gender) == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth"
                                value="{{ old('date_of_birth', $librarian?->date_of_birth?->format('Y-m-d')) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Joining Date</label>
                            <input type="date" class="form-control" name="joining_date"
                                value="{{ old('joining_date', $librarian->joining_date?->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Address Line 1</label>
                            <input type="text" name="address_line1" class="form-control"
                                value="{{ old('address_line1', $librarian->address?->address_line1) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control"
                                value="{{ old('address_line2', $librarian->address?->address_line2) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control"
                                value="{{ old('city', $librarian->address?->city) }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <select class="form-select" name="state_id" id="state">
                                <option value="">Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id', $librarian->address?->state_id) == $state->id ? 'selected' : '' }}>
                                        {{ $state->state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">District</label>
                            <select class="form-select" name="district_id" id="district">
                                <option value="">Select District</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" class="form-control"
                                value="{{ old('pincode', $librarian->address?->pincode) }}">
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.librarians.index') }}" class="btn btn-dark">Back</a>
                        <button class="btn btn-warning"> <i class="bx bx-save"></i> Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            function loadDistrict(stateId, selectedDistrictId = null) {

                let $district = $('#district');
                $district.empty().append('<option value="">-- Select District --</option>');

                if (!stateId) return;

                $.ajax({
                    url: '/admin/district-librarian/' + stateId,
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

            window.editStateId = "{{ old('state_id', $librarian->address?->state_id) }}";
            window.editDistrictId = "{{ old('district_id', $librarian->address?->district_id) }}";

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
