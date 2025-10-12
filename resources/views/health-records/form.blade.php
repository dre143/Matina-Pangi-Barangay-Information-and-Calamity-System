<div class="row">
    <div class="col-md-6 mb-3">
        <label for="resident_id" class="form-label">Resident <span class="text-danger">*</span></label>
        <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required {{ isset($healthRecord) ? 'disabled' : '' }}>
            <option value="">Select Resident</option>
            @foreach($residents as $resident)
                <option value="{{ $resident->id }}" {{ (old('resident_id') ?? $healthRecord->resident_id ?? '') == $resident->id ? 'selected' : '' }}>
                    {{ $resident->full_name }}
                </option>
            @endforeach
        </select>
        @error('resident_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="blood_type" class="form-label">Blood Type</label>
        <select name="blood_type" id="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
            <option value="">Select Blood Type</option>
            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                <option value="{{ $type }}" {{ (old('blood_type') ?? $healthRecord->blood_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        @error('blood_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="philhealth_number" class="form-label">PhilHealth Number</label>
        <input type="text" name="philhealth_number" id="philhealth_number" class="form-control @error('philhealth_number') is-invalid @enderror" value="{{ old('philhealth_number') ?? $healthRecord->philhealth_number ?? '' }}">
        @error('philhealth_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="height" class="form-label">Height (cm)</label>
        <input type="number" name="height" id="height" step="0.01" class="form-control @error('height') is-invalid @enderror" value="{{ old('height') ?? $healthRecord->height ?? '' }}">
        @error('height')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="weight" class="form-label">Weight (kg)</label>
        <input type="number" name="weight" id="weight" step="0.01" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight') ?? $healthRecord->weight ?? '' }}">
        @error('weight')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="emergency_contact" class="form-label">Emergency Contact Name</label>
        <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact') ?? $healthRecord->emergency_contact ?? '' }}">
        @error('emergency_contact')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
        <input type="text" name="emergency_contact_number" id="emergency_contact_number" class="form-control @error('emergency_contact_number') is-invalid @enderror" value="{{ old('emergency_contact_number') ?? $healthRecord->emergency_contact_number ?? '' }}">
        @error('emergency_contact_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="medical_conditions" class="form-label">Medical Conditions</label>
        <textarea name="medical_conditions" id="medical_conditions" rows="3" class="form-control @error('medical_conditions') is-invalid @enderror">{{ old('medical_conditions') ?? $healthRecord->medical_conditions ?? '' }}</textarea>
        @error('medical_conditions')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="allergies" class="form-label">Allergies</label>
        <textarea name="allergies" id="allergies" rows="3" class="form-control @error('allergies') is-invalid @enderror">{{ old('allergies') ?? $healthRecord->allergies ?? '' }}</textarea>
        @error('allergies')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="medications" class="form-label">Current Medications</label>
        <textarea name="medications" id="medications" rows="3" class="form-control @error('medications') is-invalid @enderror">{{ old('medications') ?? $healthRecord->medications ?? '' }}</textarea>
        @error('medications')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="notes" class="form-label">Additional Notes</label>
        <textarea name="notes" id="notes" rows="2" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') ?? $healthRecord->notes ?? '' }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
