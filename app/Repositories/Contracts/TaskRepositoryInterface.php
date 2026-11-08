<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function getForUser(
        int $userId,
        array $filters = []
    ): LengthAwarePaginator;

    public function create(int $userId, array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): bool;

    public function getStatistics(int $userId): array;
}