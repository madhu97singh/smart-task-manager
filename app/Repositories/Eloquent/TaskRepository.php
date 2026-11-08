<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function getForUser(
        int $userId,
        array $filters = []
    ): LengthAwarePaginator {

        return Task::query()
            ->with('category')
            ->where('user_id', $userId)

            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })

            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })

            ->when($filters['priority'] ?? null, function ($query, $priority) {
                $query->where('priority', $priority);
            })

            ->when($filters['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function create(int $userId, array $data): Task
    {
        return Task::create(array_merge($data, [
            'user_id' => $userId,
        ]));
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->refresh();
    }

    public function delete(Task $task): bool
    {
        return (bool) $task->delete();
    }

    public function getStatistics(int $userId): array
    {
        $query = Task::where('user_id', $userId);

        return [
            'total' => (clone $query)->count(),

            'pending' => (clone $query)
                ->where('status', 'pending')->count(),

            'in_progress' => (clone $query)
                ->where('status', 'in_progress')->count(),

            'completed' => (clone $query)
                ->where('status', 'completed')->count(),

            'overdue' => (clone $query)
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', now()->toDateString())
                ->whereNotIn('status', ['completed'])
                ->count(),
        ];
    }
}