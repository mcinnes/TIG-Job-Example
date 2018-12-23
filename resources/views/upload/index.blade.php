@extends('layout.mainlayout')
@section('content')
    <div class="container">
        <form action="upload/saved" enctype="multipart/form-data" method="POST">
            <div class="form-group">
                <label for="contact">Phone Number</label>
                <input type="text" class="form-control" name="contact" id="contact" aria-describedby="phoneHelp" placeholder="Enter phone number">
                <small id="phoneHelp" class="form-text text-muted">Phone number must be in the format +61412345678</small>
            </div>
            <div class="form-group">
                <label for="text">Text</label>
                <input type="text" class="form-control" name="text" id="text" aria-describedby="textHelp" placeholder="Enter text to synthesize">
            </div>
            <button class="btn btn-success">Go</button>
        </form>
    </div>
@endsection