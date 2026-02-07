@extends('layouts.admin.app')
@section('title', 'Members')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">Manage Members</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif



        <div class="card">
            <h5 class="card-header">Members List</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Alt Contact No</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Verified</th>

                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($members as $index => $member)
                            <tr>

                                <td>
                                    {{ ($members->currentPage() - 1) * $members->perPage() + ($index + 1) }}
                                </td>

                                <td>{{ $member->user->full_name }} </td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->contact_no }}</td>
                                <td>{{ $member->alt_contact_no }}</td>
                                <td>{{ ucfirst($member->gender) }}</td>
                                <td>{{ ($member->date_of_birth)->format('d/m/Y')?? '-' }}</td>


                                <td class="text-center">
                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                        data-bs-target="#statusModal{{ $member->user->id }}">
                                        @if ($member->user->is_block == 0)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-danger">Blocked</span>
                                        @endif
                                    </a>
                                </td>

                                <td class="text-center">
                                    @if ($member->user->is_verified == 1)
                                        <span class="badge bg-label-success">Verified</span>
                                    @else
                                        <span class="badge bg-label-warning">Pending</span>
                                    @endif
                                </td>

                            </tr>


                            <div class="modal fade" id="statusModal{{ $member->user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Change Status</h5>
                                        </div>

                                        <div class="modal-body">
                                            Are you sure you want to
                                            <strong>
                                                {{ $member->user->is_block == 1 ? 'Active' : 'Block' }}
                                            </strong>
                                            this member?
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>

                                            <form action="{{ route('admin.members.toggle-member', $member->user->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-danger">
                                                    Yes, Confirm
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>



                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-danger">
                                    <i class="bx bx-error-circle"></i> No Members Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            @if ($members->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
