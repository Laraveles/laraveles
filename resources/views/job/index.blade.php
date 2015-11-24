{{-- job.index --}}

@extends ('layout.app')

@section ('content')

    <div class="row">
        <div class="col-md-8">
            @foreach ($jobs as $job)

                <div class="media">
                    <a href="{{ route('job.show', $job->id) }}">

                        <div class="media-left">
                            @if ($job->recruiter->avatar)
                                <img class="media-object" width="64px" src="{{ asset('img/recruiters/' . $job->recruiter->avatar) }}" alt="{{ $job->recruiter->company }}">
                            @else
                                <img class="media-object" width="64px" src="{{ asset('img/company.png') }}" alt="{{ $job->recruiter->company }}">
                            @endif
                        </div>
                        <div class="media-body">
                            {{-- Job title --}}
                            <h4 class="media-heading">{{ $job->title }}</h4>

                            {{-- Waiting for approval --}}
                            @unless ($job->listing)
                                <span class="label label-danger pull-right">Pendiente de moderación</span>
                            @endunless

                            {{-- New flag --}}
                            @if ($job->isNew() && $job->listing)
                                <span class="pull-right label label-warning">¡Nuevo!</span>
                            @endif

                            {{-- Recruiter name --}}
                            <div>{{ $job->recruiter->company }}</div>

                            {{-- Job location --}}
                            <div>{{ $job->location }}</div>

                        </div>
                    </a>
                </div>

            @endforeach

        </div>
        <div class="col-md-4">
            <p>En <a href="{{ route('home') }}">Laraveles.com</a> queremos ayudar a establecer contacto entre demandantes y ofertantes de empleo relacionado con Laravel.</p>
            <p>¡Publica ahora tu oferta!</p>
            <a href="{{ route('job.create') }}" class="btn btn-primary btn-block">Nuevo Empleo</a>
        </div>
    </div>



@endsection