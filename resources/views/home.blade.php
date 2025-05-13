@extends('layouts.app')

@section('content')
    <div class="container chat-container">
        <div class="conversation-list px-2">
            <h5 class="my-1 px-1">Recent chat</h5>
            {{-- @dd($friends->toArray()) --}}
            @if (isset($friends) && count($friends) > 0)
            @foreach ($friends as $friend)
                <div class="conversation-item position-relative py-2 px-3 border-bottom" id="conservation-{{ $friend->id }}" data-id="{{ $friend->id }}">
                    <div class="d-flex align-items-start">
                        <div class="position-relative me-2">
                            <div class="all-online-user position-absolute top-0 start-100 translate-middle" id="online-user-{{ $friend->id }}"></div>
                            <img src="{{ isset($friend->image) ? asset('storage/' . $friend->image) : asset('images/person.jpg') }}"
                                class="rounded-circle" height="45" width="45" alt="Friend Image">
                        </div>
            
                        <div class="flex-grow-1 pl-2">
                            <div class="d-flex justify-content-between align-items-center my-0">
                                <strong>{{ ucfirst($friend->name) }}</strong>
                                
                                <div class="btn-group">
                                    <svg xmlns="http://www.w3.org/2000/svg" data-bs-toggle="dropdown" aria-expanded="false" class="three-dot" width="25" height="25" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                      </svg>
                                    {{-- <i class="bi bi-three-dots-vertical three-dot" data-bs-toggle="dropdown" aria-expanded="false"></i> --}}
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item unfriend_user" href="javascript:void(0)" data-type="unfriend" data-uid="{{ $friend->id }}">
                                                <small>Unfriend</small>
                                                
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
            
                            <div class="d-flex justify-content-between align-items-center" style="margin-top: -4px;">
                                <small class="text-muted" id="conservaion__short_message_type">
                                    @switch($friend['last_message']['message_type'] ?? '')
                                        @case('message')
                                            @php
                                                $message = $friend['last_message']['message'];
                                            @endphp
                                            {{ strlen($message) > 25 ? substr($message,0,23) .'...' : $message }}
                                            @break
                                        @case('media')
                                            <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -3px" width="14" height="14" fill="gray" class="bi bi-image" viewBox="0 0 16 16">
                                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                                            </svg>
                                            <span class="mt-1">Media</span>
                                            @break
                                        @case('videos')
                                            <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -3px" width="14" height="14" fill="gray" class="bi bi-image" viewBox="0 0 16 16">
                                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                                            </svg>
                                            <span class="mt-1">Video</span>
                                            @break
                                        @case('message_with_media')
                                            @php
                                                $message = $friend['last_message']['message'];
                                            @endphp
                                            <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -3px" width="14" height="14" fill="gray" class="bi bi-image" viewBox="0 0 16 16">
                                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                                            </svg>
                                            {{ strlen($message) > 25 ? substr($message,0,23) .'...' : $message }}
                                            @break
                                        @case('audio')
                                            <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -3px" width="14" height="14" fill="gray" class="bi bi-mic" viewBox="0 0 16 16">
                                                <path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5"/>
                                                <path d="M10 8a2 2 0 1 1-4 0V3a2 2 0 1 1 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3"/>
                                            </svg>
                                            <span class="mt-1">Audio</span>
                                            @break
                                        @default
                                            
                                    @endswitch
                                </small>
                                <span class="noti_counter__conservation">
                                    @php
                                        $counter = (int) getNotificationCounterForMessage($friend->id);
                                    @endphp
                                    {{ $counter !== 0 ? $counter : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
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
            </div>

            {{-- for audio  --}}
            <div id="audio-recording-ui" class="audio-recording-ui d-none position-absolute"
                style="bottom: 81px; left: 26px;">
                <div class="recording-info d-flex align-items-center">
                    <i class="bi bi-mic h4 me-2 text-danger"></i>
                    <span id="recording_span"></span>
                </div>
                <button id="stop-recording-btn" class="btn btn-sm ms-3 mx-3 bg-danger text-light">
                    <i class="bi bi-trash"></i>
                </button>

                <button id="send-recording-btn" class="btn btn-sm ms-3 bg-primary text-light">
                    <i class="bi bi-send-check"></i>
                </button>
            </div>
            
            <div class="message-input d-none position-relative" data-friend-id="" id="user-id-{{ auth()?->user()?->id }}">

                <p class="d-none" style="position: absolute; top: -21px;">Typing...</p>
                <input type="text" class="message-value" id="message-input" placeholder="Type a message..." />

                {{-- recording --}}
                <label class="start_recording" id="recording-btn" style="padding: 10px 0px 0px 4px; cursor: pointer;">
                    <i class="bi bi-mic-fill h5"></i>
                </label>

                {{-- for media  --}}
                <input hidden type="file" id="chat-image" accept="image/*, video/*" multiple>
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
