@extends('layouts.app')

@section('title', 'Edit Calamity Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Edit Calamity Record</h2>
    <a href="{{ route('calamities.show', $calamity) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('calamities.update', $calamity) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="calamity_name" class="form-label">Calamity Name <span class="text-danger">*</span></label>
                    <input type="text" name="calamity_name" id="calamity_name" class="form-control" value="{{ $calamity->calamity_name }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="calamity_type" class="form-label">Calamity Type <span class="text-danger">*</span></label>
                    <select name="calamity_type" id="calamity_type" class="form-select" required>
                        <option value="typhoon" {{ $calamity->calamity_type == 'typhoon' ? 'selected' : '' }}>Typhoon</option>
                        <option value="flood" {{ $calamity->calamity_type == 'flood' ? 'selected' : '' }}>Flood</option>
                        <option value="earthquake" {{ $calamity->calamity_type == 'earthquake' ? 'selected' : '' }}>Earthquake</option>
                        <option value="fire" {{ $calamity->calamity_type == 'fire' ? 'selected' : '' }}>Fire</option>
                        <option value="landslide" {{ $calamity->calamity_type == 'landslide' ? 'selected' : '' }}>Landslide</option>
                        <option value="drought" {{ $calamity->calamity_type == 'drought' ? 'selected' : '' }}>Drought</option>
                        <option value="other" {{ $calamity->calamity_type == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="date_occurred" class="form-label">Date Occurred <span class="text-danger">*</span></label>
                    <input type="date" name="date_occurred" id="date_occurred" class="form-control" value="{{ $calamity->date_occurred->format('Y-m-d') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="severity_level" class="form-label">Severity Level <span class="text-danger">*</span></label>
                    <select name="severity_level" id="severity_level" class="form-select" required>
                        <option value="minor" {{ $calamity->severity_level == 'minor' ? 'selected' : '' }}>Minor</option>
                        <option value="moderate" {{ $calamity->severity_level == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="severe" {{ $calamity->severity_level == 'severe' ? 'selected' : '' }}>Severe</option>
                        <option value="catastrophic" {{ $calamity->severity_level == 'catastrophic' ? 'selected' : '' }}>Catastrophic</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="affected_areas" class="form-label">Affected Areas</label>
                    <textarea name="affected_areas" id="affected_areas" rows="2" class="form-control">{{ $calamity->affected_areas }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control">{{ $calamity->description }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="response_actions" class="form-label">Response Actions</label>
                    <textarea name="response_actions" id="response_actions" rows="3" class="form-control">{{ $calamity->response_actions }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="ongoing" {{ $calamity->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="monitoring" {{ $calamity->status == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                        <option value="resolved" {{ $calamity->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('calamities.show', $calamity) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
