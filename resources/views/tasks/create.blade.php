@extends('layouts.app')

@section('title', 'Create Task')

@section('heading', 'Create New Task')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form action="{{ route('tasks.store') }}" method="POST">

            @csrf

            @include('tasks.partials.form')

            <button class="btn btn-primary">
                Save Task
            </button>

            <a href="{{ route('tasks.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>

</div>

@endsection