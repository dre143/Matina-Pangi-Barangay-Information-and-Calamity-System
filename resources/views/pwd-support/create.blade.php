@extends('layouts.app')

@section('title', 'Register PWD')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-universal-access"></i> Register PWD</h2>
    <a href="{{ route('pwd-support.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pwd-support.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="resident_id" class="form-label">Resident <span class="text-danger">*</span></label>
                    <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                        <option value="">Select Resident</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}">{{ $resident->full_name }}</option>
                        @endforeach
                    </select>
                    @error('resident_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="pwd_id_number" class="form-label">PWD ID Number <span class="text-danger">*</span></label>
                    <input type="text" name="pwd_id_number" id="pwd_id_number" class="form-control @error('pwd_id_number') is-invalid @enderror" required>
                    @error('pwd_id_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="disability_type" class="form-label">Disability Type <span class="text-danger">*</span></label>
                    <select name="disability_type" id="disability_type" class="form-select @error('disability_type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        <option value="visual">Visual Impairment</option>
                        <option value="hearing">Hearing Impairment</option>
                        <option value="mobility">Mobility Impairment</option>
                        <option value="mental">Mental Disability</option>
                        <option value="psychosocial">Psychosocial Disability</option>
                        <option value="multiple">Multiple Disabilities</option>
                        <option value="other">Other</option>
                    </select>
                    @error('disability_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="date_registered" class="form-label">Date Registered <span class="text-danger">*</span></label>
                    <input type="date" name="date_registered" id="date_registered" class="form-control @error('date_registered') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                    @error('date_registered')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="disability_description" class="form-label">Disability Description</label>
                    <textarea name="disability_description" id="disability_description" rows="3" class="form-control" placeholder="Describe the disability..."></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="assistance_received" class="form-label">Assistance Received</label>
                    <textarea name="assistance_received" id="assistance_received" rows="2" class="form-control" placeholder="List assistance received..."></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="medical_needs" class="form-label">Medical Needs</label>
                    <textarea name="medical_needs" id="medical_needs" rows="2" class="form-control" placeholder="List medical needs..."></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pwd-support.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Register PWD
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
