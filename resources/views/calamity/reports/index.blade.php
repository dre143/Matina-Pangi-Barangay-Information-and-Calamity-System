@extends('layouts.app')
@section('title','Calamity Report')
@section('content')
<div class="section-offset">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Calamity Report</h4>
  </div>
  <div class="card">
    <div class="card-body">
      @if($calamities->count())
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Type</th>
              <th>Date</th>
              <th>Severity</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($calamities as $c)
            <tr>
              <td>{{ $c->id }}</td>
              <td>{{ $c->calamity_name ?? ucfirst($c->calamity_type) }}</td>
              <td>{{ ucfirst($c->calamity_type) }}</td>
              <td>{{ optional($c->date_occurred)->format('Y-m-d') }}</td>
              <td>{{ $c->severity_level }}</td>
              <td class="text-end">
                <a href="{{ route('web.calamity-reports.show',$c) }}" class="btn btn-primary btn-sm"><i class="bi bi-file-text"></i> View Report</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-3">{{ $calamities->links() }}</div>
      @else
      <div class="text-center py-5 text-muted">No calamity records found.</div>
      @endif
    </div>
  </div>
</div>
@endsection