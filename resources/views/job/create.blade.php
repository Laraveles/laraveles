{{-- job.create --}}

@extends ('layout.app')

@section ('content')

    @if (! isset($job))
        {!! Form::open(['route' => 'job.store']) !!}
    @else
        {!! Form::model($job, ['route' => ['job.update', $job->id], 'method' => 'PUT']) !!}
    @endif

    <fieldset>
        <legend>Descripción del puesto</legend>
        {{-- Title --}}
        <div class="form-group">
            <label>Título</label>
            {!! Form::text('title', null, [
                'class' => 'form-control',
                'placeholder' => 'Programador Laravel Junior, Programador Backend, ...'
            ]) !!}
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label>Descripción</label>
            {!! Form::textarea('description', null, [
                'class' => 'form-control',
                'placeholder' => 'Describe detalladamente la oferta'
            ]) !!}
        </div>

        {{-- How to apply --}}
        <div class="form-group">
            <label>Inscripción</label>
            {!! Form::textarea('apply', null, [
                'class' => 'form-control',
                'placeholder' => 'Explica el procedimiento de inscripción'
            ]) !!}
        </div>

        {{-- Type --}}
        <div class="form-group">
            <label>Tipo</label>
            {!! Form::select('type', $types, [
                'class' => 'form-control'
            ]) !!}
        </div>
    </fieldset>


    <fieldset>
        <legend>Ubicación</legend>

        {{-- City --}}
        <div class="form-group">
            <label>Ciudad</label>
            {!! Form::text('city', null, [
                'class' => 'form-control',
                'placeholder' => 'Madrid, Buenos Aires, ...'
            ]) !!}
        </div>

        {{-- Country --}}
        <div class="form-group">
            <label>País</label>
            {!! Form::text('country', null, [
                'class' => 'form-control',
                'placeholder' => 'Chile, USA, ...'
            ]) !!}
        </div>

        {{-- Remote --}}
        <div class="form-group">
            <label>Remoto</label>
            {!! Form::checkbox('remote', true, [
                'class' => 'form-control'
            ]) !!}
        </div>
    </fieldset>

    {{-- Save --}}
    @include ('_forms.buttons.save')

    {!! Form::close() !!}

@endsection