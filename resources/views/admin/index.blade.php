@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admin Dashboard</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Message</th>
                        <th>Toxicity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $feedback)
                    <tr class="{{ $feedback->is_toxic ? 'table-warning' : '' }}">
                        <td>{{ $feedback->id }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $feedback->category }}</span>
                        </td>
                        <td>
                            <p class="mb-0">{{ \Illuminate\Support\Str::limit($feedback->message, 100) }}</p>
                            @if(strlen($feedback->message) > 100)
                                <a href="#" data-bs-toggle="modal" data-bs-target="#msgModal{{ $feedback->id }}">Read more</a>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="msgModal{{ $feedback->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Feedback #{{ $feedback->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $feedback->message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($feedback->is_toxic)
                                <span class="badge bg-danger">Toxic</span>
                            @else
                                <span class="badge bg-success">Clean</span>
                            @endif
                        </td>
                        <td>
                            @if($feedback->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($feedback->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Flagged</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.update', $feedback->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="status" value="approved" class="btn btn-sm btn-outline-success">Approve</button>
                                <button type="submit" name="status" value="flagged" class="btn btn-sm btn-outline-danger">Flag</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No feedback submitted yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $feedbacks->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
