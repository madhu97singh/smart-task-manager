@extends('layouts.app')

@section('title', 'Notifications')
@section('heading', 'Notifications')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        @forelse($notifications as $notification)

            <div class="border-bottom py-3 d-flex justify-content-between">

                <div>
                    <strong>
                        {{ $notification->data['title'] ?? 'Notification' }}
                    </strong>

                    <p class="mb-1">
                        {{ $notification->data['message'] ?? '' }}
                    </p>

                    <small class="text-muted">
                        {{ $notification->created_at->diffForHumans() }}
                    </small>
                </div>

                @if(!$notification->read_at)
                    <button
                        class="btn btn-sm btn-outline-primary mark-read"
                        data-url="{{ route('notifications.read', $notification->id) }}">
                        Mark as read
                    </button>
                @else
                    <span class="badge bg-success">Read</span>
                @endif

            </div>

        @empty

            <p class="text-center">No notifications found.</p>

        @endforelse

        {{ $notifications->links('pagination::bootstrap-5') }}

    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.mark-read').forEach(button => {

    button.addEventListener('click', async function () {

        const response = await fetch(this.dataset.url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok) {
            this.outerHTML = '<span class="badge bg-success">Read</span>';
        } else {
            alert(result.message || 'Unable to update notification');
        }
    });

});
</script>
@endpush