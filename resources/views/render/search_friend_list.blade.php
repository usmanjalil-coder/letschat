
@if(isset($friends) && count($friends['data']) > 0)
    
        @foreach ($friends['data'] as $key => $friend)
            <div class="d-flex align-items-center mb-3">
                <img src="{{ isset($friend['image']) ?  asset('storage') .'/'. $friend['image'] : asset('images/person.jpg')}}" alt="User Image" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                <div class="flex-grow-1 mx-3">
                    <h6 class="mb-0">{{ $friend['name'] }}</h6>
                    <small class="text-muted">{{ $friend['email'] }}</small>
                </div>
                @if ($friend['already_friend'])
                    <button class="btn btn-warning btn-sm ms-3" 
                        id="unfriend_user" data-type="unfriend"
                        data-uid="{{ $friend['id'] }}">
                        {{ 'Unfriend' }}
                    </button>
                @else
                    <button class="btn {{ $friend['request_send'] ? 'btn-success' : 'btn-primary' }} btn-sm ms-3" 
                        id="add_friend_btn" 
                        data-friend-status="{{ intval($friend['request_send']) }}" 
                        data-id="{{ $friend['id'] }}">
                        {{ $friend['request_send'] ? 'Cancel Friend Request' : 'Send Request' }}
                    </button>
                @endif
            </div>
        @endforeach
        
    
@else
    <div class="no-records">
        <!-- User 1 -->
        <div class="text-center" style="margin-top: 20vh">
        <div>No records found......</div>
        </div>
    </div>
@endif