<?php

namespace App\Console\Commands;

use App\Jobs\SendOverdueTaskNotification;
use App\Models\Task;
use Illuminate\Console\Command;

class CheckOverdueTasks extends Command
{
    protected $signature = 'tasks:check-overdue';

    protected $description = 'Find overdue tasks and dispatch notification jobs';

    public function handle(): int
    {
        $count = 0;

        Task::query()
            ->whereDate('due_date', '<', today())
            ->whereNotIn('status', ['completed'])
            ->whereNull('overdue_notified_at')
            ->select('id')
            ->chunkById(100, function ($tasks) use (&$count) {

                foreach ($tasks as $task) {
                    SendOverdueTaskNotification::dispatch($task->id);
                    $count++;
                }

            });

        $this->info("Dispatched {$count} overdue task notifications.");

        return self::SUCCESS;
    }
}