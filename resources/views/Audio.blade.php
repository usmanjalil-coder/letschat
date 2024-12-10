@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center">

    

    <div class="row">
        <div class="col">
            <h2 class="mb-4">Audio Recorder</h2>

    <!-- Recording Controls -->
    <div class="controls mb-3">
        <button id="recordBtn" class="btn btn-danger btn-lg">Record</button>
        <button id="stopBtn" class="btn btn-secondary btn-lg ml-3" disabled>Stop</button>
    </div>

    <!-- Audio Waveform Display -->
    <div id="waveform" class="waveform mt-4"></div>

    <!-- Audio Playback Control -->
    <div class="playback mt-4 d-none">
        <audio id="audioPlayback" controls></audio>
    </div>
        </div>
        <div class="col">
            <h2>All Recorded Audios</h2>
                <div class="list-group">
                    @foreach($recordings as $recording)
                        <div class="list-group-item">
                            <p>{{ $recording->file_name }}</p>
                            <audio controls>
                                <source src="{{ asset('storage/' . $recording->file_path) }}" type="audio/wav">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    @endforeach
                </div>
        </div>
        
    </div>
</div>


{{-- <div class="container mt-5">
    <h2>All Recorded Audios</h2>
    <div class="list-group">
        @foreach($recordings as $recording)
            <div class="list-group-item">
                <p>{{ $recording->file_name }}</p>
                <audio controls>
                    <source src="{{ asset('storage/' . $recording->file_path) }}" type="audio/wav">
                    Your browser does not support the audio element.
                </audio>
            </div>
        @endforeach
    </div>
</div> --}}


@endsection

