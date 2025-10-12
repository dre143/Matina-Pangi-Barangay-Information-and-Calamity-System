@extends('layouts.app')

@section('title', 'Edit Health Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-heart-pulse"></i> Edit Health Record</h2>
    <a href="{{ route('health-records.show', $healthRecord) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('health-records.update', $healthRecord) }}" method="POST">
            @csrf
            @method('PUT')
            @include('health-records.form')
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('health-records.show', $healthRecord) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
