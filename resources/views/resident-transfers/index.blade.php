@extends('layouts.app')

@section('title', 'Resident Transfers')

@push('styles')
<style></style>
@endpush

@section('content')
<div class="section-offset">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-arrow-left-right"></i> Resident Transfers</h2>
    <div class="btn-group">
        @if(auth()->user()->isSecretary())
        <a href="{{ route('resident-transfers.pending') }}" class="btn btn-warning">
            <i class="bi bi-clock-history"></i> Pending Approvals
        </a>
        @endif
        <a href="{{ route('resident-transfers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Request Transfer
        </a>
    </div>
</div>

@php
$transferSearchFields = [
    [
        'name' => 'search',
        'type' => 'text',
        'label' => 'Resident Name',
        'placeholder' => 'Search by resident name...',
        'col' => 4
    ],
    [
        'name' => 'status',
        'type' => 'select',
        'label' => 'Status',
        'placeholder' => 'All Status',
        'options' => [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'completed' => 'Completed',
            'rejected' => 'Rejected'
        ],
        'col' => 3
    ],
    [
        'name' => 'type',
        'type' => 'select',
        'label' => 'Transfer Type',
        'placeholder' => 'All Types',
        'options' => [
            'internal' => 'Internal Transfer',
            'external' => 'External Transfer'
        ],
        'col' => 3
    ],
    [
        'name' => 'reason',
        'type' => 'select',
        'label' => 'Reason',
        'placeholder' => 'All Reasons',
        'options' => [
            'marriage' => 'Marriage',
            'work' => 'Work/Employment',
            'education' => 'Education',
            'family' => 'Family Reasons',
            'housing' => 'Housing',
            'other' => 'Other'
        ],
        'col' => 2
    ]
];
@endphp

<x-search-filter 
    :route="route('resident-transfers.index')" 
    title="Search & Filter Resident Transfers"
    icon="bi-arrow-left-right"
    :fields="$transferSearchFields"
    :advanced="true">
    
    <x-slot name="advancedSlot">
        <div class="col-md-3">
            <label class="form-label small">Transfer Date From</label>
            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Transfer Date To</label>
            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Processed By</label>
            <select class="form-select" name="processed_by">
                <option value="">All Staff</option>
                @if(isset($staff))
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" {{ request('processed_by') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">From Purok</label>
            <select class="form-select" name="from_purok">
                <option value="">All Puroks</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="Purok {{ $i }}" {{ request('from_purok') == "Purok $i" ? 'selected' : '' }}>Purok {{ $i }}</option>
                @endfor
            </select>
        </div>
    </x-slot>
</x-search-filter>

<!-- Transfers Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table transfer-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-person"></i> Resident</th>
                        <th><i class="bi bi-house-door"></i> From</th>
                        <th><i class="bi bi-house-check"></i> To</th>
                        <th><i class="bi bi-tag"></i> Type</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th><i class="bi bi-info-circle"></i> Status</th>
                        <th><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $transfer->resident->full_name }}</strong>
                                        <small class="text-muted">{{ $transfer->resident->resident_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($transfer->oldHousehold)
                                    <strong class="text-danger">{{ $transfer->oldHousehold->household_id }}</strong><br>
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $transfer->old_purok }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($transfer->transfer_type === 'transfer_in' && $transfer->newHousehold)
                                    <strong class="text-success">{{ $transfer->newHousehold->household_id }}</strong><br>
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $transfer->new_purok ?? $transfer->newHousehold->purok }}</small>
                                @elseif($transfer->transfer_type === 'transfer_out')
                                    <span class="badge bg-warning">External Location</span><br>
                                    <small class="text-muted">{{ $transfer->destination_barangay ?? 'Outside Barangay' }}</small>
                                @else
                                    <span class="text-muted">Pending Assignment</span>
                                @endif
                            </td>
                            <td>
                                @if($transfer->transfer_type === 'transfer_in')
                                    <span class="badge bg-info"><i class="bi bi-arrow-left-right"></i> Internal</span>
                                @elseif($transfer->transfer_type === 'transfer_out')
                                    <span class="badge bg-warning"><i class="bi bi-box-arrow-right"></i> External</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>
                                <i class="bi bi-calendar-event"></i>
                                {{ $transfer->transfer_date->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $transfer->transfer_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($transfer->status === 'pending')
                                    <span class="status-badge pending">
                                        <i class="bi bi-clock-history"></i> Pending
                                    </span>
                                @elseif($transfer->status === 'approved')
                                    <span class="status-badge approved">
                                        <i class="bi bi-check-circle"></i> Approved
                                    </span>
                                @elseif($transfer->status === 'completed')
                                    <span class="status-badge completed">
                                        <i class="bi bi-check-circle-fill"></i> Completed
                                    </span>
                                @else
                                    <span class="status-badge rejected">
                                        <i class="bi bi-x-circle"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <a href="{{ route('resident-transfers.show', $transfer) }}" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h5>No Transfer Records Found</h5>
                                    <p class="text-muted">There are no resident transfer records matching your criteria.</p>
                                    <a href="{{ route('resident-transfers.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle"></i> Create New Transfer Request
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transfers->hasPages())
        <div class="mt-3">
            {{ $transfers->links() }}
        </div>
        @endif
    </div>
</div>
</div>
@endsection
