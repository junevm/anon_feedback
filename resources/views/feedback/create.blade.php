@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Submit Anonymous Feedback</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="alert alert-info">
                    Your identity is protected. We do not store user IDs or IP addresses.
                </div>

                <form method="POST" action="{{ route('feedback.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select" required>
                            <option value="">Select a category...</option>
                            <option value="Work Culture">Work Culture</option>
                            <option value="Management">Management</option>
                            <option value="Workload">Workload</option>
                            <option value="Safety">Safety</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea name="message" id="message" rows="5" class="form-control" required placeholder="Type your feedback here..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Anonymously</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
