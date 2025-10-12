@extends('layouts.app')

@section('title', 'Health Records - Barangay Matina Pangi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-heart-pulse"></i> Health Records</h2>
    <a href="{{ route('health-records.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Health Record
    </a>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('health-records.index') }}" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by resident name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Health Records Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Resident</th>
                        <th>Blood Type</th>
                        <th>Height (cm)</th>
                        <th>Weight (kg)</th>
                        <th>PhilHealth #</th>
                        <th>Emergency Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($healthRecords as $record)
                        <tr>
                            <td><strong>{{ $record->resident->full_name }}</strong></td>
                            <td>{{ $record->blood_type ?? 'N/A' }}</td>
                            <td>{{ $record->height ?? 'N/A' }}</td>
                            <td>{{ $record->weight ?? 'N/A' }}</td>
                            <td>{{ $record->philhealth_number ?? 'N/A' }}</td>
                            <td>
                                {{ $record->emergency_contact ?? 'N/A' }}<br>
                                <small class="text-muted">{{ $record->emergency_contact_number }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('health-records.show', $record) }}" class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('health-records.edit', $record) }}" class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No health records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $healthRecords->links() }}
        </div>
    </div>
</div>
@endsection
