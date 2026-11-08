@extends('layouts.app')

@section('title', 'My Tasks')

@section('heading', 'My Tasks')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h5>All Tasks</h5>

    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add Task
    </a>

</div>

<form method="GET" action="{{ route('tasks.index') }}" class="card card-body mb-3">

    <div class="row g-2">

        <div class="col-md-3">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search task..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">

            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                <option value="in_progress" @selected(request('status') == 'in_progress')>In Progress</option>
                <option value="completed" @selected(request('status') == 'completed')>Completed</option>
            </select>

        </div>

        <div class="col-md-2">

            <select name="priority" class="form-select">
                <option value="">All Priority</option>
                @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                    <option value="{{ $priority }}" @selected(request('priority') == $priority)>
                        {{ ucfirst($priority) }}
                    </option>
                @endforeach
            </select>

        </div>

        <div class="col-md-2">

            <select name="category_id" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

        </div>

        <div class="col-md-3">
            <button class="btn btn-dark">Filter</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Reset</a>
        </div>

    </div>

</form>

<div class="card shadow-sm">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-dark">

                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($tasks as $task)

                    <tr>

                        <td>
                            <strong>{{ $task->title }}</strong>
                            <div class="small text-muted">
                                {{ Str::limit($task->description, 50) }}
                            </div>
                        </td>

                        <td>{{ $task->category?->name ?? '-' }}</td>

                        <td>
                            {{ ucfirst($task->priority) }}
                        </td>

                        <td>
                            <select class="form-select form-select-sm task-status"
                                    data-url="{{ route('tasks.status', $task) }}"
                                    style="min-width: 130px">

                                <option value="pending" @selected($task->status == 'pending')>
                                    Pending
                                </option>

                                <option value="in_progress" @selected($task->status == 'in_progress')>
                                    In Progress
                                </option>

                                <option value="completed" @selected($task->status == 'completed')>
                                    Completed
                                </option>

                            </select>
                        </td>

                        <td>
                            {{ $task->due_date?->format('d M Y') ?? '-' }}
                        </td>

                        <td>

                            <a href="{{ route('tasks.edit', $task) }}"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('tasks.destroy', $task) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this task?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center p-4">
                            No tasks found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="p-3">
        {{ $tasks->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection

@push('scripts')

<script>
document.querySelectorAll('.task-status').forEach(select => {

    select.addEventListener('change', async function () {

        const previousStatus = this.dataset.previous || '';

        try {

            const response = await fetch(this.dataset.url, {
                method: 'PATCH',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
                },

                body: JSON.stringify({
                    status: this.value
                })
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Unable to update status');
            }

            alert(result.message);

        } catch (error) {

            alert(error.message);

            if (previousStatus) {
                this.value = previousStatus;
            }

        }

        this.dataset.previous = this.value;

    });

    select.dataset.previous = select.value;

});

if (window.Echo && window.currentUserId) {

    window.Echo.private(`users.${window.currentUserId}`)
        .listen('.task.status.updated', (event) => {

            console.log('Real-time task update:', event);

            alert(
                `${event.title} status changed to ${event.status}`
            );

        });

}
</script>

@endpush