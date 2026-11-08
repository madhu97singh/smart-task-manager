<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreTaskActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskActivityCreated $event): void
    {
        TaskActivity::create([
            'task_id' => $event->task->id,
            'user_id' => $event->userId,
            'action' => $event->action,
            'description' => $this->getDescription($event->action),
            'old_values' => $event->oldValues,
            'new_values' => $event->newValues,
        ]);
    }

    private function getDescription(string $action): string
    {
        return match ($action) {
            'created' => 'Created a new task',
            'updated' => 'Updated task details',
            'completed' => 'Completed a task',
            'deleted' => 'Deleted a task',
            default => 'Performed an action on a task',
        };
    }
}
