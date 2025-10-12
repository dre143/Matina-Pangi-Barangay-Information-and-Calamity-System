@extends('layouts.app')

@section('title', 'Health Record Details')

@section('content')
{{-- Cache Buster: {{ now()->timestamp }} --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-heart-pulse"></i> Health Record Details</h2>
    <div class="btn-group">
        <a href="{{ route('health-records.edit', $healthRecord) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('health-records.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Resident Information</h5>
            </div>
            <div class="card-body">
                <h4>{{ $healthRecord->resident->full_name }}</h4>
                <p class="text-muted">{{ $healthRecord->resident->age }} years old, {{ $healthRecord->resident->gender }}</p>
                <a href="{{ route('residents.show', $healthRecord->resident) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> View Full Profile
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clipboard-pulse"></i> Health Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Blood Type:</strong>
                        <p>{{ $healthRecord->blood_type ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Height:</strong>
                        <p>{{ $healthRecord->height ? $healthRecord->height . ' cm' : 'Not specified' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Weight:</strong>
                        <p>{{ $healthRecord->weight ? $healthRecord->weight . ' kg' : 'Not specified' }}</p>
                    </div>
                </div>

                @if($healthRecord->medical_conditions)
                    <div class="mb-3">
                        <strong>Medical Conditions:</strong>
                        <p>{{ $healthRecord->medical_conditions }}</p>
                    </div>
                @endif

                @if($healthRecord->allergies)
                    <div class="mb-3">
                        <strong>Allergies:</strong>
                        <p>{{ $healthRecord->allergies }}</p>
                    </div>
                @endif

                @if($healthRecord->medications)
                    <div class="mb-3">
                        <strong>Current Medications:</strong>
                        <p>{{ $healthRecord->medications }}</p>
                    </div>
                @endif

                @if($healthRecord->notes)
                    <div class="mb-3">
                        <strong>Additional Notes:</strong>
                        <p>{{ $healthRecord->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-telephone"></i> Emergency Contact</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong><br>{{ $healthRecord->emergency_contact ?? 'Not specified' }}</p>
                <p><strong>Phone:</strong><br>{{ $healthRecord->emergency_contact_number ?? 'Not specified' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-card-text"></i> PhilHealth</h5>
            </div>
            <div class="card-body">
                <p><strong>PhilHealth Number:</strong><br>{{ $healthRecord->philhealth_number ?? 'Not specified' }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('health-records.destroy', $healthRecord) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this health record?');">
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
