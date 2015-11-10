{{-- auth.auth --}}

@extends ('layout.app')

@section ('content')

    <div class="panel">

        <div class="panel-heading">
            <h4 class="panel-title">Identifícate</h4>
            <p>Completa el formulario o accede con tu cuenta de <a href="{{ route('auth.social', 'github') }}"><span class="alert round label"><i class="fa fa-github"></i> GitHub</span></a></p>
        </div>

        <hr/>

        <div class="panel-body">

            {!! Form::open(['route' => 'auth.authenticate', 'id' => 'login-form']) !!}

            {{-- Username --}}
            <div class="form-group">
                <label>Usuario</label>
                {!! Form::text('username', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Nombre de usuario / e-mail',
                    'tabindex' => 1
                ]) !!}
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label>Contraseña</label>
                {!! Form::password('password', [
                    'class' => 'form-control',
                    'placeholder' => '········',
                    'tabindex' => 2
                ]) !!}
            </div>

            {{-- Access --}}
            <button type="submit" class="btn btn-success" tabindex="3">Acceder</button>

            {!! Form::close() !!}

        </div>

    </div>


@endsection