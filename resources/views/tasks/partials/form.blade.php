<div class="mb-3">

    <label class="form-label">Task Title</label>

    <input type="text"
           name="title"
           class="form-control"
           value="{{ old('title', $task->title ?? '') }}"
           required>

    @error('title')
        <small class="text-danger">{{ $message }}</small>
    @enderror

</div>

<div class="mb-3">

    <label class="form-label">Description</label>

    <textarea name="description"
              class="form-control"
              rows="4">{{ old('description', $task->description ?? '') }}</textarea>

</div>

<div class="mb-3">

    <label class="form-label">Category</label>

    <select name="category_id" class="form-select">

        <option value="">Select Category</option>

        @foreach($categories as $category)

            <option value="{{ $category->id }}"
                @selected(old('category_id', $task->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>

        @endforeach

    </select>

</div>

<div class="mb-3">

    <label class="form-label">Priority</label>

    <select name="priority" class="form-select" required>

        @foreach(['low', 'medium', 'high', 'urgent'] as $priority)

            <option value="{{ $priority }}"
                @selected(old('priority', $task->priority ?? 'medium') == $priority)>
                {{ ucfirst($priority) }}
            </option>

        @endforeach

    </select>

</div>

<div class="mb-3">

    <label class="form-label">Status</label>

    <select name="status" class="form-select" required>

        @foreach(['pending', 'in_progress', 'completed'] as $status)

            <option value="{{ $status }}"
                @selected(old('status', $task->status ?? 'pending') == $status)>
                {{ ucfirst(str_replace('_', ' ', $status)) }}
            </option>

        @endforeach

    </select>

</div>

<div class="mb-3">

    <label class="form-label">Due Date</label>

    <input type="date"
           name="due_date"
           class="form-control"
           value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">

</div>