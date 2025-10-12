@extends('layouts.app')

@section('title', 'Census')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-bar-chart"></i> Census Snapshot</h2>
    @if(auth()->user()->isSecretary())
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-download"></i> Export Census
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('census.export.pdf') }}">
                    <i class="bi bi-file-pdf"></i> Export to PDF
                </a></li>
                <li><a class="dropdown-item" href="{{ route('census.export.excel') }}">
                    <i class="bi bi-file-excel"></i> Export to Excel
                </a></li>
            </ul>
        </div>
    @endif
</div>

<!-- Population Overview -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Population</h6>
                        <h3 class="mb-0 text-primary">{{ number_format($census['total_population']) }}</h3>
                        <small class="text-muted">All Approved</small>
                    </div>
                    <div class="text-primary" style="font-size: 48px;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Active Residents</h6>
                        <h3 class="mb-0 text-success">{{ number_format($census['active_residents']) }}</h3>
                        <small class="text-muted">Currently Living</small>
                    </div>
                    <div class="text-success" style="font-size: 48px;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Reallocated</h6>
                        <h3 class="mb-0 text-warning">{{ number_format($census['reallocated_residents']) }}</h3>
                        <small class="text-muted">Moved Away</small>
                    </div>
                    <div class="text-warning" style="font-size: 48px;">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Deceased</h6>
                        <h3 class="mb-0 text-dark">{{ number_format($census['deceased_residents']) }}</h3>
                        <small class="text-muted">Historical</small>
                    </div>
                    <div class="text-dark" style="font-size: 48px;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Household Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Households</h6>
                        <h2 class="mb-0 text-info">{{ number_format($census['total_households']) }}</h2>
                    </div>
                    <div class="text-info" style="font-size: 64px;">
                        <i class="bi bi-house-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card border-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Average Household Size</h6>
                        <h2 class="mb-0 text-secondary">{{ number_format($census['average_household_size'], 1) }}</h2>
                    </div>
                    <div class="text-secondary" style="font-size: 64px;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gender Distribution -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gender-ambiguous"></i> Gender Distribution</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="text-primary">{{ number_format($census['male_count']) }}</h3>
                        <p class="text-muted mb-0">Male</p>
                        <small class="text-muted">
                            {{ $census['total_population'] > 0 ? number_format(($census['male_count'] / $census['total_population']) * 100, 1) : 0 }}%
                        </small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-danger">{{ number_format($census['female_count']) }}</h3>
                        <p class="text-muted mb-0">Female</p>
                        <small class="text-muted">
                            {{ $census['total_population'] > 0 ? number_format(($census['female_count'] / $census['total_population']) * 100, 1) : 0 }}%
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people"></i> Age Groups</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Children (0-12)</span>
                        <strong>{{ number_format($census['children_count']) }}</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: {{ $census['total_population'] > 0 ? ($census['children_count'] / $census['total_population'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Teens (13-19)</span>
                        <strong>{{ number_format($census['teens_count']) }}</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-secondary" style="width: {{ $census['total_population'] > 0 ? ($census['teens_count'] / $census['total_population'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Adults (20-59)</span>
                        <strong>{{ number_format($census['adults_count']) }}</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" style="width: {{ $census['total_population'] > 0 ? ($census['adults_count'] / $census['total_population'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Seniors (60+)</span>
                        <strong>{{ number_format($census['seniors_count']) }}</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $census['total_population'] > 0 ? ($census['seniors_count'] / $census['total_population'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Special Categories -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-universal-access text-info" style="font-size: 48px;"></i>
                <h3 class="mt-2 mb-0">{{ number_format($census['pwd_count']) }}</h3>
                <p class="text-muted mb-0">PWD</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-walking text-warning" style="font-size: 48px;"></i>
                <h3 class="mt-2 mb-0">{{ number_format($census['senior_citizens_count']) }}</h3>
                <p class="text-muted mb-0">Senior Citizens</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-check-circle text-success" style="font-size: 48px;"></i>
                <h3 class="mt-2 mb-0">{{ number_format($census['voters_count']) }}</h3>
                <p class="text-muted mb-0">Voters</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-cash-coin text-primary" style="font-size: 48px;"></i>
                <h3 class="mt-2 mb-0">{{ number_format($census['fourps_count']) }}</h3>
                <p class="text-muted mb-0">4Ps Beneficiaries</p>
            </div>
        </div>
    </div>
</div>

<!-- Civil Status & Employment -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-heart"></i> Civil Status</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Single</td>
                        <td class="text-end"><strong>{{ number_format($census['single_count']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Married</td>
                        <td class="text-end"><strong>{{ number_format($census['married_count']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Widowed</td>
                        <td class="text-end"><strong>{{ number_format($census['widowed_count']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Separated</td>
                        <td class="text-end"><strong>{{ number_format($census['separated_count']) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Employment Status</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Employed</td>
                        <td class="text-end"><strong>{{ number_format($census['employed_count']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Unemployed</td>
                        <td class="text-end"><strong>{{ number_format($census['unemployed_count']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Self-Employed</td>
                        <td class="text-end"><strong>{{ number_format($census['self_employed_count']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Students</td>
                        <td class="text-end"><strong>{{ number_format($census['students_count']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Retired</td>
                        <td class="text-end"><strong>{{ number_format($census['retired_count']) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Housing & Income -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-house"></i> Housing Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Owned Houses</td>
                        <td class="text-end"><strong>{{ number_format($census['owned_houses']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Rented Houses</td>
                        <td class="text-end"><strong>{{ number_format($census['rented_houses']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Rent-Free Houses</td>
                        <td class="text-end"><strong>{{ number_format($census['rent_free_houses']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>With Electricity</td>
                        <td class="text-end"><strong>{{ number_format($census['with_electricity']) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Without Electricity</td>
                        <td class="text-end"><strong>{{ number_format($census['without_electricity']) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-cash"></i> Income Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Total Monthly Income</label>
                    <h3 class="mb-0 text-success">₱{{ number_format($census['total_income'], 2) }}</h3>
                </div>
                <div>
                    <label class="text-muted small">Average Monthly Income</label>
                    <h3 class="mb-0 text-primary">₱{{ number_format($census['average_income'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Household Types -->
<div class="row g-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Household Types</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h3>{{ number_format($census['solo_households']) }}</h3>
                        <p class="text-muted mb-0">Solo Households</p>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ number_format($census['family_households']) }}</h3>
                        <p class="text-muted mb-0">Family Households</p>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ number_format($census['extended_households']) }}</h3>
                        <p class="text-muted mb-0">Extended Households</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generated Info -->
<div class="card mt-3">
    <div class="card-body">
        <small class="text-muted">
            <i class="bi bi-clock"></i> Generated on: {{ now()->format('F d, Y h:i A') }}
        </small>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress {
        height: 20px;
    }
</style>
@endpush
