@extends('layouts.app')

@section('title', 'Evacuation Centers')

@section('content')
<div class="section-offset">
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Evacuation Centers</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-building"></i> Evacuation Centers</h2>
  <a href="{{ route('web.evacuation-centers.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
</div>

<form method="GET" action="{{ route('web.evacuation-centers.index') }}" class="card mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Center name or location" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Capacity Min</label>
        <input type="number" name="capacity_min" class="form-control" value="{{ request('capacity_min') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Occupancy Max</label>
        <input type="number" name="occupancy_max" class="form-control" value="{{ request('occupancy_max') }}">
      </div>
      <div class="col-md-2 align-self-end">
        <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Search</button>
      </div>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-body">
    @if(isset($centers) && $centers->count())
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Occupancy</th>
            <th>Facilities</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($centers as $center)
          <tr>
            <td><strong class="text-primary">{{ $center->name }}</strong></td>
            <td>{{ $center->location }}</td>
            <td><span class="badge bg-info">{{ $center->capacity }}</span></td>
            <td><span class="badge bg-{{ ($center->current_occupancy ?? 0) > ($center->capacity ?? 0) ? 'danger' : 'success' }}">{{ $center->current_occupancy }}</span></td>
            <td>{{ is_array($center->facilities) ? implode(', ', $center->facilities) : $center->facilities }}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('evacuation-centers.show',$center) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                <a href="{{ route('evacuation-centers.edit',$center) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('evacuation-centers.destroy',$center) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this center?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $centers->links() }}</div>
    @else
    <div class="text-center py-5">
      <i class="bi bi-building" style="font-size:64px;color:#ccc;"></i>
      <p class="text-muted mt-3">No evacuation centers found.</p>
    </div>
    @endif
  </div>
</div>
</div>
@endsection