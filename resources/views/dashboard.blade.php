@extends('layouts.app')

@section('title', 'Dashboard - Barangay Matina Pangi')
{{-- Updated: 2025-10-12 11:39 - FORCE REFRESH --}}

@push('styles')
<style>
    .card-header {
        background: linear-gradient(135deg, rgba(58, 183, 149, 0.15), rgba(122, 229, 130, 0.1)) !important;
        color: #1a5a45 !important;
        border-bottom: 3px solid #3AB795 !important;
    }
    
    .card-header h5,
    .card-header h6 {
        color: #1a5a45 !important;
    }
    
    .card-header i {
        color: #3AB795 !important;
    }
    
    .card-body {
        background: white !important;
        color: #495057 !important;
    }
    
    .card-body span,
    .card-body li,
    .card-body p {
        color: #495057 !important;
    }
    
    .card-body strong {
        color: #2c3e50 !important;
    }
    
    /* Force icon visibility on stat cards */
    .stat-card .card-body > div {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    
    .stat-card .card-body i {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .stat-icon i {
        color: white !important;
        font-size: 2rem !important;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)) !important;
        -webkit-text-stroke: 0.5px rgba(255,255,255,0.5);
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header mb-4" style="margin-top: -2.5rem; padding-top: 1.5rem;">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-speedometer2"></i> Dashboard
        </h2>
        <p class="text-muted mb-0">Welcome back! Here's what's happening in Barangay Matina Pangi.</p>
    </div>
    <div class="text-end">
        <div class="badge bg-light text-dark px-3 py-2" style="font-size: 0.9rem;">
            <i class="bi bi-calendar3"></i> {{ now()->format('F d, Y') }}
        </div>
    </div>
</div>

<!-- Statistics Cards - Row 1 -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">Total Residents</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['total_residents']) }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people-fill text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-success">
                    <i class="bi bi-arrow-up-circle-fill me-1"></i>
                    <small class="fw-semibold">Active Population</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0" style="border-left-color: #14b8a6 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">Total Households</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['total_households']) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
                        <i class="bi bi-house-fill text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-success">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    <small class="fw-semibold">Registered Families</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0" style="border-left-color: #f59e0b !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">Senior Citizens</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['total_senior_citizens']) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="bi bi-person-hearts" style="color: white !important; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-warning">
                    <i class="bi bi-heart-fill me-1"></i>
                    <small class="fw-semibold">60+ Years Old</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0" style="border-left-color: #3b82f6 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">PWD</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['total_pwd']) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="bi bi-universal-access text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-info">
                    <i class="bi bi-shield-fill-check me-1"></i>
                    <small class="fw-semibold">With Disabilities</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards - Row 2 -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0" style="border-left-color: #8b5cf6 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">Teens (13-19)</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['total_teens']) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="bi bi-person-standing text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="color: #8b5cf6;">
                    <i class="bi bi-mortarboard-fill me-1"></i>
                    <small class="fw-semibold">Youth Population</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0" style="border-left-color: #10b981 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">Registered Voters</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['total_voters']) }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-check2-circle text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-success">
                    <i class="bi bi-patch-check-fill me-1"></i>
                    <small class="fw-semibold">Eligible to Vote</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0" style="border-left-color: #f97316 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">4Ps Beneficiaries</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['total_4ps']) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                        <i class="bi bi-cash-coin text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="color: #f97316;">
                    <i class="bi bi-wallet2 me-1"></i>
                    <small class="fw-semibold">Gov't Assistance</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 border-0" style="border-left-color: #06b6d4 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="stat-label mb-2">Avg Household Size</p>
                        <h2 class="stat-value mb-0">{{ number_format($stats['average_household_size'], 1) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                        <i class="bi bi-people text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="color: #06b6d4;">
                    <i class="bi bi-graph-up me-1"></i>
                    <small class="fw-semibold">Members per Family</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Cards Row -->
<div class="row g-4 mb-4">
    <!-- Gender Distribution -->
    <div class="col-lg-4">
        <div class="card h-100 border-0">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-gender-ambiguous"></i> Gender Distribution</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-gender-male text-primary me-2" style="font-size: 1.25rem;"></i>
                            <span class="fw-semibold">Male</span>
                        </div>
                        <strong class="text-primary">{{ number_format($stats['male_count']) }}</strong>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 10px;">
                        <div class="progress-bar" style="width: {{ $stats['total_residents'] > 0 ? ($stats['male_count'] / $stats['total_residents'] * 100) : 0 }}%; background: linear-gradient(90deg, #3b82f6, #2563eb);"></div>
                    </div>
                    <small class="text-muted">{{ $stats['total_residents'] > 0 ? number_format(($stats['male_count'] / $stats['total_residents'] * 100), 1) : 0 }}%</small>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-gender-female text-danger me-2" style="font-size: 1.25rem;"></i>
                            <span class="fw-semibold">Female</span>
                        </div>
                        <strong class="text-danger">{{ number_format($stats['female_count']) }}</strong>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 10px;">
                        <div class="progress-bar" style="width: {{ $stats['total_residents'] > 0 ? ($stats['female_count'] / $stats['total_residents'] * 100) : 0 }}%; background: linear-gradient(90deg, #ef4444, #dc2626);"></div>
                    </div>
                    <small class="text-muted">{{ $stats['total_residents'] > 0 ? number_format(($stats['female_count'] / $stats['total_residents'] * 100), 1) : 0 }}%</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Age Distribution -->
    <div class="col-lg-4">
        <div class="card h-100 border-0">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Age Distribution</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(16, 185, 129, 0.05);">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="bi bi-emoji-smile text-white"></i>
                            </div>
                            <span class="fw-semibold">Children (0-12)</span>
                        </div>
                        <strong>{{ number_format($ageDistribution['children']) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(139, 92, 246, 0.05);">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="bi bi-mortarboard text-white"></i>
                            </div>
                            <span class="fw-semibold">Teens (13-19)</span>
                        </div>
                        <strong>{{ number_format($ageDistribution['teens']) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(59, 130, 246, 0.05);">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                <i class="bi bi-briefcase text-white"></i>
                            </div>
                            <span class="fw-semibold">Adults (20-59)</span>
                        </div>
                        <strong>{{ number_format($ageDistribution['adults']) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(245, 158, 11, 0.05);">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="bi bi-heart text-white"></i>
                            </div>
                            <span class="fw-semibold">Seniors (60+)</span>
                        </div>
                        <strong>{{ number_format($ageDistribution['seniors']) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card h-100 border-0">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @if(auth()->user()->isSecretary())
                        <a href="{{ route('households.create') }}" class="btn btn-gradient d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>Register Household</span>
                        </a>
                        <a href="{{ route('households.index') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Add Resident</span>
                        </a>
                        <a href="{{ route('certificates.create') }}" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Issue Certificate</span>
                        </a>
                    @endif
                    <a href="{{ route('census.index') }}" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>View Census Report</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Residents -->
@if($recentResidents->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-gradient d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recently Registered Residents</h5>
                <a href="{{ route('residents.index') }}" class="btn btn-sm btn-light">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Resident ID</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Household</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentResidents as $resident)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $resident->resident_id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #10b981, #059669); color: white; font-weight: 600; font-size: 0.75rem;">
                                            {{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}
                                        </div>
                                        <a href="{{ route('residents.show', $resident) }}" class="text-decoration-none fw-semibold">
                                            {{ $resident->full_name }}
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $resident->age }} yrs</td>
                                <td>
                                    @if($resident->sex == 'male')
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            <i class="bi bi-gender-male"></i> Male
                                        </span>
                                    @else
                                        <span class="badge" style="background: #fee2e2; color: #991b1b;">
                                            <i class="bi bi-gender-female"></i> Female
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('households.show', $resident->household) }}" class="text-decoration-none">
                                        <i class="bi bi-house-fill text-success"></i> {{ $resident->household->household_id }}
                                    </a>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $resident->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('residents.show', $resident) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
