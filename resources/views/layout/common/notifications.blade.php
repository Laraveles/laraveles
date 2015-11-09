{{-- layout.common.notifications --}}

@if ($errors->count())
    <section class="alert">
        <div class="alert alert-danger">
            {!! $errors->all('<div>:message</div>') !!}
        </div>
    </section>
@endif
