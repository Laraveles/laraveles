{{-- layout.common.notifications --}}

@include ('flash::message')

@if ($errors->count())
    <section class="alert">
        <div class="alert alert-danger">
            @foreach ($errors->all('<div>:message</div>') as $error)
                {!! $error !!}
            @endforeach
        </div>
    </section>
@endif
