@extends('layouts.app')

@section('content')
    <div class="container chat-container">
        <div class="conversation-list px-2">
            <h5 class="my-1 px-1">Recent chat</h5>
            {{-- @dd($friends->toArray()) --}}
            @if (isset($friends) && count($friends) > 0)
                @foreach ($friends as $friend)
                    <div class="conversation-item" data-id="{{ $friend->id }}">
                        <div>
                            <div class="all-online-user" id="online-user-{{ $friend->id }}"></div>
                            <img src="{{ isset($friend->image) ? asset('storage') . '/' . $friend->image : asset('images/person.jpg') }}"
                                class="rounded-circle" height="30px" width="30px" alt="">
                            <strong>{{ ucfirst($friend->name) }} - &nbsp;&nbsp;&nbsp;<span
                                    class="user-status"></span></strong>
                            <span id="typing-{{ $friend->id }}" class="timestamp d-none">Typing...</span>
                        </div>
                    </div>
                    {{-- <div>
                                <span class="quote">Muhammad Umar - trz</span> <span class="timestamp">2:35 PM</span>
                            </div> --}}
                @endforeach
            @else
                <div class=" text-center my-5">
                    <p>You havn't any friend!</p> <div>
                        <button class="btn btn-sm btn-primary" id="add_frnd_btn">Add friend</button>
                    </div>
                </div>
            @endif

        </div>

        <div class="chat-area position-relative">
            {{-- <div class="rcvr-data text-center d-none" style=" position: absolute; left: 47%;  top: -39px; ">
            <img class="rounded-circle" src="{{ asset('images/person.jpg') }}" alt="" height="70px" width="70px">
            <p>usman</p>
        </div> --}}
            <div class="starter-text">
                <h3 class="h2">Hello , {{ ucfirst(auth()->user()->name) }}☺️</h3>
                <p>Let's start new chat.... </p>
            </div>
            <div id="chat-area-append">

            </div>
            <div class="img-container d-flex py-2">
                {{-- <div class="img-div mx-3 position-relative">
                <i class="bi bi-x"></i>
                <img src="{{ asset('images/Capture.PNG') }}" alt="img" width="90" height="90">
            </div> --}}
            </div>

            {{-- for audio  --}}
            <div id="audio-recording-ui" class="audio-recording-ui d-none position-absolute"
                style="bottom: 70px; left: 26px;">
                <div class="recording-info d-flex align-items-center">
                    <i class="bi bi-mic h4 me-2 text-danger"></i>
                    <span id="recording_span"></span>
                </div>
                <button id="stop-recording-btn" class="btn btn-danger btn-sm ms-3">Cancel</button>
            </div>

            <div class="message-input d-none position-relative">

                <p class="d-none" style="position: absolute; top: -21px;">Typing...</p>
                <input type="text" class="message-value" id="message-input" placeholder="Type a message..." />

                {{-- recording --}}
                <label class="start_recording" id="recording-btn" style="padding: 10px 0px 0px 4px; cursor: pointer;">
                    <i class="bi bi-mic-fill h5"></i>
                </label>

                {{-- for media  --}}
                <input hidden type="file" id="chat-image" multiple>
                <label for="chat-image" class="" style="padding: 10px 9px 0px 11px; cursor: pointer;">
                    <i class="bi bi-camera h5"></i>
                </label>

                {{-- send butn  --}}
                <label class="send-message-btn" id="send-btn" style="padding: 10px 9px 0px 0px; cursor: pointer;">
                    <i class="bi bi-send h5"></i>
                </label>
            </div>
        </div>
    </div>
@endsection
