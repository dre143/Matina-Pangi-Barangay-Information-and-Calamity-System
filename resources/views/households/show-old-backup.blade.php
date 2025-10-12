@extends('layouts.app')

@section('title', 'Household Details')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('households.index') }}">Households</a></li>
            <li class="breadcrumb-item active">{{ $household->household_id }}</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-house"></i> Household Details</h2>
    <div>
        @if(auth()->user()->isSecretary())
            <a href="{{ route('households.add-member', $household) }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Add Member
            </a>
            <a href="{{ route('households.edit', $household) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        @endif
        <a href="{{ route('households.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Household Information -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Household Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Household ID</label>
                        <p class="mb-0 fw-bold">{{ $household->household_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Household Type</label>
                        <p class="mb-0">
                            <span class="badge bg-info">{{ ucfirst($household->household_type) }}</span>
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label class="text-muted small">Complete Address</label>
                        <p class="mb-0">{{ $household->full_address }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Housing Type</label>
                        <p class="mb-0">{{ ucfirst($household->housing_type) }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Electricity</label>
                        <p class="mb-0">
                            @if($household->has_electricity)
                                <i class="bi bi-lightning-fill text-warning"></i> Yes
                                @if($household->electric_account_number)
                                    <br><small class="text-muted">{{ $household->electric_account_number }}</small>
                                @endif
                            @else
                                <i class="bi bi-x-circle text-danger"></i> No
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Total Members</label>
                        <p class="mb-0">
                            <span class="badge bg-primary">{{ $household->total_members }}</span>
                        </p>
                    </div>
                    @if($household->parentHousehold)
                        <div class="col-md-12">
                            <label class="text-muted small">Parent Household</label>
                            <p class="mb-0">
                                <a href="{{ route('households.show', $household->parentHousehold) }}">
                                    {{ $household->parentHousehold->household_id }} - 
                                    {{ $household->parentHousehold->head ? $household->parentHousehold->head->full_name : 'N/A' }}
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-cash"></i> Household Income</h5>
            </div>
            <div class="card-body">
                <h3 class="mb-0">₱{{ number_format($household->total_income, 2) }}</h3>
                <p class="text-muted small mb-0">Total Monthly Income</p>
                <hr>
                <p class="mb-0">
                    <strong>Average per Member:</strong><br>
                    ₱{{ number_format($household->total_members > 0 ? $household->total_income / $household->total_members : 0, 2) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Household Members -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-people"></i> Household Members</h5>
    </div>
    <div class="card-body">
        @if($household->residents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Civil Status</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($household->residents as $resident)
                        <tr>
                            <td>{{ $resident->resident_id }}</td>
                            <td>
                                <a href="{{ route('residents.show', $resident) }}">
                                    {{ $resident->full_name }}
                                </a>
                                @if($resident->is_household_head)
                                    <span class="badge bg-success">Head</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($resident->household_role) }}</td>
                            <td>{{ $resident->age }}</td>
                            <td>{{ ucfirst($resident->sex) }}</td>
                            <td>{{ ucfirst($resident->civil_status) }}</td>
                            <td>
                                @if($resident->is_pwd)
                                    <span class="badge bg-info">PWD</span>
                                @endif
                                @if($resident->is_senior_citizen)
                                    <span class="badge bg-warning">Senior</span>
                                @endif
                                @if($resident->is_voter)
                                    <span class="badge bg-success">Voter</span>
                                @endif
                                @if($resident->is_4ps_beneficiary)
                                    <span class="badge bg-primary">4Ps</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('residents.show', $resident) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center mb-0">No members found.</p>
        @endif
    </div>
</div>

<!-- Child Households -->
@if($household->childHouseholds->count() > 0)
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Child Households (New Family Heads)</h5>
    </div>
    <div class="card-body">
        <div class="list-group">
            @foreach($household->childHouseholds as $child)
                <a href="{{ route('households.show', $child) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $child->household_id }}</strong> - 
                            {{ $child->head ? $child->head->full_name : 'N/A' }}
                            <br>
                            <small class="text-muted">{{ $child->full_address }}</small>
                        </div>
                        <span class="badge bg-primary">{{ $child->total_members }} member(s)</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Audit Information -->
<div class="card mt-3">
    <div class="card-body">
        <small class="text-muted">
            <strong>Registered:</strong> {{ $household->created_at->format('F d, Y h:i A') }}
            <span class="mx-2">|</span>
            <strong>Last Updated:</strong> {{ $household->updated_at->format('F d, Y h:i A') }}
        </small>
    </div>
</div>
@endsection
