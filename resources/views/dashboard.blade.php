@extends('layouts.app')

@section('title', 'Dashboard - Barangay Matina Pangi')
{{-- Updated: 2025-10-12 11:39 - FORCE REFRESH --}}

@push('styles')
<style>
    .page-header { border-bottom: 1px solid #E5E7EB; max-width: 1200px; margin: 0 auto; }
    .page-header h2 { color: #111827 !important; }
    .card-header { background: #FFFFFF !important; border-bottom: 1px solid #E5E7EB !important; color: #1F2937 !important; }
    .card-header h5,.card-header h6 { color: #1F2937 !important; }
    .card-header i { color: #1E3A8A !important; }
    .dashboard-content { margin: 0 !important; padding: 0 1.5rem !important; }
    .dashboard-content.section-offset { padding-left: 0 !important; padding-right: 0 !important; margin: 0 auto !important; max-width: 1200px !important; position: relative !important; left: 0 !important; }
    @media (max-width: 768px){ .dashboard-content.section-offset { left: 0 !important; } }
    .stats-grid { display: grid !important; gap: 1.5rem !important; margin: 0 auto 2rem auto !important; max-width: 1200px !important; grid-template-columns: repeat(4, minmax(0, 1fr)) !important; grid-auto-rows: 200px !important; align-items: stretch !important; justify-content: center !important; }
    .stats-grid > div { display: grid !important; }
    .stat-card { height: 100% !important; }
    .stat-card .card-body { display: flex !important; align-items: flex-start !important; justify-content: flex-start !important; height: 100% !important; padding: 1.5rem !important; }
    .stat-card h3 { font-size: 2.6rem !important; line-height: 1 !important; margin-top: -2px !important; }
    .stat-icon { width: 52px !important; height: 52px !important; }
    .stat-card .card-body .d-flex { align-items: stretch !important; gap: 0.75rem !important; height: 100% !important; }
@media (max-width: 768px){ .stats-grid{ grid-template-columns: repeat(2,1fr) !important; } }
@media (max-width: 480px){ .stats-grid{ grid-template-columns: 1fr !important; } }
    .analytics-center { max-width: 1200px !important; margin: 0 auto !important; grid-template-columns: repeat(2, minmax(320px, 1fr)) !important; justify-content: center !important; align-items: start !important; grid-auto-rows: auto !important; }
    .content-container.form-offset-right { padding-left: 24px !important; padding-right: 24px !important; }
    .stat-icon { width: 40px; height: 40px; background: #F3F4F6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #1E3A8A; font-size: 1.25rem; }
    .action-button-wrapper .btn { border-radius: 8px !important; }
    .chart-compact { display: flex !important; justify-content: center !important; }
    .chart-compact canvas { width: 360px !important; max-width: 360px !important; }
    .stat-card .card-body .d-flex { justify-content: space-between !important; }
    .stat-card .card-body .d-flex > div:last-child { display: flex !important; flex-direction: column !important; justify-content: space-between !important; align-items: flex-end !important; height: 100% !important; flex: 1 1 auto !important; }
    .stat-card .card-body .d-flex > div:last-child h3 { text-align: right !important; margin-top: 0 !important; }
    .stat-card .card-body .d-flex > div:last-child p { margin-top: 0 !important; }
</style>
@endpush

@section('content')
<div class="dashboard-content section-offset">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="flex-grow-1">
                <h2 class="mb-1">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </h2>
                <p class="text-muted mb-0">Welcome back! Here's what's happening in Barangay Matina Pangi.</p>
            </div>
            <div class="ms-auto text-end">
                <div class="badge bg-light text-dark px-3 py-2" style="font-size: 0.9rem;">
                    <i class="bi bi-calendar3"></i> {{ now()->format('F d, Y') }}
                </div>
            </div>
        </div>
    </div>

<!-- Main Statistics Grid - Perfect 4-Card Rows -->
<div class="stats-grid">
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-person-standing"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_teens']) }}</h3>
                        <p class="text-muted mb-0">Teens (13–19)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_voters']) }}</h3>
                        <p class="text-muted mb-0">Registered Voters</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_4ps']) }}</h3>
                        <p class="text-muted mb-0">4Ps Beneficiaries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['average_household_size'], 1) }}</h3>
                        <p class="text-muted mb-0">Avg Household Size</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

<!-- Row 2: Secondary Statistics (4 cards) -->
<div class="stats-grid">
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_residents']) }}</h3>
                        <p class="text-muted mb-0">Total Residents</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-house"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_households']) }}</h3>
                        <p class="text-muted mb-0">Households</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-person"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_senior_citizens']) }}</h3>
                        <p class="text-muted mb-0">Senior Citizens</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-universal-access"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_pwd']) }}</h3>
                        <p class="text-muted mb-0">PWD Residents</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

<!-- Analytics & Distribution Section -->
<div class="stats-grid analytics-center">
    <!-- Gender Distribution -->
    <div>
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-gender-ambiguous text-primary me-2"></i>Gender Distribution
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="chart-compact">
                    <canvas id="genderChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Age Distribution -->
    <div>
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-graph-up text-primary me-2"></i>Age Distribution
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="chart-compact">
                    <canvas id="ageChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="row g-4 mt-4 mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body p-5">
                <div class="row g-4">
                    @if(auth()->user()->isSecretary())
                        <div class="col-lg-3 col-md-6">
                            <div class="action-button-wrapper">
                                <a href="{{ route('households.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-height: 56px;">
                                    <i class="bi bi-house-add"></i>
                                    <span class="fw-semibold">Register Household</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="action-button-wrapper">
                                <a href="{{ route('households.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-height: 56px;">
                                    <i class="bi bi-person-plus"></i>
                                    <span class="fw-semibold">Add Resident</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="action-button-wrapper">
                                <a href="{{ route('certificates.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-height: 56px;">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span class="fw-semibold">Issue Certificate</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="action-button-wrapper">
                                <a href="{{ route('census.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-height: 56px;">
                                    <i class="bi bi-bar-chart"></i>
                                    <span class="fw-semibold">View Census</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="action-button-wrapper">
                                <a href="{{ route('settings.users.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-height: 56px;">
                                    <i class="bi bi-gear"></i>
                                    <span class="fw-semibold">Manage Users</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-6 col-md-6">
                            <div class="action-button-wrapper">
                                <a href="{{ route('census.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-height: 56px;">
                                    <i class="bi bi-bar-chart"></i>
                                    <span class="fw-semibold">View Census Report</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="action-button-wrapper">
                                <a href="{{ route('residents.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-height: 56px;">
                                    <i class="bi bi-people"></i>
                                    <span class="fw-semibold">View Residents</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Residents Section -->
@if($recentResidents->count() > 0)
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history text-info me-2"></i>Recently Registered Residents
                </h5>
                <a href="{{ route('residents.index') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="bi bi-arrow-right ms-1"></i>
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

</div> <!-- End dashboard-content wrapper -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
  const deepBlue = '#1E3A8A';
  const softGray = '#F3F4F6';
  const grid = '#E5E7EB';
  const genderCtx = document.getElementById('genderChart');
  if (genderCtx) {
    new Chart(genderCtx, {
      type: 'doughnut',
      data: {
        labels: ['Male','Female'],
        datasets: [{
          data: [
            {{ (int)($stats['male_count'] ?? 0) }},
            {{ (int)($stats['female_count'] ?? 0) }}
          ],
          backgroundColor: [deepBlue, softGray],
          borderWidth: 0
        }]
      },
      options: {
        plugins: { legend: { position: 'bottom' } },
        cutout: '60%'
      }
    });
  }
  const ageCtx = document.getElementById('ageChart');
  if (ageCtx) {
    new Chart(ageCtx, {
      type: 'bar',
      data: {
        labels: ['Children','Teens','Adults','Seniors'],
        datasets: [{
          label: 'Count',
          data: [
            {{ (int)($ageDistribution['children'] ?? 0) }},
            {{ (int)($ageDistribution['teens'] ?? 0) }},
            {{ (int)($ageDistribution['adults'] ?? 0) }},
            {{ (int)($ageDistribution['seniors'] ?? 0) }}
          ],
          backgroundColor: deepBlue
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: { x: { grid: { color: grid } }, y: { beginAtZero: true, grid: { color: grid } } }
      }
    });
  }
})();
</script>
@endpush
