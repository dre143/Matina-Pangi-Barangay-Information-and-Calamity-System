@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Purok Management</h2>
        @if(auth()->user()->isSecretary())
        <a href="{{ route('puroks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Purok
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($puroks as $purok)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $purok->purok_name }}</h5>
                    <p class="text-muted mb-3">Code: <strong>{{ $purok->purok_code }}</strong></p>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Households:</span>
                            <strong>{{ $purok->households_count ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Population:</span>
                            <strong>{{ $purok->residents_count ?? 0 }}</strong>
                        </div>
                    </div>

                    @if($purok->purok_leader_name)
                    <div class="mb-3">
                        <small class="text-muted">Purok Leader:</small>
                        <p class="mb-0"><strong>{{ $purok->purok_leader_name }}</strong></p>
                        @if($purok->purok_leader_contact)
                        <small>{{ $purok->purok_leader_contact }}</small>
                        @endif
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('puroks.show', $purok) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> View
                        </a>
                        @if(auth()->user()->isSecretary())
                        <a href="{{ route('puroks.edit', $purok) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('puroks.update-counts', $purok) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary" title="Update Counts">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                No puroks found. <a href="{{ route('puroks.create') }}">Create one now</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
