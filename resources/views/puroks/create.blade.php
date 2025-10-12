@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Purok</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('puroks.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Purok Name <span class="text-danger">*</span></label>
                            <input type="text" name="purok_name" class="form-control @error('purok_name') is-invalid @enderror" 
                                   value="{{ old('purok_name') }}" placeholder="e.g., Purok 1, Purok Maligaya" required>
                            @error('purok_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Purok Code <span class="text-danger">*</span></label>
                            <input type="text" name="purok_code" class="form-control @error('purok_code') is-invalid @enderror" 
                                   value="{{ old('purok_code') }}" placeholder="e.g., P1, P-MAL" required>
                            @error('purok_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Unique identifier for this purok</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Purok Leader Name</label>
                            <input type="text" name="purok_leader_name" class="form-control @error('purok_leader_name') is-invalid @enderror" 
                                   value="{{ old('purok_leader_name') }}">
                            @error('purok_leader_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Leader Contact Number</label>
                            <input type="text" name="purok_leader_contact" class="form-control @error('purok_leader_contact') is-invalid @enderror" 
                                   value="{{ old('purok_leader_contact') }}" placeholder="09XXXXXXXXX">
                            @error('purok_leader_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Boundaries</label>
                            <textarea name="boundaries" class="form-control @error('boundaries') is-invalid @enderror" 
                                      rows="3" placeholder="Describe the geographic boundaries of this purok">{{ old('boundaries') }}</textarea>
                            @error('boundaries')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Create Purok
                            </button>
                            <a href="{{ route('puroks.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
