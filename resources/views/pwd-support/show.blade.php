@extends('layouts.app')

@section('title', 'PWD Record Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-universal-access"></i> PWD Record Details</h2>
    <div class="btn-group">
        <a href="{{ route('pwd-support.edit', $pwdSupport) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('pwd-support.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> PWD Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>PWD ID Number:</strong>
                        <p class="text-primary">{{ $pwdSupport->pwd_id_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Resident:</strong>
                        <p>{{ $pwdSupport->resident->full_name }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Disability Type:</strong>
                        <p><span class="badge bg-info">{{ ucfirst($pwdSupport->disability_type) }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Date Registered:</strong>
                        <p>{{ $pwdSupport->date_registered ? $pwdSupport->date_registered->format('F d, Y') : 'Not recorded' }}</p>
                    </div>
                </div>

                @if($pwdSupport->disability_description)
                    <div class="mb-3">
                        <strong>Disability Description:</strong>
                        <p>{{ $pwdSupport->disability_description }}</p>
                    </div>
                @endif

                @if($pwdSupport->assistance_received)
                    <div class="mb-3">
                        <strong>Assistance Received:</strong>
                        <p>{{ $pwdSupport->assistance_received }}</p>
                    </div>
                @endif

                @if($pwdSupport->medical_needs)
                    <div class="mb-3">
                        <strong>Medical Needs:</strong>
                        <p>{{ $pwdSupport->medical_needs }}</p>
                    </div>
                @endif

                @if($pwdSupport->notes)
                    <div class="mb-3">
                        <strong>Additional Notes:</strong>
                        <p>{{ $pwdSupport->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Resident Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong><br>{{ $pwdSupport->resident->full_name }}</p>
                <p><strong>Age:</strong><br>{{ $pwdSupport->resident->age }} years</p>
                <p><strong>Address:</strong><br>{{ $pwdSupport->resident->household->address }}</p>
                <a href="{{ route('residents.show', $pwdSupport->resident) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> View Full Profile
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pwd-support.destroy', $pwdSupport) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
