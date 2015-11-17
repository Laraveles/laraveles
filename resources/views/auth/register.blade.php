{{-- auth.register --}}

@extends ('layout.app')

@section ('content')

<div class="panel">

    <div class="panel-heading">
        <h4 class="panel-title">Regístrate</h4>
        <p>Completa el formulario o accede con tu cuenta de <a href="{{ route('auth.social', 'github') }}"><span class="alert round label"><i class="fa fa-github"></i> GitHub</span></a></p>
    </div>

    <hr/>

    <div class="panel-body">

        {!! Form::open(['route' => 'auth.register.store', 'id' => 'register-form']) !!}

        {{-- Username --}}
        <div class="form-group">
            <label>Usuario *</label>
            {!! Form::text('username', null, [
                'class' => 'form-control',
                'tabindex' => 1
            ]) !!}
        </div>

        {{-- E-Mail --}}
        <div class="form-group">
            <label>E-Mail *</label>
            {!! Form::email('email', null, [
                'class' => 'form-control',
                'tabindex' => 2
            ]) !!}
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label>Contraseña *</label>
            {!! Form::password('password', [
                'class' => 'form-control',
                'tabindex' => 3
            ]) !!}
        </div>

        {{-- Password confirmation --}}
        <div class="form-group">
            <label>Confirmar contraseña *</label>
            {!! Form::password('password_confirmation', [
                'class' => 'form-control',
                'tabindex' => 4
            ]) !!}
        </div>

        {{-- Send / Cancel --}}
        <button type="submit" class="btn btn-success" tabindex="5">Regístrate</button>
        <a href="{{ route('auth.register.index') }}" class="button secondary">Cancelar</a>

        {!! Form::close() !!}

    </div>

</div>

@endsection
