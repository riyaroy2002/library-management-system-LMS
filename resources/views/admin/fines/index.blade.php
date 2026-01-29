@extends('layouts.admin.app')
@section('title', 'Fines')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage Fines</span>
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

        <div class="card">
            <h5 class="card-header">Fines List</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Issued Book</th>
                            <th>Amount</th>
                            <th>Late Days</th>
                            <th>Paid Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            

                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.books.edit', 1) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>

                                        <a class="dropdown-item" href="{{ route('admin.books.delete', 1) }}">
                                            <i class="bx bx-trash-alt me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="12" class="text-center text-danger">
                                <i class="bx bx-error-circle me-2"></i> No Data Found
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <hr class="my-5" />

    </div>
@endsection


