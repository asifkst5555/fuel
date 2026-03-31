<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Fuel Monitor') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            html {
                font-size: 80%;
            }
        </style>
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">Fuel Monitor</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </nav>

        @isset($header)
            <header class="bg-white border-bottom">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="container py-4">
            {{ $slot }}
        </main>
    </body>
    </html>
