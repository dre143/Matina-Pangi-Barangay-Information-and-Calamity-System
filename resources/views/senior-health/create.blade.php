@extends('layouts.app')

@section('title', 'Add Senior Health Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-person-cane"></i> Add Senior Health Record</h2>
    <a href="{{ route('senior-health.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('senior-health.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="resident_id" class="form-label">Senior Citizen <span class="text-danger">*</span></label>
                    <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                        <option value="">Select Senior Citizen (60+ years old)</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}">{{ $resident->full_name }} ({{ $resident->age }} years)</option>
                        @endforeach
                    </select>
                    @error('resident_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="mobility_status" class="form-label">Mobility Status</label>
                    <select name="mobility_status" id="mobility_status" class="form-select">
                        <option value="">Select Status</option>
                        <option value="independent">Independent</option>
                        <option value="assisted">Assisted</option>
                        <option value="wheelchair">Wheelchair</option>
                        <option value="bedridden">Bedridden</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="last_checkup_date" class="form-label">Last Checkup Date</label>
                    <input type="date" name="last_checkup_date" id="last_checkup_date" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="health_conditions" class="form-label">Health Conditions</label>
                    <textarea name="health_conditions" id="health_conditions" rows="3" class="form-control" placeholder="List any health conditions..."></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="medications" class="form-label">Current Medications</label>
                    <textarea name="medications" id="medications" rows="3" class="form-control" placeholder="List current medications..."></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('senior-health.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
