{{-- docs.docs --}}

@extends ('layout.app')

@section ('content')

{!! Form::select('version', $fileVersions) !!}


{!! $index !!}

{!! $content !!}
@endsection