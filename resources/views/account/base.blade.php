{{-- account.base --}}

@extends ('layout.app')

@section ('content')

    <div class="col-md-offset-1 col-md-3">
        <ul class="list-group">
            <li class="list-group-item"><a href="{{ route('account.recruiter') }}">Recruiter</a></li>
        </ul>
    </div>

    <div class="col-md-offset-1 col-md-6">
        @yield ('tab')
    </div>

@endsection
