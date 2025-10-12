@extends('layouts.app')

@section('title', 'Add Government Assistance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gift"></i> Add Government Assistance</h2>
    <a href="{{ route('government-assistance.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('government-assistance.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="resident_id" class="form-label">Recipient <span class="text-danger">*</span></label>
                    <select name="resident_id" id="resident_id" class="form-select" required>
                        <option value="">Select Resident</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}">{{ $resident->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="program_name" class="form-label">Program Name <span class="text-danger">*</span></label>
                    <input type="text" name="program_name" id="program_name" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="program_type" class="form-label">Program Type <span class="text-danger">*</span></label>
                    <select name="program_type" id="program_type" class="form-select" required>
                        <option value="">Select Type</option>
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

                <div class="col-md-4 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="form-control" placeholder="0.00">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="date_received" class="form-label">Date Received <span class="text-danger">*</span></label>
                    <input type="date" name="date_received" id="date_received" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Program description..."></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('government-assistance.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
