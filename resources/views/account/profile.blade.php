{{-- account.profile --}}

@extends ('account.base')

@section ('tab-title', 'Perfil')

@section ('tab')

    {!! Form::open(['route' => 'account.profile.store']) !!}

    <fieldset>
        <legend>Usuario</legend>

        {{-- Username --}}
        <div class="form-group">
            <label>Usuario</label>
            {!! Form::text('username', null, [
                'class' => 'form-control',
                'placeholder' => 'Laraveles, ...'
            ]) !!}
            <p class="help-block">Sin espacios ni carácteres especiales <mark>A-Z a-z 0-9 _-</mark></p>
        </div>

        {{-- E-mail --}}
        <div class="form-group">
            <label>E-mail</label>
            {!! Form::email('email', null, [
                'class' => 'form-control',
                'placeholder' => 'usuario@dominio.com'
            ]) !!}
            <p class="help-block">Habrá que confirmar la nueva dirección de correo si se modifica.</p>
        </div>

        @if (isset($user->avatar))
            <img src="{{ url("img/profiles/{$user->avatar}") }}" width="100px">
        @endif

        {{-- Avatar --}}
        <div class="form-group">
            <label>Avatar</label>
            {!! Form::file('avatar', [
                'class' => 'form-control'
            ]) !!}
        </div>
    </fieldset>

    <fieldset>
        <legend>Cambiar contraseña</legend>

        <p>Completa este apartado para cambiar tu contraseña.</p>

        {{-- Password --}}
        <div class="form-group">
            <label>Contraseña</label>
            {!! Form::password('password', [
                'class' => 'form-control'
            ]) !!}
        </div>

        {{-- Password Confirmation --}}
        <div class="form-group">
            <label>Confirmar contraseña</label>
            {!! Form::password('password_confirmation', [
                'class' => 'form-control'
            ]) !!}
        </div>
    </fieldset>

    @include ('_forms.buttons.save')

    {!! Form::close() !!}

@endsection