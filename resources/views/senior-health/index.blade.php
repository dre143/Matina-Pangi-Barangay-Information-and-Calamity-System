@extends('layouts.app')

@section('title', 'Senior Health - Barangay Matina Pangi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-person-cane"></i> Senior Health Records</h2>
    <a href="{{ route('senior-health.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Senior Health Record
    </a>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by senior citizen name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Records Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Senior Citizen</th>
                        <th>Age</th>
                        <th>Mobility Status</th>
                        <th>Last Checkup</th>
                        <th>Health Conditions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seniorHealthRecords as $record)
                        <tr>
                            <td><strong>{{ $record->resident->full_name }}</strong></td>
                            <td>{{ $record->resident->age }} years</td>
                            <td>
                                @if($record->mobility_status == 'independent')
                                    <span class="badge bg-success">Independent</span>
                                @elseif($record->mobility_status == 'assisted')
                                    <span class="badge bg-info">Assisted</span>
                                @elseif($record->mobility_status == 'wheelchair')
                                    <span class="badge bg-warning">Wheelchair</span>
                                @else
                                    <span class="badge bg-danger">Bedridden</span>
                                @endif
                            </td>
                            <td>{{ $record->last_checkup_date ? $record->last_checkup_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ Str::limit($record->health_conditions ?? 'None specified', 40) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('senior-health.show', $record) }}" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('senior-health.edit', $record) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No senior health records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $seniorHealthRecords->links() }}
        </div>
    </div>
</div>
@endsection
