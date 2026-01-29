@extends('layouts.admin.app')
@section('title', 'News Letters')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-dark fw-bold">Manage News Letters</span>
        </h4>

        <div class="card">
            <h5 class="card-header">Newsletter Subscribers</h5>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Subscribed At</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @forelse ($newsletters as $index => $item)
                            <tr>
                                <td>
                                    {{ ($newsletters->currentPage() - 1) * $newsletters->perPage() + $index + 1 }}
                                </td>

                                <td>{{ $item->email }}</td>

                                <td>
                                    @if ($item->is_subscribed)
                                        <span class="badge bg-label-success">
                                            Subscribed
                                        </span>
                                    @else
                                        <span class="badge bg-label-danger">
                                            Unsubscribed
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->subscribed_at?->format('d M Y, h:i A') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">
                                    <i class="bx bx-error-circle me-1"></i> No Subscribers Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($newsletters->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $newsletters->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
