<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Head Content --}}
    @include('layout.common.head')
</head>
<body>
{{-- Navigation --}}
@include('layout.common.navigation')

@if (auth()->check())
    <p>Logueado como <strong>{{ $user->username }}</strong> <a href="{{ route('auth.logout') }}">Salir</a></p>
@else
    <a href="{{ route('auth.login') }}">Acceder</a>
@endif

{{-- Notifications --}}
@include('layout.common.notifications')

{{-- Main Content --}}
<div class="container">
    @yield('content')
</div>

{{-- Footer --}}
@include('layout.common.footer')

{{-- JavaScript Application --}}
{{--<script src="{{ elixir('js/app.js') }}"></script>--}}
</body>
</html>