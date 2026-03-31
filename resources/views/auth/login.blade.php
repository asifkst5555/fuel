<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin Login | Hathazari Fuel Monitor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                min-height: 100vh;
                background:
                    radial-gradient(circle at top, rgba(36, 72, 216, 0.18), transparent 26%),
                    linear-gradient(135deg, #112648 0%, #16345f 48%, #eef4ff 100%);
                font-family: "Inter", sans-serif;
            }

            .login-card {
                border: 0;
                border-radius: 1.75rem;
                box-shadow: 0 26px 70px rgba(12, 24, 47, 0.24);
            }
        </style>
    </head>
    <body class="d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="login-card card">
                        <div class="card-body p-4 p-lg-5">
                            <div class="text-center mb-4">
                                <a href="{{ route('home') }}" class="text-decoration-none text-primary fw-bold">Hathazari Fuel Monitor</a>
                                <h1 class="h3 mt-3 mb-2">Admin Login</h1>
                                <p class="text-secondary mb-0">Sign in to manage stations, update fuel availability, and control the live public dashboard.</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">
                                        Remember me
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 rounded-4 py-3 fw-semibold">Login to Dashboard</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
