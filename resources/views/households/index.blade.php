@extends('layouts.app')

@section('title', 'Households')

@section('content')
<div class="section-offset">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-house"></i> Households</h2>
    @if(auth()->user()->isSecretary())
        <a href="{{ route('households.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Register Household
        </a>
    @endif
</div>

@php
$searchFields = [
    [
        'name' => 'search',
        'type' => 'text',
        'label' => 'Household ID / Address',
        'placeholder' => 'Search by ID or Address...',
        'col' => 4
    ],
    [
        'name' => 'head_name',
        'type' => 'text',
        'label' => 'Primary Head Name',
        'placeholder' => 'Search by head name...',
        'col' => 4
    ],
    [
        'name' => 'purok_id',
        'type' => 'select',
        'label' => 'Purok',
        'placeholder' => 'All Puroks',
        'options' => $puroks->pluck('purok_name', 'id')->toArray(),
        'col' => 4
    ],
    [
        'name' => 'beneficiary_type',
        'type' => 'select',
        'label' => 'Beneficiary Type',
        'placeholder' => 'All Types',
        'options' => [
            'pwd' => 'PWD',
            '4ps' => '4Ps Beneficiary',
            'senior' => 'Senior Citizen',
            'teen' => 'Teen'
        ],
        'col' => 3
    ],
    [
        'name' => 'type',
        'type' => 'select',
        'label' => 'Household Type',
        'placeholder' => 'All Types',
        'options' => [
            'nuclear' => 'Nuclear Family',
            'extended' => 'Extended Family',
            'single' => 'Single Person',
            'other' => 'Other'
        ],
        'col' => 3
    ],
    [
        'name' => 'status',
        'type' => 'select',
        'label' => 'Status',
        'placeholder' => 'All Status',
        'options' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'pending' => 'Pending Approval'
        ],
        'col' => 3
    ],
    [
        'name' => 'member_count_min',
        'type' => 'number',
        'label' => 'Min Members',
        'placeholder' => 'Min',
        'min' => 1,
        'col' => 2
    ],
    [
        'name' => 'member_count_max',
        'type' => 'number',
        'label' => 'Max Members',
        'placeholder' => 'Max',
        'min' => 1,
        'col' => 1
    ]
];
@endphp

<x-search-filter 
    :route="route('households.index')" 
    title="Search & Filter Households"
    icon="bi-house-fill"
    :fields="$searchFields"
    :advanced="true">
    
    <x-slot name="advancedSlot">
        <div class="col-md-4">
            <label class="form-label small">Date Registered From</label>
            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label small">Date Registered To</label>
            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label small">Has Pregnant Member</label>
            <select class="form-select" name="has_pregnant">
                <option value="">All</option>
                <option value="yes" {{ request('has_pregnant') == 'yes' ? 'selected' : '' }}>Yes</option>
                <option value="no" {{ request('has_pregnant') == 'no' ? 'selected' : '' }}>No</option>
            </select>
        </div>
    </x-slot>
</x-search-filter>

<!-- Households Table -->
<div class="card">
    <div class="card-body">
        @if($households->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Household ID</th>
                            <th>Primary Head</th>
                            <th>Address / Purok</th>
                            <th>Members / Families</th>
                            <th>Housing</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($households as $household)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $household->household_id }}</strong>
                            </td>
                            <td>
                                @if($household->officialHead)
                                    <div class="d-flex align-items-center gap-2 text-nowrap">
                                        <a href="{{ route('residents.show', $household->officialHead) }}" class="text-decoration-none">
                                            <strong>{{ $household->officialHead->full_name }}</strong>
                                        </a>
                                        <small class="text-muted">{{ $household->officialHead->age }} yrs, {{ ucfirst($household->officialHead->sex) }}</small>
                                    </div>
                                @elseif($household->head)
                                    <a href="{{ route('residents.show', $household->head) }}">
                                        {{ $household->head->full_name }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $household->address }}</div>
                                @if($household->purok)
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt-fill"></i> {{ is_object($household->purok) ? $household->purok->purok_name : $household->purok }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <i class="bi bi-people-fill"></i> {{ $household->total_members }} Members
                                </span>
                                @php
                                    $familyCount = $household->subFamilies ? $household->subFamilies->count() : 0;
                                @endphp
                                @if($familyCount > 1)
                                    <br><span class="badge bg-info mt-1">
                                        <i class="bi bi-diagram-3-fill"></i> {{ $familyCount }} Families
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    <span class="badge bg-{{ $household->housing_type === 'owned' ? 'success' : ($household->housing_type === 'rented' ? 'warning' : 'info') }}">
                                        {{ ucfirst($household->housing_type) }}
                                    </span>
                                    @if($household->has_electricity)
                                        <br><span class="text-muted">Electricity</span>
                                    @endif
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $household->approval_badge_color }}">
                                    {{ ucfirst($household->approval_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('households.show', $household) }}" 
                                       class="btn btn-primary" title="View">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    @if(auth()->user()->isSecretary())
                                        <a href="{{ route('households.edit', $household) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('households.archive', $household) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to archive this household? All residents will also be archived.')">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" title="Archive">
                                                <i class="bi bi-archive"></i> Archive
                                            </button>
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
                {{ $households->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-house" style="font-size: 64px; color: #ccc;"></i>
                <p class="text-muted mt-3">No households found.</p>
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

@push('styles')
<style>
.table .btn-group { display: inline-flex !important; flex-wrap: nowrap !important; }
.table .btn-group .btn { padding: 0.375rem 0.5rem !important; border-radius: 6px !important; white-space: nowrap !important; }
.table .btn-group form { display: inline-block !important; }
</style>
@endpush
