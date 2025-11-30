@extends('layouts.app')

@section('title', 'Profiling Hub')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Profiling Only</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="row g-3">
        @if(auth()->user()->isStaff())
        <div class="col-md-3">
            <a href="{{ route('staff.residents.index') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-people fs-3 text-primary"></i></div>
                    <h5 class="mb-1">Residents</h5>
                    <small class="text-muted">Browse resident profiles</small>
                </div>
            </a>
        </div>
        @else
        <div class="col-md-3">
            <a href="{{ route('households.index') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-house fs-3 text-primary"></i></div>
                    <h5 class="mb-1">Households</h5>
                    <small class="text-muted">Manage household records</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('residents.index') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-people fs-3 text-primary"></i></div>
                    <h5 class="mb-1">Residents</h5>
                    <small class="text-muted">Browse resident profiles</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('census.index') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-bar-chart fs-3 text-primary"></i></div>
                    <h5 class="mb-1">Census</h5>
                    <small class="text-muted">Population analytics</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('resident-transfers.index') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-arrow-left-right fs-3 text-primary"></i></div>
                    <h5 class="mb-1">Transfers</h5>
                    <small class="text-muted">Resident transfers</small>
                </div>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
