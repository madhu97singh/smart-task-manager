<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Events\TaskStatusBroadcast;
use App\Events\TaskActivityCreated;
use App\Notifications\TaskStatusNotification;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function getTasks(
        int $userId,
        array $filters = []
    ): LengthAwarePaginator {

        return $this->taskRepository->getForUser($userId, $filters);
    }

    public function createTask(int $userId, array $data): Task
    {
        $task = $this->taskRepository->create($userId, $data);

        event(new TaskActivityCreated(
            $task,
            $userId,
            'created',
            [],
            $data
        ));

        return $task;
    }

    public function updateTask(Task $task, array $data): Task
    {
        $oldStatus = $task->status;
        $oldValues = $task->only(array_keys($data));

        if (($data['status'] ?? $oldStatus) === 'completed') {
            $data['completed_at'] = $task->completed_at ?? now();
        } else {
            $data['completed_at'] = null;
        }

        $updatedTask = $this->taskRepository->update($task, $data);

        $action = $updatedTask->status === 'completed'
            ? 'completed'
            : 'updated';

        event(new TaskActivityCreated(
            $updatedTask,
            $updatedTask->user_id,
            $action,
            $oldValues,
            $updatedTask->only(array_keys($data))
        ));

        // Send notification only when status changes to completed
        if ($oldStatus !== 'completed' && $updatedTask->status === 'completed') {
            $user = $updatedTask->user;

            if ($user) {
                $user->notify(
                    new TaskStatusNotification($updatedTask)
                );
            }
        }

        // Broadcast only when status actually changes
        if ($oldStatus !== $updatedTask->status) {
            event(new TaskStatusBroadcast($updatedTask));
        }

        return $updatedTask;
    }

    public function updateStatus(Task $task, string $status): Task
    {
        return $this->updateTask($task, [
            'status' => $status,
        ]);
    }

    public function deleteTask(Task $task): bool
    {
        $userId = $task->user_id;

        $taskSnapshot = clone $task;

        $deleted = $this->taskRepository->delete($task);

        if ($deleted) {
            event(new TaskActivityCreated(
                $taskSnapshot,
                $userId,
                'deleted'
            ));
        }

        return $deleted;
    }

    public function getDashboardStatistics(int $userId): array
    {
        return $this->taskRepository->getStatistics($userId);
    }
}