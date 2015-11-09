{{-- auth.register --}}

@extends ('layout.app')

<div class="panel">

    <div class="panel-heading">
        <h4 class="panel-title">Regístrate</h4>
        <p>Completa el formulario o accede con tu cuenta de <a href="{{ route('auth.login.social', 'github') }}"><span class="alert round label"><i class="fa fa-github"></i> GitHub</span></a></p>
    </div>

    <hr/>

    <div class="panel-body">

        {!! Form::open(['route' => 'auth.register.store', 'id' => 'register-form']) !!}

        {{-- Username --}}
        <label>Usuario *
            {!! Form::text('username', null, ['tabindex' => 1]) !!}
        </label>

        {{-- E-Mail --}}
        <label>E-Mail *
            {!! Form::email('email', null, ['tabindex' => 2]) !!}
        </label>

        {{-- Password --}}
        <label>Contraseña *
            {!! Form::password('password', ['tabindex' => 3]) !!}
        </label>

        {{-- Password confirmation --}}
        <label>Confirmar contraseña *
            {!! Form::password('password_confirmation', ['tabindex' => 4]) !!}
        </label>

        {{-- Send / Cancel --}}
        <input type="submit" class="button success" value="Regístrate" tabindex="5"/>
        <a href="{{ route('auth.register') }}" class="button secondary">Cancelar</a>

        {!! Form::close() !!}

    </div>

</div>


