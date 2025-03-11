
{{-- {{  dd($conversations[0]['receiver']['image']) }} --}}
<div class="messages">
    <div class="rcvr-data text-center d-flex justify-content-between py-2">
        <div class="d-flex">
            <img class="rounded-circle mx-2" src="{{ isset($conversations[0]['receiver']['image']) ? asset('storage') .'/'. $conversations[0]['receiver']['image'] : asset('images/person.jpg') }}" alt="" height="40px" width="40px">
            <div>
                <p class="mb-0">{{ ucfirst($r['r_name']['name']) }}</p>
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
                        <div class="message sent position-relative">
                            {{ $message['message'] }}
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                            </div>
                            <div class="ticks--div">
                                <i class="bi bi-check2-all"></i>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($message['message_type'] === 'media')
                    @php
                        $msg = json_decode($message['images'], true);
                    @endphp
                    @if (count($msg) === 1)
                        <div class="message sent position-relative">
                            <img class="view_media_image" src="{{ asset('storage') .'/'. $msg[0]}}" alt="img" width="120" height="120">
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                                {{-- <span class="ticks_span">
                                    <i class="bi bi-clock d-none"></i>
                                </span> --}}
                            </div>
                            <div class="ticks--div">
                                <i class="bi bi-check2-all"></i>
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
                            <div class="ticks--div">
                                <i class="bi bi-check2-all"></i>
                            </div>
                        </div>
                    @elseif (count($msg) === 2)
                        <div class="message sent position-relative" style="margin-top: 50px; width: 240px; display: flex; flex-wrap: wrap">
                            @foreach ($msg as $key => $msgs)
                                <div class="mx-1 my-1">
                                    <img class="view_media_image" src="{{ asset('storage') .'/'. $msgs}}" alt="img" width="100" height="100">
                                </div>
                            @endforeach
                            <div class="sender_message_time">
                                {{ $message['created_at'] }}
                            </div>
                            <div class="ticks--div">
                                <i class="bi bi-check2-all"></i>
                            </div>
                        </div>
                    @else
                        @foreach ($msg as $key => $msg)
                            <div class="message sent position-relative">
                                <img class="view_media_image" src="{{ asset('storage') .'/'. $msg}}" alt="img" width="120" height="120">
                                <div class="sender_message_time">
                                    {{ $message['created_at'] }}
                                    {{-- <span class="ticks_span">
                                        <i class="bi bi-clock d-none"></i>
                                    </span> --}}
                                </div>
                                <div class="ticks--div">
                                    <i class="bi bi-check2-all"></i>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif

                @if ($message['message_type'] === 'message_with_media')
                    <div class="message sent position-relative">
                        <img class="view_media_image" src="{{ asset('storage') .'/'. $msg[0]}}" alt="img" width="120" height="120">
                        <p class="m-0 p-0">{{ $message['message'] }}</p>
                        <div class="sender_message_time">
                            {{ $message['created_at'] }}

                        </div>
                        <div class="ticks--div">
                            <i class="bi bi-check2-all"></i>
                        </div>
                    </div>
                @endif

                @if ($message['message_type'] === 'audio')
                    <div class="message sent position-relative">
                        <audio class="js-player" controls>
                            <source src="{{ asset('storage/' . $message->audio_file_path) }}" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                        <div class="sender_message_time">
                            {{ $message['created_at'] }}
                        </div>
                        <div class="ticks--div">
                            <i class="bi bi-check2-all"></i>
                        </div>
                    </div>
                @endif
            @else
                    {{-- receiver --}}
                @if ($message['message_type'] === 'message')
                    <div class="message received position-relative" style="margin-top: 50px">
                        @if ($message['message'] !== null)
                            <div class="receiver_image_and_name">
                                <img src="{{ isset($message['sender']['image']) ? asset('storage').'/'. $message['sender']['image'] : asset('images/person.jpg')}}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                {{ $message['sender']['name'] }} , <small>{{ $message['created_at'] }}</small>
                            </div>
                            {{ $message['message'] }}
                        @endif
                    </div>
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
