@extends('layouts.app')

@section('title', 'Household Details - ' . $household->household_id)

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

<!-- Header with Actions -->
<div class="d-flex justify-content-between align-items-end mb-2">
    <h2><i class="bi bi-house-door-fill"></i> Household <span class="no-wrap">{{ $household->household_id }}</span></h2>
    <div class="btn-group action-buttons" style="max-width: 100%;">
        <a href="{{ route('household-events.by-household', $household) }}" class="btn btn-info">
            <i class="bi bi-calendar-event"></i> View Events
        </a>
        <a href="{{ route('sub-families.create', ['household_id' => $household->id]) }}" class="btn btn-primary">
            <i class="bi bi-people-fill"></i> Add Extended Family
        </a>
        @if(auth()->user()->isSecretary() || auth()->user()->isStaff())
            <a href="{{ route('households.add-member', $household) }}" class="btn btn-success">
                <i class="bi bi-person-plus-fill"></i> Add Member
            </a>
        @endif
        @if(auth()->user()->isSecretary())
            <a href="{{ route('households.edit', $household) }}" class="btn btn-warning">
                <i class="bi bi-pencil-fill"></i> Edit
            </a>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#archiveModal">
                <i class="bi bi-archive-fill"></i> Archive
            </button>
        @endif
        <a href="{{ route('households.index') }}" class="btn btn-secondary btn-back">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Household Overview Card -->
<div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-0">
                    <i class="bi bi-house-fill"></i> 
                    <span class="no-wrap">{{ $household->household_id }}</span>
                    @if($household->officialHead)
                        - {{ $household->officialHead->full_name }}
                        <span class="badge bg-warning text-dark ms-2">
                            <i class="bi bi-star-fill"></i> PRIMARY HEAD
                        </span>
                    @endif
                </h4>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-light text-dark fs-6">
                    <i class="bi bi-people-fill"></i> {{ $statistics['total_residents'] }} Members
                </span>
                <span class="badge bg-light text-dark fs-6 ms-2">
                    <i class="bi bi-diagram-3-fill"></i> {{ $statistics['total_families'] }} Families
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- Address Information -->
            <div class="col-md-6">
                <h6 class="text-muted mb-2"><i class="bi bi-geo-alt-fill"></i> Address</h6>
                <p class="mb-0 fw-bold">{{ $household->address }}</p>
                @if($household->purok)
                    <p class="mb-0 text-muted">{{ $household->purok->purok_name ?? $household->purok }}</p>
                @endif
                <p class="mb-0 text-muted">Barangay Matina Pangi, Davao City</p>
            </div>

            <!-- Housing Details -->
            <div class="col-md-3">
                <h6 class="text-muted mb-2"><i class="bi bi-house-fill"></i> Housing</h6>
                <p class="mb-1">
                    <strong>Type:</strong> 
                    <span class="badge bg-{{ $household->housing_type === 'owned' ? 'success' : ($household->housing_type === 'rented' ? 'warning' : 'info') }}">
                        {{ ucfirst($household->housing_type) }}
                    </span>
                </p>
                <p class="mb-0">
                    <strong>Electricity:</strong> 
                    @if($household->has_electricity)
                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> Yes</span>
                        @if($household->electric_account_number)
                            <br><small class="text-muted">Acct: {{ $household->electric_account_number }}</small>
                        @endif
                    @else
                        <span class="text-danger"><i class="bi bi-x-circle-fill"></i> No</span>
                    @endif
                </p>
            </div>

            <!-- Status -->
            <div class="col-md-3">
                <h6 class="text-muted mb-2"><i class="bi bi-info-circle-fill"></i> Status</h6>
                <p class="mb-1">
                    <span class="badge bg-{{ $household->approval_badge_color }}">
                        {{ ucfirst($household->approval_status) }}
                    </span>
                </p>
                @if($household->approved_at)
                    <small class="text-muted">
                        Approved: {{ $household->approved_at->format('M d, Y') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Household Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Household Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center g-3">
                    <div class="col-md-2">
                        <div class="p-3 bg-primary bg-opacity-10 rounded">
                            <h3 class="mb-0 text-primary">{{ $statistics['total_residents'] }}</h3>
                            <small class="text-muted">Total Residents</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="mb-0 text-info">{{ $statistics['seniors'] }}</h3>
                            <small class="text-muted">Senior Citizens</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <h3 class="mb-0 text-warning">{{ $statistics['teens'] }}</h3>
                            <small class="text-muted">Teens (13-19)</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                            <h3 class="mb-0 text-danger">{{ $statistics['pwd'] }}</h3>
                            <small class="text-muted">PWD</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="mb-0 text-success">{{ $statistics['voters'] }}</h3>
                            <small class="text-muted">Voters</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-secondary bg-opacity-10 rounded">
                            <h3 class="mb-0 text-secondary">{{ $statistics['four_ps'] }}</h3>
                            <small class="text-muted">4Ps Beneficiaries</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRIMARY FAMILY -->
@if($primaryFamily && $primaryMembers->count() > 0)
<div class="card mb-4 border-warning">
    <div class="card-header bg-warning bg-opacity-10">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-0">
                    <i class="bi bi-house-heart-fill text-warning"></i> 
                    PRIMARY FAMILY
                    @if($household->officialHead)
                        <small class="text-muted">({{ $household->officialHead->full_name }})</small>
                    @endif
                </h4>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-dark">{{ $primaryStats['total'] }} Members</span>
                @if($primaryStats['seniors'] > 0)
                    <span class="badge bg-info">{{ $primaryStats['seniors'] }} Seniors</span>
                @endif
                @if($primaryStats['pwd'] > 0)
                    <span class="badge bg-danger">{{ $primaryStats['pwd'] }} PWD</span>
                @endif
                @if($primaryStats['four_ps'] > 0)
                    <span class="badge bg-primary">{{ $primaryStats['four_ps'] }} 4Ps</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Age/Sex</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Categories</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($primaryMembers as $member)
                    <tr class="{{ $member->status !== 'active' ? 'table-secondary' : '' }}">
                        <td>
                            <strong>{{ $member->full_name }}</strong>
                            @if($member->is_primary_head)
                                <span class="badge bg-warning text-dark ms-2">
                                    <i class="bi bi-star-fill"></i> PRIMARY HEAD
                                </span>
                            @endif
                        </td>
                        <td>{{ $member->age }} / {{ ucfirst($member->sex) }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($member->household_role) }}</span>
                        </td>
                        <td>
                            @if($member->approval_status === 'pending')
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock"></i> Pending Approval
                                </span>
                            @elseif($member->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($member->status === 'reallocated')
                                <span class="badge bg-warning">Reallocated</span>
                            @else
                                <span class="badge bg-dark">Deceased</span>
                            @endif
                        </td>
                        <td>
                            @if($member->is_senior_citizen)
                                <span class="badge bg-info">Senior</span>
                            @endif
                            @if($member->is_teen)
                                <span class="badge bg-warning">Teen</span>
                            @endif
                            @if($member->is_pwd)
                                <span class="badge bg-danger">PWD</span>
                            @endif
                            @if($member->is_voter)
                                <span class="badge bg-success">Voter</span>
                            @endif
                            @if($member->is_4ps_beneficiary)
                                <span class="badge bg-primary">4Ps</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('residents.show', $member) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye-fill"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- EXTENDED FAMILIES (CO-HEADS) -->
@if($extendedFamilies->count() > 0)
    @foreach($extendedFamilies as $index => $extendedFamily)
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary bg-opacity-10">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-0">
                        <i class="bi bi-people-fill text-primary"></i> 
                        EXTENDED FAMILY
                        @if($extendedFamily->subHead)
                            <small class="text-muted">({{ $extendedFamily->sub_family_name }})</small>
                            <span class="badge bg-primary ms-2">
                                <i class="bi bi-person-badge-fill"></i> CO-HEAD: {{ $extendedFamily->subHead->full_name }}
                            </span>
                        @endif
                    </h4>
                </div>
                <div class="col-md-4 text-end">
                    @php
                        $familyMembers = $extendedFamily->members;
                        $familyStats = [
                            'total' => $familyMembers->count(),
                            'seniors' => $familyMembers->where('is_senior_citizen', true)->count(),
                            'pwd' => $familyMembers->where('is_pwd', true)->count(),
                            'four_ps' => $familyMembers->where('is_4ps_beneficiary', true)->count(),
                        ];
                    @endphp
                    <span class="badge bg-dark">{{ $familyStats['total'] }} Members</span>
                    @if($familyStats['seniors'] > 0)
                        <span class="badge bg-info">{{ $familyStats['seniors'] }} Seniors</span>
                    @endif
                    @if($familyStats['pwd'] > 0)
                        <span class="badge bg-danger">{{ $familyStats['pwd'] }} PWD</span>
                    @endif
                    @if($familyStats['four_ps'] > 0)
                        <span class="badge bg-primary">{{ $familyStats['four_ps'] }} 4Ps</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($familyMembers as $member)
                        <tr class="{{ $member->status !== 'active' ? 'table-secondary' : '' }}">
                            <td>
                                <strong>{{ $member->full_name }}</strong>
                                @if($member->is_co_head)
                                    <span class="badge bg-primary ms-2">
                                        <i class="bi bi-person-badge-fill"></i> CO-HEAD
                                    </span>
                                @endif
                            </td>
                            <td>{{ $member->age }} / {{ ucfirst($member->sex) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($member->household_role) }}</span>
                            </td>
                            <td>
                                @if($member->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($member->status === 'reallocated')
                                    <span class="badge bg-warning">Reallocated</span>
                                @else
                                    <span class="badge bg-dark">Deceased</span>
                                @endif
                            </td>
                            <td>
                                @if($member->is_senior_citizen)
                                    <span class="badge bg-info">Senior</span>
                                @endif
                                @if($member->is_teen)
                                    <span class="badge bg-warning">Teen</span>
                                @endif
                                @if($member->is_pwd)
                                    <span class="badge bg-danger">PWD</span>
                                @endif
                                @if($member->is_voter)
                                    <span class="badge bg-success">Voter</span>
                                @endif
                                @if($member->is_4ps_beneficiary)
                                    <span class="badge bg-primary">4Ps</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('residents.show', $member) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye-fill"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
@endif

<!-- Archive Modal -->
@if(auth()->user()->isSecretary())
<div class="modal fade" id="archiveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-archive-fill"></i> Archive Household</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive this household?</p>
                <p class="text-muted">This will archive all members and can be restored later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('households.archive', $household) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-archive-fill"></i> Archive
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
