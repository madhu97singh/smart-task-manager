<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\OverdueTaskNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOverdueTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $taskId
    ) {}

    public function handle(): void
    {
        $task = Task::with('user')->find($this->taskId);

        if (!$task || $task->status === 'completed') {
            return;
        }

        $task->user->notify(
            new OverdueTaskNotification($task)
        );

        $task->update([
            'overdue_notified_at' => now(),
        ]);
    }
}