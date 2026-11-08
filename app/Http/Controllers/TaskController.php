<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'status',
            'priority',
            'category_id',
        ]);

        $tasks = $this->taskService->getTasks(
            auth()->id(),
            $filters
        );

        $categories = Category::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('tasks.create', compact('categories'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask(
            auth()->id(),
            $request->validated()
        );

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $categories = Category::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $this->taskService->updateTask(
            $task,
            $request->validated()
        );

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,in_progress,completed'
            ],
        ]);

        $task = $this->taskService->updateStatus(
            $task,
            $validated['status']
        );

        return response()->json([
            'success' => true,
            'message' => 'Task status updated.',
            'task' => $task,
        ]);
    }
}