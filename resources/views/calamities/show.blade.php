@extends('layouts.app')

@section('title', 'Calamity Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Calamity Details</h2>
    <div class="btn-group">
        <a href="{{ route('calamities.edit', $calamity) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('calamities.add-households', $calamity) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Affected Household
        </a>
        <a href="{{ route('calamities.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Calamity Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Calamity Name:</strong>
                        <p>{{ $calamity->calamity_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Type:</strong>
                        <p><span class="badge bg-warning">{{ ucfirst($calamity->calamity_type) }}</span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date Occurred:</strong>
                        <p>{{ $calamity->date_occurred->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Severity Level:</strong>
                        <p>
                            @if($calamity->severity_level == 'catastrophic')
                                <span class="badge bg-danger">Catastrophic</span>
                            @elseif($calamity->severity_level == 'severe')
                                <span class="badge bg-danger">Severe</span>
                            @elseif($calamity->severity_level == 'moderate')
                                <span class="badge bg-warning">Moderate</span>
                            @else
                                <span class="badge bg-info">Minor</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            @if($calamity->status == 'ongoing')
                                <span class="badge bg-danger">Ongoing</span>
                            @elseif($calamity->status == 'monitoring')
                                <span class="badge bg-warning">Monitoring</span>
                            @else
                                <span class="badge bg-success">Resolved</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Reported By:</strong>
                        <p>{{ $calamity->reporter ? $calamity->reporter->name : 'N/A' }}</p>
                    </div>
                </div>

                @if($calamity->affected_areas)
                    <div class="mb-3">
                        <strong>Affected Areas:</strong>
                        <p>{{ $calamity->affected_areas }}</p>
                    </div>
                @endif

                @if($calamity->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $calamity->description }}</p>
                    </div>
                @endif

                @if($calamity->response_actions)
                    <div class="mb-3">
                        <strong>Response Actions:</strong>
                        <p>{{ $calamity->response_actions }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-house"></i> Affected Households ({{ $calamity->affectedHouseholds->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Household</th>
                                <th>Damage Level</th>
                                <th>Estimated Cost</th>
                                <th>Assistance Needed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($calamity->affectedHouseholds as $affected)
                                <tr>
                                    <td>{{ $affected->household->household_number }}</td>
                                    <td>
                                        @if($affected->damage_level == 'total')
                                            <span class="badge bg-danger">Total</span>
                                        @elseif($affected->damage_level == 'severe')
                                            <span class="badge bg-danger">Severe</span>
                                        @elseif($affected->damage_level == 'moderate')
                                            <span class="badge bg-warning">Moderate</span>
                                        @else
                                            <span class="badge bg-info">Minor</span>
                                        @endif
                                    </td>
                                    <td>{{ $affected->estimated_damage_cost ? '₱' . number_format($affected->estimated_damage_cost, 2) : 'N/A' }}</td>
                                    <td>{{ Str::limit($affected->assistance_needed ?? 'None specified', 30) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No affected households recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistics</h5>
            </div>
            <div class="card-body">
                <p><strong>Total Affected Households:</strong><br>{{ $calamity->affectedHouseholds->count() }}</p>
                <p><strong>Total Estimated Damage:</strong><br>₱{{ number_format($calamity->affectedHouseholds->sum('estimated_damage_cost'), 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
