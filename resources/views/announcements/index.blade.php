@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-megaphone"></i> Announcements</h2>
    <a href="{{ route('announcements.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
</div>

<div class="card">
    <div class="card-body">
        @if($announcements->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Urgency</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($announcements as $a)
                    <tr>
                        <td><strong class="text-primary">{{ $a->title }}</strong></td>
                        <td>{{ $a->urgency ?: 'Normal' }}</td>
                        <td><span class="badge bg-{{ $a->status==='sent' ? 'success' : 'secondary' }}">{{ ucfirst($a->status) }}</span></td>
                        <td>{{ optional($a->sent_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('announcements.show', $a) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $announcements->links() }}</div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-megaphone" style="font-size:64px;color:#ccc;"></i>
            <p class="text-muted mt-3">No announcements yet.</p>
        </div>
        @endif
    </div>
 </div>
@endsection