@extends('layouts.app')

@section('title', 'Relief Inventory')

@section('content')
<div class="section-offset">
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Relief Inventory</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-box-seam"></i> Relief Inventory</h2>
  <a href="{{ route('web.relief-items.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
</div>

<form method="GET" action="{{ route('web.relief-items.index') }}" class="card mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Item or category" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Category</label>
        <select name="category" class="form-select">
          <option value="">All</option>
          <option value="food">Food</option>
          <option value="water">Water</option>
          <option value="blanket">Blanket</option>
          <option value="medicine">Medicine</option>
          <option value="clothes">Clothes</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small">Min Quantity</label>
        <input type="number" name="min_qty" class="form-control" value="{{ request('min_qty') }}">
      </div>
      <div class="col-md-2 align-self-end">
        <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Search</button>
      </div>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-body">
    @if(isset($items) && $items->count())
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Item</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $item)
          <tr>
            <td><strong class="text-primary">{{ $item->name }}</strong></td>
            <td><span class="badge bg-info">{{ ucfirst($item->category) }}</span></td>
            <td><span class="badge bg-success">{{ $item->quantity }}</span></td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('web.relief-items.show',$item) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                <a href="{{ route('web.relief-items.edit',$item) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('web.relief-items.destroy',$item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?')">
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
    <div class="mt-3">{{ $items->links() }}</div>
    @else
    <div class="text-center py-5">
      <i class="bi bi-box-seam" style="font-size:64px;color:#ccc;"></i>
      <p class="text-muted mt-3">No inventory items found.</p>
    </div>
    @endif
  </div>
</div>
</div>
@endsection
