@extends('layouts.app')

@section('title', 'Edit PWD Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-universal-access"></i> Edit PWD Record</h2>
    <a href="{{ route('pwd-support.show', $pwdSupport) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pwd-support.update', $pwdSupport) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Resident</label>
                    <input type="text" class="form-control" value="{{ $pwdSupport->resident->full_name }}" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="pwd_id_number" class="form-label">PWD ID Number <span class="text-danger">*</span></label>
                    <input type="text" name="pwd_id_number" id="pwd_id_number" class="form-control bg-light" value="{{ $pwdSupport->pwd_id_number }}" readonly required>
                    <small class="text-muted">PWD ID cannot be changed</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="disability_type" class="form-label">Disability Type <span class="text-danger">*</span></label>
                    <select name="disability_type" id="disability_type" class="form-select" required>
                        <option value="visual" {{ $pwdSupport->disability_type == 'visual' ? 'selected' : '' }}>Visual Impairment</option>
                        <option value="hearing" {{ $pwdSupport->disability_type == 'hearing' ? 'selected' : '' }}>Hearing Impairment</option>
                        <option value="mobility" {{ $pwdSupport->disability_type == 'mobility' ? 'selected' : '' }}>Mobility Impairment</option>
                        <option value="mental" {{ $pwdSupport->disability_type == 'mental' ? 'selected' : '' }}>Mental Disability</option>
                        <option value="psychosocial" {{ $pwdSupport->disability_type == 'psychosocial' ? 'selected' : '' }}>Psychosocial Disability</option>
                        <option value="multiple" {{ $pwdSupport->disability_type == 'multiple' ? 'selected' : '' }}>Multiple Disabilities</option>
                        <option value="other" {{ $pwdSupport->disability_type == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="date_registered" class="form-label">Date Registered <span class="text-danger">*</span></label>
                    <input type="date" name="date_registered" id="date_registered" class="form-control" value="{{ $pwdSupport->date_registered ? $pwdSupport->date_registered->format('Y-m-d') : '' }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="disability_description" class="form-label">Disability Description</label>
                    <textarea name="disability_description" id="disability_description" rows="3" class="form-control">{{ $pwdSupport->disability_description }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="assistance_received" class="form-label">Assistance Received</label>
                    <textarea name="assistance_received" id="assistance_received" rows="2" class="form-control">{{ $pwdSupport->assistance_received }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="medical_needs" class="form-label">Medical Needs</label>
                    <textarea name="medical_needs" id="medical_needs" rows="2" class="form-control">{{ $pwdSupport->medical_needs }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control">{{ $pwdSupport->notes }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pwd-support.show', $pwdSupport) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
