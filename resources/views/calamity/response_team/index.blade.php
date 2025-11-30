@extends('layouts.app')

@section('title', 'Response Team')

@section('content')
<div class="section-offset">
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Response Team</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-people-fill"></i> Response Team</h2>
  <a href="{{ route('web.response-team-members.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
</div>

@php
  $calamities = \App\Models\Calamity::orderByDesc('date_occurred')->get();
  $query = \App\Models\ResponseTeamMember::with(['calamity','evacuationCenter']);
  if (request('search')) { $s = request('search'); $query->where('name','like',"%{$s}%"); }
  if (request('role')) { $query->where('role','like',"%".request('role')."%"); }
  if (request('calamity_id')) { $query->where('calamity_id', request('calamity_id')); }
  $members = $query->latest()->paginate(20);
@endphp

<form method="GET" action="{{ route('web.response-team-members.index') }}" class="card mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Name or role" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Role</label>
        <input type="text" name="role" class="form-control" value="{{ request('role') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Calamity</label>
        <select name="calamity_id" class="form-select">
          <option value="">All</option>
          @foreach($calamities as $c)
            <option value="{{ $c->id }}" {{ request('calamity_id')==$c->id?'selected':'' }}>{{ $c->calamity_name }} ({{ $c->date_occurred }})</option>
          @endforeach
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
    @if($members->count())
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Role</th>
            <th>Skills</th>
            <th>Assigned Center</th>
            <th>Rescues</th>
            <th>Households Helped</th>
          </tr>
        </thead>
        <tbody>
          @foreach($members as $m)
          <tr>
            <td><strong class="text-primary">{{ $m->name }}</strong></td>
            <td><span class="badge bg-info">{{ $m->role }}</span></td>
            <td>{{ is_array($m->skills) ? implode(', ', $m->skills) : $m->skills }}</td>
            <td>{{ optional($m->evacuationCenter)->name }}</td>
            <td>{{ $m->rescueOperations()->count() }}</td>
            <td>{{ $m->rescueOperations()->distinct('calamity_affected_household_id')->count('calamity_affected_household_id') }}</td>
            
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $members->withQueryString()->links() }}</div>
    @else
    <div class="text-center py-5">
      <i class="bi bi-people-fill" style="font-size:64px;color:#ccc;"></i>
      <p class="text-muted mt-3">No response team members found.</p>
    </div>
    @endif
  </div>
</div>
</div>
@endsection