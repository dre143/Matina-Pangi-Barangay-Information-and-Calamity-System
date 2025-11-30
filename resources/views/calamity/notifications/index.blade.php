@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="section-offset">
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Notifications</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-megaphone"></i> Notifications</h2>
  <a href="{{ route('web.notifications.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
</div>

<form method="GET" action="{{ route('web.notifications.index') }}" class="card mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Title or message" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small">Type</label>
        <select name="type" class="form-select">
          <option value="">All</option>
          <option value="sms">SMS</option>
          <option value="email">Email</option>
          <option value="system">System</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          <option value="draft">Draft</option>
          <option value="sent">Sent</option>
          <option value="failed">Failed</option>
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
    @if(isset($notifications) && $notifications->count())
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Sent At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($notifications as $n)
          <tr>
            <td><strong class="text-primary">{{ $n->title }}</strong></td>
            <td><span class="badge bg-info">{{ strtoupper($n->type) }}</span></td>
            <td><span class="badge bg-{{ $n->status==='sent'?'success':($n->status==='failed'?'danger':'secondary') }}">{{ ucfirst($n->status) }}</span></td>
            <td>{{ optional($n->sent_at)->format('Y-m-d') }}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('web.notifications.show',$n) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                <a href="{{ route('web.notifications.edit',$n) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('web.notifications.destroy',$n) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notification?')">
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
    <div class="mt-3">{{ $notifications->links() }}</div>
    @else
    <div class="text-center py-5">
      <i class="bi bi-megaphone" style="font-size:64px;color:#ccc;"></i>
      <p class="text-muted mt-3">No notifications found.</p>
    </div>
    @endif
  </div>
</div>
</div>
@endsection