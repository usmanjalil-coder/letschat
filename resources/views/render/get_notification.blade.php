{{-- @dd($friendRequest->toArray()); --}}
@if (isset($friendRequest) && count($friendRequest) > 0)
    @foreach ($friendRequest as $key => $friendRequest)
        <div class="d-flex align-items-center mb-3" id="request_div_{{$friendRequest['from_user_id']}}">
            <img src="{{ isset($friendRequest['image']) ? asset('storage') .'/'. $friendRequest['image'] : asset('images/person.jpg')}}" alt="User Image" class="rounded-circle me-3" style="width: 40px; height: 40px;">
            <div class="flex-grow-1 mx-3">
                <h6 class="mb-0">{{ ucfirst($friendRequest['name']) }}</h6>
                <p class="text-muted" style="font-size: 13px; ">{{ $friendRequest['message'] }}
                    <small>({{ toLocalTimeZone($friendRequest['created_at']) }})</small>
                </p>
                   
            </div>
            @if ($friendRequest['action'] === 'friend_request')
                <div>
                    <button class="btn btn-sm btn-danger request_accept_or_reject" id="request_rejected" data-type="rejected" data-uid="{{ $friendRequest['from_user_id'] }}" >Reject</button>
                    <button class="btn btn-sm btn-primary request_accept_or_reject" id="request_accepted" data-type="accepted" data-uid="{{ $friendRequest['from_user_id'] }} ">Accept</button>
                </div>
            @endif
        </div>
    @endforeach
@else
    <div class="text-center"><p>You hav't notification yet!</p></div>
@endif
