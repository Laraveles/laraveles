{{-- account.base --}}

@extends ('layout.app')

@section ('content')

    <div class="col-md-offset-1 col-md-3">
        <ul class="list-group">
            <li class="list-group-item"><a href="{{ route('account.profile.index') }}">Perfil</a></li>
            <li class="list-group-item"><a href="{{ route('account.location.index') }}">Ubicación</a></li>
            <li class="list-group-item"><a href="{{ route('account.recruiter.index') }}">Empleo</a></li>
        </ul>
    </div>

    <div class="col-md-offset-1 col-md-6">
        <h3>@yield ('tab-title')</h3>

        @yield ('tab')
    </div>

@endsection
