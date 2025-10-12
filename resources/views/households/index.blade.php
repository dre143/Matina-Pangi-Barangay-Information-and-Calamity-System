@extends('layouts.app')

@section('title', 'Households')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-house"></i> Households</h2>
    @if(auth()->user()->isSecretary())
        <a href="{{ route('households.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Register Household
        </a>
    @endif
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-funnel-fill"></i> Search & Filter Households</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('households.index') }}" method="GET">
            <div class="row g-3">
                <!-- Household ID / Address Search -->
                <div class="col-md-4">
                    <label class="form-label small">Household ID / Address</label>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search by ID or Address..." 
                           value="{{ request('search') }}">
                </div>

                <!-- Primary Head Name -->
                <div class="col-md-4">
                    <label class="form-label small">Primary Head Name</label>
                    <input type="text" class="form-control" name="head_name" 
                           placeholder="Search by head name..." 
                           value="{{ request('head_name') }}">
                </div>

                <!-- Purok Filter -->
                <div class="col-md-4">
                    <label class="form-label small">Purok</label>
                    <select class="form-select" name="purok_id">
                        <option value="">All Puroks</option>
                        @foreach($puroks as $purok)
                            <option value="{{ $purok->id }}" {{ request('purok_id') == $purok->id ? 'selected' : '' }}>
                                {{ $purok->purok_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Beneficiary Type -->
                <div class="col-md-3">
                    <label class="form-label small">Beneficiary Type</label>
                    <select class="form-select" name="beneficiary_type">
                        <option value="">All</option>
                        <option value="pwd" {{ request('beneficiary_type') == 'pwd' ? 'selected' : '' }}>PWD</option>
                        <option value="4ps" {{ request('beneficiary_type') == '4ps' ? 'selected' : '' }}>4Ps</option>
                        <option value="senior" {{ request('beneficiary_type') == 'senior' ? 'selected' : '' }}>Senior Citizen</option>
                        <option value="teen" {{ request('beneficiary_type') == 'teen' ? 'selected' : '' }}>Teen</option>
                    </select>
                </div>

                <!-- Household Type -->
                <div class="col-md-3">
                    <label class="form-label small">Household Type</label>
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        <option value="solo" {{ request('type') == 'solo' ? 'selected' : '' }}>Solo</option>
                        <option value="family" {{ request('type') == 'family' ? 'selected' : '' }}>Family</option>
                        <option value="extended" {{ request('type') == 'extended' ? 'selected' : '' }}>Extended</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-6">
                    <label class="form-label small">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <a href="{{ route('households.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                        @if(auth()->user()->isSecretary())
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-download"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('households.export.pdf') }}">
                                        <i class="bi bi-file-pdf"></i> Export to PDF
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('households.export.excel') }}">
                                        <i class="bi bi-file-excel"></i> Export to Excel
                                    </a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                        <div>
                                            <a href="{{ route('residents.show', $household->officialHead) }}" class="text-decoration-none">
                                                <strong>{{ $household->officialHead->full_name }}</strong>
                                            </a>
                                            <br><small class="text-muted">{{ $household->officialHead->age }} yrs, {{ ucfirst($household->officialHead->sex) }}</small>
                                        </div>
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
                                        <br><i class="bi bi-lightning-fill text-warning"></i> <span class="text-muted">Electricity</span>
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
                                       class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->isSecretary())
                                        <a href="{{ route('households.edit', $household) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('households.archive', $household) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to archive this household? All residents will also be archived.')">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" title="Archive">
                                                <i class="bi bi-archive"></i>
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
@endsection
