@extends('layouts.app')

@section('title', 'Edit Task')

@section('heading', 'Edit Task')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form action="{{ route('tasks.update', $task) }}" method="POST">

            @csrf
            @method('PUT')

            @include('tasks.partials.form')

            <button class="btn btn-primary">
                Update Task
            </button>

            <a href="{{ route('tasks.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>

</div>

@endsection