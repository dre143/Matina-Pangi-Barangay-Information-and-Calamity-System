@extends('layouts.app')

@section('title', 'Relief Distribution')

@section('content')
<div class="section-offset">
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Relief Distribution</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-truck"></i> Relief Distribution</h2>
  <a href="{{ route('web.relief-distributions.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
</div>

<form method="GET" action="{{ route('web.relief-distributions.index') }}" class="card mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Household or item" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Item</label>
        <select name="item" class="form-select"></select>
      </div>
      <div class="col-md-2 align-self-end">
        <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Search</button>
      </div>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-body">
    @if(isset($distributions) && $distributions->count())
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Calamity</th>
            <th>Household</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Date</th>
            <th>Staff</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($distributions as $d)
          <tr>
            <td>{{ optional($d->calamity)->calamity_name }}</td>
            <td>{{ optional($d->household)->household_id }}</td>
            <td>{{ optional($d->item)->name }}</td>
            <td><span class="badge bg-success">{{ $d->quantity }}</span></td>
            <td>{{ optional($d->distributed_at)->format('Y-m-d') }}</td>
            <td>{{ optional($d->staff)->name }}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('relief-distributions.show',$d) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                <a href="{{ route('relief-distributions.edit',$d) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('relief-distributions.destroy',$d) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this distribution?')">
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
    <div class="mt-3">{{ $distributions->links() }}</div>
    @else
    <div class="text-center py-5">
      <i class="bi bi-truck" style="font-size:64px;color:#ccc;"></i>
      <p class="text-muted mt-3">No distributions found.</p>
    </div>
    @endif
  </div>
</div>
</div>
@endsection