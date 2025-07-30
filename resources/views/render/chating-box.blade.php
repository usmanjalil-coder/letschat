
{{-- {{  dd($conversations[0]['receiver']['image']) }} --}}
<div class="messages">
    <div class="rcvr-data text-center d-flex justify-content-between py-2">
        <div class="d-flex">
            {{-- @dd($r['r_name']) --}}
            <img class="rounded-circle mx-2" src="{{ isset($r['r_name']['image']) ? asset('storage') .'/'. $r['r_name']['image'] : asset('images/person.jpg') }}" alt="" height="40px" width="40px">
            <div>
                <p class="mb-0 text-left">{{ ucfirst($r['r_name']['name']) }}</p>
                <p class="mb-0 last_seen_class" id="last-seen-{{ $r['r_name']['id'] }}" style="font-size: 12px; ">{{ $r['last_seen'] }}</p>
                <p class="mb-0 active_class d-none" id="active-id-{{ $r['r_name']['id'] }}" style="font-size: 12px; ">Active now</p>
            </div>
        </div>
        <div style="margin-top: 7px; ">

            <i class="bi bi-telephone-fill h5 " id="start-audio-call" data-name="{{ $r['r_name']['name'] }}" data-id="{{ $r['r_name']['id'] }}"></i>

            <i class="bi bi-camera-video-fill h5  mx-2" data-name="{{ $r['r_name']['name'] }}" data-id="{{ $r['r_name']['id'] }}" id="start-video-call" style="cursor: pointer"></i>
        </div>
    </div>

    {{-- @dd($conversations->toArray()) --}}
    @isset($conversations)
        @foreach ($conversations as $key => $message)
            @if($message['sender_id'] === auth()->user()->id)
                @if ($message['message_type'] === 'message')
                    @if ($message['message'] !== null)
                        <div class="message sent position-relative" style="{{ $loop->first ? 'margin-top:40px' : '' }}
                        {{
                            !$loop->first &&
                            (
                                $message['status'] === 'sent' ||
                                (
                                    $message['status'] === 'seen' &&
                                    $message['id'] === $last_message_seen['id']
                                )
                            )
                                ? 'margin-top:25px'
                                : ''
                        }}">
                            {{ $message['message'] }}
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                            </div>
                            <div class="ticks--div">
                                @if ($message['status'] === 'sent')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                    <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                @if ($message['message_type'] === 'media')
                    @php
                        $msg = json_decode($message['images'], true);
                    @endphp
                    @if (count($msg) === 1)
                        <div class="message sent position-relative" style="{{ $loop->first ? 'margin-top:40px' : '' }}">
                            <img class="view_media_image" src="{{ asset('storage') .'/'. $msg[0]}}" alt="img" width="120" height="120">
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                                {{-- <span class="ticks_span">
                                    <i class="bi bi-clock d-none"></i>
                                </span> --}}
                            </div>
                            <div class="ticks--div" style="bottom: -17px;">
                                @if ($message['status'] === 'sent')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                    <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                                @endif
                            </div>
                        </div>
                    @elseif (count($msg) === 4)
                        <div class="message sent position-relative" style="margin-top: 50px; width: 240px; display: flex; flex-wrap: wrap">
                            @foreach ($msg as $key => $msgs)
                                <div class="mx-1 my-1">
                                    <img class="view_media_image" src="{{ asset('storage') .'/'. $msgs}}" alt="img" width="100" height="100">
                                </div>
                            @endforeach
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                            </div>
                            <div class="ticks--div" style="bottom: -14px;">
                                @if ($message['status'] === 'sent')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                    <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                                @endif
                            </div>
                        </div>
                    @elseif (count($msg) === 2)
                        <div class="message sent position-relative" style="margin-top: 50px; width: 240px; display: flex; flex-wrap: wrap; {{ $loop->first ? 'margin-top:40px' : '' }}">
                            @foreach ($msg as $key => $msgs)
                                <div class="mx-1 my-1">
                                    <img class="view_media_image" src="{{ asset('storage') .'/'. $msgs}}" alt="img" width="100" height="100">
                                </div>
                            @endforeach
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                            </div>
                            <div class="ticks--div">
                                @if ($message['status'] === 'sent')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                    <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                                @endif
                            </div>
                        </div>
                    @else
                        @foreach ($msg as $key => $msg)
                            <div class="message sent position-relative" style="{{ $loop->first ? 'margin-top:40px' : '' }}">
                                <img class="view_media_image" src="{{ asset('storage') .'/'. $msg}}" alt="img" width="120" height="120">
                                <div class="sender_message_time">
                                    {{ $message['created_at'] }}
                                    {{-- <span class="ticks_span">
                                        <i class="bi bi-clock d-none"></i>
                                    </span> --}}
                                </div>
                                <div class="ticks--div">
                                    @if ($loop->last)
                                        @if ($message['status'] === 'sent')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                            <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif

                @if ($message['message_type'] === 'video' && !is_null($message['videos']))
                    @php
                        $videos = json_decode($message['videos'], true);
                    @endphp
                    @foreach ($videos as $key => $video)
                        <div class="message sent position-relative" style="{{ $loop->first ? 'margin-top:40px' : '' }}">
                            <video width="220" height="220" controls>
                                <source src="{{ asset('storage') .'/'. $video}}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                            </div>
                            <div class="ticks--div">
                                {{-- @if ($loop->last) --}}
                                    @if ($message['status'] === 'sent')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                        <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                                    @endif
                                {{-- @endif --}}
                            </div>
                        </div>
                    @endforeach
                @endif

                @if ($message['message_type'] === 'message_with_media')
                {{-- @dd($message['videos']); --}}
                    <div class="message sent position-relative" style="{{ $loop->first ? 'margin-top:40px' : '' }}">
                        @if(!empty(json_decode($message['videos'], true)) && str_contains(json_decode($message['videos'], true)[0], '.mp4')) 
                            <video width="220" height="220" controls>
                                <source src="{{ asset('storage') .'/'. json_decode($message['videos'], true)[0]}}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <img class="view_media_image" src="{{ asset('storage') .'/'. json_decode($message['images'], true)[0]}}" alt="img" width="120" height="120">
                        @endif
                        <p class="m-0 p-0">{{ $message['message'] }}</p>
                        <div class="sender_message_time">
                            {{ $message['created_at'] }}

                        </div>
                        <div class="ticks--div">
                            @if ($message['status'] === 'sent')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                            @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                            @endif
                        </div>
                    </div>
                @endif

                @if ($message['message_type'] === 'audio')
                    <div class="message sent position-relative" style="{{ $loop->first ? 'margin-top:40px' : '' }}">
                        <audio class="js-player" controls>
                            <source src="{{ asset('storage/' . $message->audio_file_path) }}" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                        <div class="sender_message_time">
                            {{ $message['created_at'] }}
                        </div>
                        <div class="ticks--div" style="top: 64px;">
                            @if ($message['status'] === 'sent')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                            @elseif($message['status'] === 'seen' && $message['id'] === $last_message_seen['id'])
                                <img src="{{ isset($r['r_name']['image']) ? asset('storage').'/'. $r['r_name']['image'] : asset('images/person.jpg')}}" height="15px" width="15px" class="rounded-circle" alt="">
                            @endif
                        </div>
                    </div>
                @endif
            @else
                    {{-- receiver --}}
                @if ($message['message_type'] === 'message')
                    @if ($message['message'] !== null)
                        <div class="message received position-relative" style="margin-top: 50px">
                            <div class="receiver_image_and_name">
                                <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                            </div>
                            {{ $message['message'] }}
                        </div>
                    @endif
                @elseif ($message['message_type'] === 'audio')
                    
                    <div class="message received position-relative" style="margin-top: 50px">
                        <div class="receiver_image_and_name">
                            <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                            {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                        </div>
                        <audio class="js-player" controls>
                            <source src="{{ asset('storage/' . $message->audio_file_path) }}" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                    </div>

                {{-- @elseif ($message['message_type'] === 'message_with_media')
                    <div class="message received position-relative" style="margin-top: 50px">
                        <div class="receiver_image_and_name">
                            <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                            {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                        </div>
                        {{ $message['message'] }}
                    </div> --}}
                @elseif ($message['message_type'] === 'video' && !is_null($message['videos']))
                    @php
                        $videos = json_decode($message['videos'], true);
                    @endphp
                    @foreach ($videos as $key => $video)
                        <div class="message received position-relative" style="margin-top: 50px">
                            <div class="receiver_image_and_name">
                                <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                            </div>
                            <video width="220" height="220" controls>
                                <source src="{{ asset('storage') .'/'. $video}}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                     @endforeach
                @elseif ($message['message_type'] === 'media')
                    @php
                        $msg_r = json_decode($message['images'], true);
                    @endphp
                    @if (count($msg_r) === 1)
                        <div class="message received position-relative" style="margin-top: 50px">
                            <div class="receiver_image_and_name">
                                <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                            </div>
                            <img class="view_media_image" src="{{ asset('storage') .'/'. $msg_r[0]}}" alt="img" width="120" height="120">
                        </div>
                    @elseif (count($msg_r) === 2)
                        <div class="message received position-relative" style="margin-top: 50px; width: 240px; display: flex; flex-wrap: wrap">
                            <div class="receiver_image_and_name">
                                <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                            </div>
                            @foreach ($msg_r as $key => $msgs)
                                <div class="mx-1 my-1">
                                    <img class="view_media_image" src="{{ asset('storage') .'/'. $msgs}}" alt="img" width="100" height="100">
                                </div>
                            @endforeach
                        </div>
                    @elseif(count($msg_r) === 4)
                        <div class="message received position-relative" style="margin-top: 50px; width: 240px; display: flex; flex-wrap: wrap">
                            <div class="receiver_image_and_name">
                                <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                            </div>
                            @foreach ($msg_r as $key => $msgs)
                                <div class="mx-1 my-1">
                                    <img class="view_media_image" src="{{ asset('storage') .'/'. $msgs}}" alt="img" width="100" height="100">
                                </div>
                            @endforeach
                        </div>
                    @else
                        @foreach ($msg_r as $key => $msgs)
                            <div class="message received position-relative" style="margin-top: 50px">
                                <div class="receiver_image_and_name">
                                    <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                    {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                                </div>
                                <img class="view_media_image" src="{{ asset('storage') .'/'. $msgs}}" alt="img" width="120" height="120">
                            </div>
                        @endforeach
                    @endif
                @endif
            @endif
        @endforeach
    @endisset
</div>
