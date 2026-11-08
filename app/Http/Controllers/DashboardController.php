<?php

namespace App\Http\Controllers;

use App\Services\TaskService;

class DashboardController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index()
    {
        $statistics = $this->taskService
            ->getDashboardStatistics(auth()->id());

        return view('dashboard', compact('statistics'));
    }
}