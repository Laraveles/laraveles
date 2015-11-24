{{-- job.show --}}

@extends ('layout.app')

@section ('content')

    @can ('update', $job)
    <a href="{{ route('job.edit', $job->id) }}" class="btn btn-primary">Modificar</a>
    <a href="{{ route('job.destroy', $job->id) }}"
       data-method="delete"
       data-confirm="¿Seguro que quieres eliminar este trabajo?"
       class="btn btn-danger">
        Eliminar
    </a>
    @endcan
    @can ('moderate', $job)
        @unless ($job->listing)
            <a href="{{ route('job.approve', $job->id) }}" class="btn btn-success">Aprobar</a>
        @endunless
    @endif

    <h2>{{ $job->title }}</h2>

    <div class="row">
        <div class="col-md-8">

            <h3>Descripción de la oferta</h3>
            {{ $job->description }}

            <h3>¿Cómo inscribirse?</h3>
            {{ $job->apply }}

        </div>

        <div class="col-md-4">
            <h4>Empresa</h4>
            {{ $recruiter->company }}
        </div>
    </div>

@endsection