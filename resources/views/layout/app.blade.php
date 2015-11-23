<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Head Content --}}
    @include('layout.common.head')
</head>
<body>
{{-- Navigation --}}
{{--@include('layout.common.navigation')--}}

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