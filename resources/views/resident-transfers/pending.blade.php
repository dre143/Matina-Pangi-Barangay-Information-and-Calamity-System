@extends('layouts.app')

@section('title', 'Pending Transfer Approvals')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-clock-history"></i> Pending Transfer Approvals</h2>
    <a href="{{ route('resident-transfers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> All Transfers
    </a>
</div>

@if($pendingTransfers->count() > 0)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle"></i>
    <strong>{{ $pendingTransfers->total() }}</strong> transfer request(s) waiting for your approval.
</div>
@endif

<!-- Pending Transfers -->
<div class="row">
    @forelse($pendingTransfers as $transfer)
    <div class="col-md-6 mb-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="bi bi-person"></i> {{ $transfer->resident->full_name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">From:</small><br>
                        <strong>{{ $transfer->oldHousehold->household_id ?? 'N/A' }}</strong><br>
                        <small>{{ $transfer->old_purok }}</small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">To:</small><br>
                        @if($transfer->transfer_type === 'internal')
                            <strong>{{ $transfer->newHousehold->household_id ?? 'N/A' }}</strong><br>
                            <small>{{ $transfer->new_purok ?? $transfer->newHousehold->purok ?? '' }}</small>
                        @else
                            <strong class="text-warning">External Transfer</strong><br>
                            <small>{{ $transfer->destination_barangay }}</small>
                        @endif
                    </div>
                </div>

                <p class="mb-2">
                    <strong>Type:</strong>
                    @if($transfer->transfer_type === 'internal')
                        <span class="badge bg-info">Internal</span>
                    @else
                        <span class="badge bg-warning">External</span>
                    @endif
                </p>

                <p class="mb-2"><strong>Transfer Date:</strong> {{ $transfer->transfer_date->format('M d, Y') }}</p>
                <p class="mb-2"><strong>Reason:</strong> {{ ucfirst($transfer->reason) }}</p>
                
                <div class="bg-light p-2 rounded mb-3">
                    <small><strong>Details:</strong></small><br>
                    <small>{{ Str::limit($transfer->reason_for_transfer ?? $transfer->reason_details ?? 'No details', 100) }}</small>
                </div>

                <p class="mb-2">
                    <small class="text-muted">
                        <i class="bi bi-person"></i> Requested by: {{ $transfer->creator->name ?? 'N/A' }}<br>
                        <i class="bi bi-clock"></i> {{ $transfer->created_at->diffForHumans() }}
                    </small>
                </p>

                <div class="d-flex gap-2">
                    <a href="{{ route('resident-transfers.show', $transfer) }}" class="btn btn-sm btn-primary flex-fill">
                        <i class="bi bi-eye"></i> View Details
                    </a>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $transfer->id }}">
                        <i class="bi bi-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $transfer->id }}">
                        <i class="bi bi-x"></i> Reject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal for this transfer -->
    <div class="modal fade" id="approveModal{{ $transfer->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('resident-transfers.approve', $transfer) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Approve transfer for <strong>{{ $transfer->resident->full_name }}</strong>?</p>
                        <div class="alert alert-info">
                            <small>
                                @if($transfer->transfer_type === 'internal')
                                    This will move the resident to {{ $transfer->newHousehold->household_id ?? 'the new household' }}.
                                @else
                                    This will mark the resident as relocated and archive their record.
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal for this transfer -->
    <div class="modal fade" id="rejectModal{{ $transfer->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('resident-transfers.reject', $transfer) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Reject transfer for <strong>{{ $transfer->resident->full_name }}</strong>?</p>
                        <label class="form-label">Reason for rejection:</label>
                        <textarea name="remarks" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Pending Transfers</h4>
                <p class="text-muted">All transfer requests have been processed.</p>
                <a href="{{ route('resident-transfers.index') }}" class="btn btn-primary">
                    <i class="bi bi-list"></i> View All Transfers
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($pendingTransfers->hasPages())
<div class="mt-4">
    {{ $pendingTransfers->links() }}
</div>
@endif
@endsection
