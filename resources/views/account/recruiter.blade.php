{{-- account.recruiter --}}

@extends ('account.base')

@section ('tab')

    {!! Form::model($recruiter, ['route' => 'account.recruiter.store', 'files' => true]) !!}

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

    @if ($recruiter && $recruiter->avatar)
        <img src="{{ url("img/recruiters/{$recruiter->avatar}") }}" width="100px">
    @endif

    {{-- Avatar --}}
    <div class="form-group">
        <label>Avatar</label>
        {!! Form::file('avatar', [
            'class' => 'form-control',
            'placeholder' => 'Logo de la empresa'
        ]) !!}
    </div>

    {{-- Save --}}
    @include ('_forms.buttons.save')

    {!! Form::close() !!}

@endsection