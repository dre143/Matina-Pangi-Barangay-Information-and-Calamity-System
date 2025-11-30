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
<div class="section-offset">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Residents</h2>
    <div class="alert alert-info mb-0 py-2 px-3">
        <i class="bi bi-info-circle"></i> To add residents, go to <strong>Households</strong> → Select household → <strong>Add Member</strong>
    </div>
</div>

@php
$residentSearchFields = [
    [
        'name' => 'search',
        'type' => 'text',
        'label' => 'Name or ID',
        'placeholder' => 'Search by name or ID...',
        'col' => 4
    ],
    [
        'name' => 'category',
        'type' => 'select',
        'label' => 'Category',
        'placeholder' => 'All Categories',
        'options' => [
            'pwd' => 'PWD',
            'senior' => 'Senior Citizens',
            'teen' => 'Teens',
            'voter' => 'Voters',
            '4ps' => '4Ps Beneficiaries',
            'head' => 'Household Heads'
        ],
        'col' => 3
    ],
    [
        'name' => 'gender',
        'type' => 'select',
        'label' => 'Gender',
        'placeholder' => 'All Genders',
        'options' => [
            'Male' => 'Male',
            'Female' => 'Female'
        ],
        'col' => 2
    ],
    [
        'name' => 'civil_status',
        'type' => 'select',
        'label' => 'Civil Status',
        'placeholder' => 'All Status',
        'options' => [
            'Single' => 'Single',
            'Married' => 'Married',
            'Widowed' => 'Widowed',
            'Separated' => 'Separated'
        ],
        'col' => 3
    ]
];
@endphp

<x-search-filter 
    :route="route('residents.index')" 
    title="Search & Filter Residents"
    icon="bi-people-fill"
    :fields="$residentSearchFields"
    :advanced="true">
    
    <x-slot name="advancedSlot">
        <div class="col-md-3">
            <label class="form-label small">Age From</label>
            <input type="number" class="form-control" name="age_from" value="{{ request('age_from') }}" min="0" max="120">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Age To</label>
            <input type="number" class="form-control" name="age_to" value="{{ request('age_to') }}" min="0" max="120">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Purok</label>
            <select class="form-select" name="purok">
                <option value="">All Puroks</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="Purok {{ $i }}" {{ request('purok') == "Purok $i" ? 'selected' : '' }}>Purok {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Employment Status</label>
            <select class="form-select" name="employment">
                <option value="">All</option>
                <option value="employed" {{ request('employment') == 'employed' ? 'selected' : '' }}>Employed</option>
                <option value="unemployed" {{ request('employment') == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                <option value="student" {{ request('employment') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="retired" {{ request('employment') == 'retired' ? 'selected' : '' }}>Retired</option>
            </select>
        </div>
    </x-slot>
</x-search-filter>

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
                                       class="btn btn-primary" title="View">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    @if(auth()->user()->isSecretary())
                                        <a href="{{ route('residents.edit', $resident) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-secondary" title="Archive"
                                                onclick="if(confirm('Are you sure you want to archive this resident?')) { document.getElementById('archive-form-{{ $resident->id }}').submit(); }">
                                            <i class="bi bi-archive"></i> Archive
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
</div>
@endsection
