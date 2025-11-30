@extends('layouts.app')

@section('title', 'Damage Assessment')

@section('content')
<div class="section-offset">
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Damage Assessment</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-clipboard-check"></i> Damage Assessment</h2>
  <a href="{{ route('web.damage-assessments.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
</div>

<form method="GET" action="{{ route('web.damage-assessments.index') }}" class="card mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small">Calamity</label>
        <select name="calamity_id" class="form-select">
          <option value="">All</option>
          @isset($calamities)
          @foreach($calamities as $c)
            <option value="{{ $c->id }}" {{ request('calamity_id') == $c->id ? 'selected' : '' }}>
              {{ $c->calamity_name }}
            </option>
          @endforeach
          @endisset
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small">Damage Level</label>
        <select name="damage_level" class="form-select">
          <option value="">All</option>
          <option value="minor">Minor</option>
          <option value="moderate">Moderate</option>
          <option value="severe">Severe</option>
          <option value="total">Total</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small">Assessed Date</label>
        <input type="date" name="assessed_at" class="form-control" value="{{ request('assessed_at') }}">
      </div>
      <div class="col-md-2 align-self-end">
        <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Search</button>
      </div>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-body">
    @if(isset($assessments) && $assessments->count())
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Calamity</th>
            <th>Household</th>
            <th>Damage Level</th>
            <th>Estimated Cost</th>
            <th>Assessed At</th>
            <th>Assessor</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($assessments as $a)
          <tr>
            <td>{{ optional($a->calamity)->calamity_name }}</td>
            <td>{{ optional($a->household)->household_id }}</td>
            <td><span class="badge bg-{{ in_array($a->damage_level,['severe','total'])?'danger':($a->damage_level==='moderate'?'warning':'success') }}">{{ ucfirst($a->damage_level) }}</span></td>
            <td><span class="badge bg-info">{{ number_format($a->estimated_cost,2) }}</span></td>
            <td>{{ optional($a->assessed_at)->format('Y-m-d') }}</td>
            <td>{{ optional($a->assessor)->name }}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('web.damage-assessments.show',$a) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                <a href="{{ route('web.damage-assessments.edit',$a) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('web.damage-assessments.destroy',$a) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this assessment?')">
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
    <div class="mt-3">{{ $assessments->links() }}</div>
    @else
    <div class="text-center py-5">
      <i class="bi bi-clipboard-check" style="font-size:64px;color:#ccc;"></i>
      <p class="text-muted mt-3">No assessments found.</p>
    </div>
    @endif
  </div>
</div>
</div>
@endsection
