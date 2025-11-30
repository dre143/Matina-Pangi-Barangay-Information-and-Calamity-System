@extends('layouts.app')

@section('title', 'Calamity Incidents')

@section('content')
<div class="section-offset">
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Calamity Incidents</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-lightning-fill"></i> Calamity Incidents</h2>
  <a href="{{ route('calamities.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
}</div>

<form method="GET" action="{{ route('calamities.index') }}" class="card mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Type or description" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Type</label>
        <select name="calamity_type" class="form-select">
          <option value="">All</option>
          <option value="typhoon" {{ request('calamity_type')=='typhoon'?'selected':'' }}>Typhoon</option>
          <option value="flood" {{ request('calamity_type')=='flood'?'selected':'' }}>Flood</option>
          <option value="earthquake" {{ request('calamity_type')=='earthquake'?'selected':'' }}>Earthquake</option>
          <option value="fire" {{ request('calamity_type')=='fire'?'selected':'' }}>Fire</option>
          <option value="landslide" {{ request('calamity_type')=='landslide'?'selected':'' }}>Landslide</option>
          <option value="drought" {{ request('calamity_type')=='drought'?'selected':'' }}>Drought</option>
          <option value="other" {{ request('calamity_type')=='other'?'selected':'' }}>Other</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          <option value="ongoing" {{ request('status')=='ongoing'?'selected':'' }}>Ongoing</option>
          <option value="resolved" {{ request('status')=='resolved'?'selected':'' }}>Resolved</option>
          <option value="monitoring" {{ request('status')=='monitoring'?'selected':'' }}>Monitoring</option>
        </select>
      </div>
      <div class="col-md-2 align-self-end">
        <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Search</button>
      </div>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-body">
    @if(isset($calamities) && $calamities->count())
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Date</th>
            <th>Time</th>
            <th>Severity</th>
            <th>Status</th>
            <th>Affected Areas</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($calamities as $calamity)
          <tr>
            <td><strong class="text-primary">{{ $calamity->calamity_name }}</strong></td>
            <td><span class="badge bg-info">{{ ucfirst($calamity->calamity_type) }}</span></td>
            <td>{{ $calamity->date_occurred?->format('Y-m-d') }}</td>
            <td>{{ $calamity->occurred_time }}</td>
            <td>
              @php $sev = $calamity->severity ?? $calamity->severity_level; $color = $sev==='severe'||$sev==='catastrophic'?'danger':($sev==='moderate'?'warning':'success'); @endphp
              <span class="badge bg-{{ $color }}">{{ ucfirst($sev) }}</span>
            </td>
            <td><span class="badge bg-{{ $calamity->status==='ongoing'?'warning':($calamity->status==='resolved'?'success':'secondary') }}">{{ ucfirst($calamity->status) }}</span></td>
            <td>{{ $calamity->affected_areas }}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('calamities.show',$calamity) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                <a href="{{ route('calamities.edit',$calamity) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('calamities.destroy',$calamity) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive this incident? You can restore it from Archived Incidents.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-secondary"><i class="bi bi-archive"></i> Archive</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $calamities->links() }}</div>
    @else
    <div class="text-center py-5">
      <i class="bi bi-lightning-fill" style="font-size:64px;color:#ccc;"></i>
      <p class="text-muted mt-3">No incidents found.</p>
    </div>
    @endif
  </div>
</div>
</div>
@endsection