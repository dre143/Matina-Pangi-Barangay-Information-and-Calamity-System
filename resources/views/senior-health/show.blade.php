@extends('layouts.app')

@section('title', 'Senior Health Record Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-person-cane"></i> Senior Health Record</h2>
    <div class="btn-group">
        <a href="{{ route('senior-health.edit', $seniorHealth) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('senior-health.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Senior Citizen Information</h5>
            </div>
            <div class="card-body">
                <h4>{{ $seniorHealth->resident->full_name }}</h4>
                <p class="text-muted">{{ $seniorHealth->resident->age }} years old</p>
                <a href="{{ route('residents.show', $seniorHealth->resident) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> View Full Profile
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clipboard-pulse"></i> Health Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mobility Status:</strong>
                        <p>
                            @if($seniorHealth->mobility_status == 'independent')
                                <span class="badge bg-success">Independent</span>
                            @elseif($seniorHealth->mobility_status == 'assisted')
                                <span class="badge bg-info">Assisted</span>
                            @elseif($seniorHealth->mobility_status == 'wheelchair')
                                <span class="badge bg-warning">Wheelchair</span>
                            @else
                                <span class="badge bg-danger">Bedridden</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Last Checkup:</strong>
                        <p>{{ $seniorHealth->last_checkup_date ? $seniorHealth->last_checkup_date->format('F d, Y') : 'Not recorded' }}</p>
                    </div>
                </div>

                @if($seniorHealth->health_conditions)
                    <div class="mb-3">
                        <strong>Health Conditions:</strong>
                        <p>{{ $seniorHealth->health_conditions }}</p>
                    </div>
                @endif

                @if($seniorHealth->medications)
                    <div class="mb-3">
                        <strong>Current Medications:</strong>
                        <p>{{ $seniorHealth->medications }}</p>
                    </div>
                @endif

                @if($seniorHealth->notes)
                    <div class="mb-3">
                        <strong>Additional Notes:</strong>
                        <p>{{ $seniorHealth->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('senior-health.destroy', $seniorHealth) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
