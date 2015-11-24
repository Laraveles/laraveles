{{-- job.index --}}

@extends ('layout.app')

@section ('content')

    <ul>
        @foreach ($jobs as $job)
            <li class="list-group-item">
                <a href="{{ route('job.show', $job->id) }}">
                    {{ $job->title }}
                    @unless ($job->listing)
                        <span class="label label-danger pull-right">Pendiente de moderación</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>

@endsection