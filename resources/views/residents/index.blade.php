@extends('layouts.app')

@section('title', 'Residents')

@push('styles')
<style>
    .btn-group-sm > .btn {
        white-space: nowrap;
    }
    .btn-group {
        display: inline-flex !important;
        flex-wrap: nowrap !important;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Residents</h2>
    <div class="alert alert-info mb-0 py-2 px-3">
        <i class="bi bi-info-circle"></i> To add residents, go to <strong>Households</strong> → Select household → <strong>Add Member</strong>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('residents.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" 
                       placeholder="Search by name or ID..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    <option value="pwd" {{ request('category') == 'pwd' ? 'selected' : '' }}>PWD</option>
                    <option value="senior" {{ request('category') == 'senior' ? 'selected' : '' }}>Senior Citizens</option>
                    <option value="teen" {{ request('category') == 'teen' ? 'selected' : '' }}>Teens</option>
                    <option value="voter" {{ request('category') == 'voter' ? 'selected' : '' }}>Voters</option>
                    <option value="4ps" {{ request('category') == '4ps' ? 'selected' : '' }}>4Ps Beneficiaries</option>
                    <option value="head" {{ request('category') == 'head' ? 'selected' : '' }}>Household Heads</option>
                </select>
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="{{ route('residents.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
                @if(auth()->user()->isSecretary())
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('residents.export.pdf') }}">
                                <i class="bi bi-file-pdf"></i> Export to PDF
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('residents.export.excel') }}">
                                <i class="bi bi-file-excel"></i> Export to Excel
                            </a></li>
                        </ul>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Residents Table -->
<div class="card">
    <div class="card-body">
        @if($residents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Household</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($residents as $resident)
                        <tr>
                            <td><strong>{{ $resident->resident_id }}</strong></td>
                            <td>
                                <a href="{{ route('residents.show', $resident) }}">
                                    {{ $resident->full_name }}
                                </a>
                                @if($resident->is_household_head)
                                    <br><span class="badge bg-success">Household Head</span>
                                @endif
                            </td>
                            <td>
                                {{ $resident->age }} / {{ ucfirst($resident->sex) }}
                                <br><small class="text-muted">{{ $resident->age_category }}</small>
                            </td>
                            <td>
                                <a href="{{ route('households.show', $resident->household) }}">
                                    {{ $resident->household->household_id }}
                                </a>
                            </td>
                            <td>
                                <small>{{ Str::limit($resident->household->full_address, 30) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $resident->status_badge_color }}">
                                    {{ ucfirst($resident->status) }}
                                </span>
                                @if($resident->isPending())
                                    <br><span class="badge bg-{{ $resident->approval_badge_color }}">
                                        Pending Approval
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($resident->is_pwd)
                                    <span class="badge bg-info">PWD</span>
                                @endif
                                @if($resident->is_senior_citizen)
                                    <span class="badge bg-warning">Senior</span>
                                @endif
                                @if($resident->is_teen)
                                    <span class="badge bg-secondary">Teen</span>
                                @endif
                                @if($resident->is_voter)
                                    <span class="badge bg-success">Voter</span>
                                @endif
                                @if($resident->is_4ps_beneficiary)
                                    <span class="badge bg-primary">4Ps</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('residents.show', $resident) }}" 
                                       class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->isSecretary())
                                        <a href="{{ route('residents.edit', $resident) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-secondary" title="Archive"
                                                onclick="if(confirm('Are you sure you want to archive this resident?')) { document.getElementById('archive-form-{{ $resident->id }}').submit(); }">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                        <form id="archive-form-{{ $resident->id }}" 
                                              action="{{ route('residents.archive', $resident) }}" 
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $residents->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people" style="font-size: 64px; color: #ccc;"></i>
                <p class="text-muted mt-3">No residents found.</p>
                @if(auth()->user()->isSecretary())
                    <a href="{{ route('households.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Register First Household
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
