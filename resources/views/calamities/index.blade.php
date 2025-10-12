@extends('layouts.app')

@section('title', 'Calamity Management - Barangay Matina Pangi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Calamity Management</h2>
    <a href="{{ route('calamities.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Record Calamity
    </a>
</div>

<!-- Calamities Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Calamity Name</th>
                        <th>Type</th>
                        <th>Date Occurred</th>
                        <th>Severity</th>
                        <th>Affected Households</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($calamities as $calamity)
                        <tr>
                            <td><strong>{{ $calamity->calamity_name }}</strong></td>
                            <td><span class="badge bg-warning">{{ ucfirst($calamity->calamity_type) }}</span></td>
                            <td>{{ $calamity->date_occurred->format('M d, Y') }}</td>
                            <td>
                                @if($calamity->severity_level == 'catastrophic')
                                    <span class="badge bg-danger">Catastrophic</span>
                                @elseif($calamity->severity_level == 'severe')
                                    <span class="badge bg-danger">Severe</span>
                                @elseif($calamity->severity_level == 'moderate')
                                    <span class="badge bg-warning">Moderate</span>
                                @else
                                    <span class="badge bg-info">Minor</span>
                                @endif
                            </td>
                            <td>{{ $calamity->affected_households_count }} households</td>
                            <td>
                                @if($calamity->status == 'ongoing')
                                    <span class="badge bg-danger">Ongoing</span>
                                @elseif($calamity->status == 'monitoring')
                                    <span class="badge bg-warning">Monitoring</span>
                                @else
                                    <span class="badge bg-success">Resolved</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('calamities.show', $calamity) }}" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('calamities.edit', $calamity) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No calamity records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $calamities->links() }}
        </div>
    </div>
</div>
@endsection
