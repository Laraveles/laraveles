<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Head Content --}}
    @include('layouts.common.head')
</head>
<body>
{{-- Navigation --}}
@include('layouts.common.navigation')

{{-- Notifications --}}
@include('layouts.common.notifications')

{{-- Main Content --}}
@yield('content')

{{-- Footer --}}
@include('common.footer')

{{-- JavaScript Application --}}
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>