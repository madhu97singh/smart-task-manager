@extends('layouts.app')

@section('title', 'Dashboard')

@section('heading', 'Dashboard')

@section('content')

<div class="row g-3">

    @php
        $cards = [
            ['Total Tasks', 'total', 'primary'],
            ['Pending', 'pending', 'warning'],
            ['In Progress', 'in_progress', 'info'],
            ['Completed', 'completed', 'success'],
            ['Overdue', 'overdue', 'danger'],
        ];
    @endphp

    @foreach($cards as $card)

        <div class="col-md-4 col-lg">
            <div class="card stat-card shadow-sm">
                <div class="card-body">

                    <h6 class="text-muted">
                        {{ $card[0] }}
                    </h6>

                    <h2 class="text-{{ $card[2] }}">
                        {{ $statistics[$card[1]] }}
                    </h2>

                </div>
            </div>
        </div>

    @endforeach

</div>

<div class="mt-4">
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Create New Task
    </a>

    <a href="{{ route('tasks.index') }}" class="btn btn-dark">
        View All Tasks
    </a>
</div>

@endsection