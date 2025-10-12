@extends('layouts.app')

@section('title', 'Government Assistance - Barangay Matina Pangi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gift"></i> Government Assistance Programs</h2>
    <a href="{{ route('government-assistance.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Assistance Record
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by program name or resident..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="program_type" class="form-select">
                    <option value="">All Programs</option>
                    <option value="4ps">4Ps</option>
                    <option value="sss">SSS</option>
                    <option value="philhealth">PhilHealth</option>
                    <option value="ayuda">Ayuda</option>
                    <option value="scholarship">Scholarship</option>
                    <option value="livelihood">Livelihood</option>
                    <option value="housing">Housing</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-1">
                <select name="status" class="form-select">
                    <option value="">Status</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Assistance Records Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Program Name</th>
                        <th>Recipient</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date Received</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assistanceRecords as $record)
                        <tr>
                            <td><strong>{{ $record->program_name }}</strong></td>
                            <td>{{ $record->resident->full_name }}</td>
                            <td><span class="badge bg-primary">{{ strtoupper($record->program_type) }}</span></td>
                            <td>{{ $record->amount ? '₱' . number_format($record->amount, 2) : 'N/A' }}</td>
                            <td>{{ $record->date_received ? $record->date_received->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($record->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($record->status == 'completed')
                                    <span class="badge bg-info">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('government-assistance.show', $record) }}" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('government-assistance.edit', $record) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No assistance records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $assistanceRecords->links() }}
        </div>
    </div>
</div>
@endsection
