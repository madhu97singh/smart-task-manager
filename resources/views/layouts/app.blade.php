<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Smart Task Manager')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">
    
    <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f5f6fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #212529;
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            padding: 12px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background: #343a40;
            color: white;
        }

        .content {
            padding: 25px;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-2 sidebar p-3">

            <h4 class="text-white mb-4">Smart Tasks</h4>

            <a href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="{{ route('tasks.index') }}">
                <i class="bi bi-list-task"></i> My Tasks
            </a>

            <a href="{{ route('tasks.create') }}">
                <i class="bi bi-plus-circle"></i> Create Task
            </a>

            <a href="{{ route('notifications.index') }}">
                <i class="bi bi-bell"></i> Notifications
            </a>

            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button class="btn btn-outline-light w-100">
                    Logout
                </button>
            </form>

        </aside>

        <main class="col-md-10 content">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>@yield('heading')</h4>

                <div class="d-flex align-items-center gap-3">

                    {{-- Notification Bell --}}
                    <div class="dropdown">
                        <button class="btn btn-light position-relative"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                            <i class="bi bi-bell fs-5"></i>

                            <span id="notification-count"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end p-2"
                            style="width: 320px;"
                            id="notification-list">

                            <h6 class="dropdown-header">Notifications</h6>

                            <p class="text-muted small p-2 mb-0">
                                Loading notifications...
                            </p>

                        </div>
                    </div>

                    <span>
                        Welcome, {{ auth()->user()->name }}
                    </span>

                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@auth
<script>
document.addEventListener('DOMContentLoaded', function () {

    let knownNotifications = new Set();

    async function loadNotifications() {
        try {
            const response = await fetch(
                "{{ route('notifications.latest') }}"
            );

            if (!response.ok) return;

            const result = await response.json();

            document.getElementById('notification-count').textContent =
                result.unread_count;

            const list = document.getElementById('notification-list');

            list.innerHTML = '<h6 class="dropdown-header">Notifications</h6>';

            if (result.notifications.length === 0) {
                list.innerHTML +=
                    '<p class="text-muted small p-2">No unread notifications</p>';
                return;
            }

            result.notifications.forEach(notification => {

                if (!knownNotifications.has(notification.id)) {

                    if (knownNotifications.size > 0) {
                        showNotificationPopup(notification.data.message);
                    }

                    knownNotifications.add(notification.id);
                }

                const item = document.createElement('button');

                item.type = 'button';
                item.className = 'dropdown-item text-wrap small';

                item.textContent =
                    (notification.data.message || 'New notification') +
                    ' - ' + notification.created_at;

                item.addEventListener('click', function () {
                    markAsRead(notification.id);
                });

                list.appendChild(item);
            });

        } catch (error) {
            console.error('Notification error:', error);
        }
    }

    async function markAsRead(id) {

        const response = await fetch(
            `/notifications/${id}/read`,
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }
        );

        if (response.ok) {
            await loadNotifications();
        }
    }

    function showNotificationPopup(message) {

        const toast = document.createElement('div');

        toast.className =
            'alert alert-success position-fixed top-0 end-0 m-3 shadow';

        toast.style.zIndex = '9999';
        toast.textContent = message || 'You have a new notification';

        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 4000);
    }

    loadNotifications();

    setInterval(loadNotifications, 10000);

});
</script>
@endauth

@stack('scripts')

</body>
</html>