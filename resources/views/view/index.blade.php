@extends('layout.mainlayout')
@section('content')
    <div id="contatiner">
        <h1>Result</h1>
        <h3>Completed at: {{ $updated_at }} </h3>
        <audio controls>
            <source src="https://s3.amazonaws.com/tig-code-test/{{$s3_id}}" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>
        <a href="https://s3.amazonaws.com/tig-code-test/{{$s3_id}}">Download MP3 File</a>
    </div>
@endsection