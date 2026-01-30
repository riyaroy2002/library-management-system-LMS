@extends('layouts.frontend.app')
@section('title', 'Member Registration')
@section('content')
    <section class="wrapper bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="donor-card">
                        <div class="donor-header">
                            <h4 class="mb-0 text-white">Member Registration</h4>
                        </div>
                        <div class="donor-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form method="POST" action="{{ route('register-member.store') }}">
                                @csrf
                                <div class="row g-4">

                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control"
                                            placeholder="Enter first name" value="{{ old('first_name') }}">
                                        @error('first_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control"
                                            placeholder="Enter last name" value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contact No.</label>
                                        <input type="text" name="contact_no" class="form-control"
                                            placeholder="Enter contact no." value="{{ old('contact_no') }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                                        @error('contact_no')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Alt. Contact No.</label>
                                        <input type="text" name="alt_contact_no" class="form-control"
                                            placeholder="Enter alt. contact no." value="{{ old('alt_contact_no') }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                                        @error('alt_contact_no')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Enter email address" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">-- Select Gender --</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                        @error('gender')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control"
                                            value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-12">
                                        <label class="form-label">Address Line 1</label>
                                        <input type="text" name="address_line1" class="form-control"
                                            placeholder="House no, street name" value="{{ old('address_line1') }}">
                                        @error('address_line1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Address Line 2</label>
                                        <input type="text" name="address_line2" class="form-control"
                                            placeholder="Landmark (optional)" value="{{ old('address_line2') }}">
                                        @error('address_line2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control"
                                            placeholder="Enter city name" value="{{ old('city') }}">
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">State</label>
                                        <select class="form-select" name="state_id" id="state">
                                            <option value="">-- Select State --</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                    {{ $state->state }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">District</label>
                                        <select class="form-select" name="district_id" id="district">
                                            <option value="">-- Select District --</option>
                                        </select>
                                        @error('district_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Pin Code</label>
                                        <input type="text" name="pincode" class="form-control"
                                            placeholder="Enter pincode" value="{{ old('pincode') }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);">
                                        @error('pincode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="New password">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Confirm password">
                                        @error('password_confirmation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-3">
                                        <div class="form-check">
                                            <input type="hidden" name="check_term" value="0">
                                            <input class="form-check-input" type="checkbox" name="check_term"
                                                value="1" {{ old('check_term') == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                I confirm that the details provided are correct.
                                            </label>
                                            <br>
                                            @error('check_term')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-dark submit-btn">
                                            Register <i class="fa fa-check-circle ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            function loadDistrict(stateId, selectedDistrict = null) {
                let district = $('#district');
                district.html('<option value="">-- Select District --</option>');

                if (stateId) {
                    $.ajax({
                        url: '/state-district/' + stateId,
                        type: 'GET',
                        success: function(data) {
                            if (data.length === 0) {
                                district.append('<option value="">No district found</option>');
                            }

                            $.each(data, function(index, value) {
                                let selected = selectedDistrict == value.id ? 'selected' : '';
                                district.append(
                                    '<option value="' + value.id + '" ' + selected + '>' +
                                    value.name +
                                    '</option>'
                                );
                            });
                        },
                        error: function() {
                            alert('District load failed');
                        }
                    });
                }
            }

            $('#state').on('change', function() {
                loadDistrict($(this).val());
            });

            let oldState = "{{ old('state_id') }}";
            let oldDistrict = "{{ old('district_id') }}";

            if (oldState) {
                loadDistrict(oldState, oldDistrict);
            }

        });
    </script>
@endpush
