<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.2.1/dist/alpine.js" defer></script>
    </head>
    <body>
        <section class="hero is-info">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">Registration</h1>
                </div>
            </div>
        </section>
        <div class="container py-4 px-4">
            @if (session('status'))
                <div class="notification is-danger is-light">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field">
                    <label for="name" class="label">Name</label>
                    <div class="control">
                        <input type="text" class="input" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <div class="field">
                    <label for="email" class="label">Email</label>
                    <div class="control">
                        <input type="email" class="input" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="field">
                    <label for="password" class="label">Password</label>
                    <div class="control">
                        <input type="password" class="input" name="password" required>
                    </div>
                </div>

                <div class="field">
                    <label for="password_confirmation" class="label">Confirm Password</label>
                    <div class="control">
                        <input type="password" class="input" name="password_confirmation" required>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            <div style="display: inline-block;" class="pb-2">{{ __('Remember me') }}</div>
                        </label>
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-info">{{ __('Register') }}</button>
                    </div>
                    <div class="control pt-2">
                        <a href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
