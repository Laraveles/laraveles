{{-- account.recruiter --}}

@extends ('account.base')

@section ('tab')

    {!! Form::model(auth()->user()->recruiter, ['route' => 'account.recruiter.store', 'file' => true]) !!}

    {{-- Company name --}}
    <div class="form-group">
        <label>Empresa *</label>
        {!! Form::text('company', null, [
            'class' => 'form-control',
            'placeholder' => 'Nombre de la empresa'
        ]) !!}
    </div>

    {{-- Website --}}
    <div class="form-group">
        <label>Sitio web</label>
        {!! Form::text('website', null, [
            'class' => 'form-control',
            'placeholder' => 'empresa.com'
        ]) !!}
    </div>

    {{-- Avatar --}}
    <div class="form-group">
        <label>Avatar</label>
        {!! Form::file('avatar', [
            'class' => 'form-control',
            'placeholder' => 'Logo de la empresa'
        ]) !!}
    </div>

    {{-- Save --}}
    <button type="submit" class="btn btn-success" tabindex="3">Guardar</button>

    {!! Form::close() !!}

@endsection