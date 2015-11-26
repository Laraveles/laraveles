{{-- account.location --}}

@extends ('account.base')

@section ('tab-title', 'Ubicación')

@section ('tab')

    {!! Form::open() !!}

    {{-- Show Location --}}
    <div class="form-group">
        <div class="checkbox">
            <label>{!! Form::checkbox('remote', true, false) !!} Mostrar mi ubicación</label>
        </div>
        <p class="help-block">Marcar esta opción si se desea mostrar la ubicación del usuario.</p>
    </div>

    {{-- City --}}
    <div class="form-group">
        <label>Ciudad</label>
        {!! Form::text('city', null, [
            'class' => 'form-control',
            'placeholder' => 'Madrid, Lima, ...'
        ]) !!}
    </div>

    {{-- Country --}}
    <div class="form-group">
        <label>País</label>
        {!! Form::text('country', null, [
            'class' => 'form-control',
            'placeholder' => 'Chile, Argentina, ...'
        ]) !!}
    </div>

    {!! Form::close() !!}

@endsection