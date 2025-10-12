@extends('layouts.app')

@section('title', 'Certificates - Barangay Matina Pangi')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-file-earmark-text-fill"></i> Certificates Management
        </h2>
        <p class="text-muted mb-0">View, manage, and issue barangay certificates</p>
    </div>
    <a href="{{ route('certificates.create') }}" class="btn btn-gradient">
        <i class="bi bi-plus-circle-fill"></i> Issue New Certificate
    </a>
</div>

<!-- Filters Card -->
<div class="card border-0 filter-bar mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('certificates.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-search text-primary"></i> Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="🔍 Certificate #, OR #, or resident name..." value="{{ request('search') }}">
            </div>
            <div class="col-lg-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-filter text-success"></i> Certificate Type
                </label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="barangay_clearance" {{ request('type') == 'barangay_clearance' ? 'selected' : '' }}>🏘️ Barangay Clearance</option>
                    <option value="certificate_of_indigency" {{ request('type') == 'certificate_of_indigency' ? 'selected' : '' }}>💰 Certificate of Indigency</option>
                    <option value="certificate_of_residency" {{ request('type') == 'certificate_of_residency' ? 'selected' : '' }}>🏠 Certificate of Residency</option>
                    <option value="business_clearance" {{ request('type') == 'business_clearance' ? 'selected' : '' }}>💼 Business Clearance</option>
                    <option value="good_moral" {{ request('type') == 'good_moral' ? 'selected' : '' }}>⭐ Good Moral</option>
                    <option value="travel_permit" {{ request('type') == 'travel_permit' ? 'selected' : '' }}>✈️ Travel Permit</option>
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-flag text-info"></i> Status
                </label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>📋 Issued</option>
                    <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>✅ Claimed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                </select>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Certificates Table -->
<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Certificate #</th>
                        <th>Resident</th>
                        <th>Type</th>
                        <th>Purpose</th>
                        <th>Issued Date</th>
                        <th>OR Number</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $certificate)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark fw-bold" style="font-family: 'Courier New', monospace;">
                                    {{ $certificate->certificate_number }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #10b981, #059669); color: white; font-weight: 600; font-size: 0.75rem;">
                                        {{ strtoupper(substr($certificate->resident->first_name, 0, 1)) }}{{ strtoupper(substr($certificate->resident->last_name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $certificate->resident->full_name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                                    {{ $certificate->type_label }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" title="{{ $certificate->purpose }}">
                                    {{ Str::limit($certificate->purpose, 35) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> {{ $certificate->issued_date->format('M d, Y') }}
                                </small>
                            </td>
                            <td>
                                @if($certificate->or_number)
                                    <span class="badge bg-warning text-dark">{{ $certificate->or_number }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-success">₱{{ number_format($certificate->amount_paid, 2) }}</span>
                            </td>
                            <td>
                                @if($certificate->status == 'issued')
                                    <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                        <i class="bi bi-file-earmark-check"></i> Issued
                                    </span>
                                @elseif($certificate->status == 'claimed')
                                    <span class="badge" style="background: #d1fae5; color: #065f46;">
                                        <i class="bi bi-check-circle-fill"></i> Claimed
                                    </span>
                                @else
                                    <span class="badge" style="background: #fee2e2; color: #991b1b;">
                                        <i class="bi bi-x-circle-fill"></i> Cancelled
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('certificates.show', $certificate) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('certificates.pdf', $certificate) }}" class="btn btn-sm btn-outline-danger" title="Download PDF">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('certificates.print', $certificate) }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Print">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p class="mt-3 mb-0">No certificates found.</p>
                                    <small class="text-muted">Try adjusting your filters or issue a new certificate.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
        <div class="p-4 border-top">
            {{ $certificates->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
