{{-- auth.resend-confirmation --}}
@extends ('layout.app')

@section ('content')

    <h3>Solicitud de e-mail de confirmación</h3>

    {!! Form::open(['route' => 'auth.activate.resend']) !!}

    <div class="form-group">
        <label>E-Mail</label>
        {!! Form::email('email', null, [
            'placeholder' => 'E-mail registrado',
            'class' => 'form-control'
        ]) !!}
    </div>

    <button type="submit" class="btn btn-success">Enviar</button>

    {!! Form::close() !!}

@endsection