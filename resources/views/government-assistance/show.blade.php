@extends('layouts.app')

@section('title', 'Government Assistance Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gift"></i> Government Assistance Details</h2>
    <div class="btn-group">
        <a href="{{ route('government-assistance.edit', $governmentAssistance) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('government-assistance.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Program Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Program Name:</strong>
                        <p>{{ $governmentAssistance->program_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Program Type:</strong>
                        <p><span class="badge bg-primary">{{ strtoupper($governmentAssistance->program_type) }}</span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Recipient:</strong>
                        <p>{{ $governmentAssistance->resident->full_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            @if($governmentAssistance->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($governmentAssistance->status == 'completed')
                                <span class="badge bg-info">Completed</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Amount:</strong>
                        <p>{{ $governmentAssistance->amount ? '₱' . number_format($governmentAssistance->amount, 2) : 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Date Received:</strong>
                        <p>{{ $governmentAssistance->date_received ? $governmentAssistance->date_received->format('F d, Y') : 'Not recorded' }}</p>
                    </div>
                </div>

                @if($governmentAssistance->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $governmentAssistance->description }}</p>
                    </div>
                @endif

                @if($governmentAssistance->notes)
                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <p>{{ $governmentAssistance->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Recipient Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong><br>{{ $governmentAssistance->resident->full_name }}</p>
                <p><strong>Address:</strong><br>{{ $governmentAssistance->resident->household->address }}</p>
                <a href="{{ route('residents.show', $governmentAssistance->resident) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> View Profile
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('government-assistance.destroy', $governmentAssistance) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Delete Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
