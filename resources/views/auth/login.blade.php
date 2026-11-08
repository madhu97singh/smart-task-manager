<!DOCTYPE html>
<html>
<head>
    <title>Login - Smart Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h3 class="text-center mb-4">Smart Task Manager</h3>

                    <form action="{{ route('login.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email"
                                   class="form-control"
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>
                                <input type="checkbox" name="remember" value="1">
                                Remember me
                            </label>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <button class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <p class="text-center mt-3">
                        Don't have an account?
                        <a href="{{ route('register') }}">Register</a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>