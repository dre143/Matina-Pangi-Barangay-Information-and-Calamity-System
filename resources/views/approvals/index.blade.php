@extends('layouts.app')

@section('title', 'Pending Approvals')

@section('content')
<div class="section-offset">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clock-history"></i> Pending Approvals</h2>
    <a href="{{ route('archived.index') }}" class="btn btn-secondary">
        <i class="bi bi-archive"></i> View Archived Records
    </a>
</div>

<!-- Pending Residents -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-people"></i> Pending Residents ({{ $pendingResidents->total() }})</h5>
    </div>
    <div class="card-body">
        @if($pendingResidents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Household</th>
                            <th>Registered By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingResidents as $resident)
                        <tr>
                            <td><strong>{{ $resident->resident_id }}</strong></td>
                            <td>{{ $resident->full_name }}</td>
                            <td>{{ $resident->age }} / {{ ucfirst($resident->sex) }}</td>
                            <td>
                                <a href="{{ route('households.show', $resident->household) }}">
                                    {{ $resident->household->household_id }}
                                </a>
                            </td>
                            <td>{{ $resident->creator ? $resident->creator->name : 'N/A' }}</td>
                            <td>{{ $resident->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('residents.show', $resident) }}" 
                                       class="btn btn-primary" title="View">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form action="{{ route('approvals.resident.approve', $resident) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectResidentModal{{ $resident->id }}"
                                            title="Reject">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                </div>
                                
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectResidentModal{{ $resident->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('approvals.resident.reject', $resident) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Resident</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject <strong>{{ $resident->full_name }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject & Archive</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $pendingResidents->links() }}
            </div>
        @else
            <p class="text-muted text-center mb-0">No pending residents.</p>
        @endif
    </div>
</div>

<!-- Pending Households -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-house"></i> Pending Households ({{ $pendingHouseholds->total() }})</h5>
    </div>
    <div class="card-body">
        @if($pendingHouseholds->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Household ID</th>
                            <th>Address</th>
                            <th>Head</th>
                            <th>Members</th>
                            <th>Registered By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingHouseholds as $household)
                        <tr>
                            <td><strong>{{ $household->household_id }}</strong></td>
                            <td>{{ $household->full_address }}</td>
                            <td>{{ $household->head ? $household->head->full_name : 'N/A' }}</td>
                            <td>{{ $household->total_members }}</td>
                            <td>{{ $household->head && $household->head->creator ? $household->head->creator->name : 'N/A' }}</td>
                            <td>{{ $household->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('households.show', $household) }}" 
                                       class="btn btn-primary" title="View">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form action="{{ route('approvals.household.approve', $household) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectHouseholdModal{{ $household->id }}"
                                            title="Reject">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                </div>
                                
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectHouseholdModal{{ $household->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('approvals.household.reject', $household) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Household</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject household <strong>{{ $household->household_id }}</strong>?</p>
                                                    <p class="text-danger small">This will also reject all {{ $household->total_members }} member(s).</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject & Archive</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $pendingHouseholds->links() }}
            </div>
        @else
            <p class="text-muted text-center mb-0">No pending households.</p>
        @endif
    </div>
</div>
</div>
@endsection
