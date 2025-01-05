
@if(isset($friends) && count($friends) > 0)
    
        @foreach ($friends as $key => $friend)
            <div class="d-flex align-items-center mb-3">
                <img src="https://via.placeholder.com/50" alt="User Image" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                <div class="flex-grow-1 mx-3">
                    <h6 class="mb-0">{{ $friend['name'] }}</h6>
                    <small class="text-muted">{{ $friend['email'] }}</small>
                </div>
                <button class="btn btn-primary btn-sm ms-3" id="add_friend_btn" data-id="{{ $friend['id'] }}">Add Friend</button>
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