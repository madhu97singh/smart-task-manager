<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskActivityCreated
{
    use Dispatchable, SerializesModels;

    public Task $task;
    public int $userId;
    public string $action;
    public array $oldValues;
    public array $newValues;

    public function __construct(
        Task $task,
        int $userId,
        string $action,
        array $oldValues = [],
        array $newValues = []
    ) {
        $this->task = $task;
        $this->userId = $userId;
        $this->action = $action;
        $this->oldValues = $oldValues;
        $this->newValues = $newValues;
    }
}