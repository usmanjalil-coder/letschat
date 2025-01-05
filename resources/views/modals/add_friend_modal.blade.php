<!-- Modal -->
<div class="modal fade" id="addFriendModal" tabindex="-1" role="dialog" aria-labelledby="addFriendModalTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFriendModalTitle">Search Friends</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 70vh; overflow-y:scroll;">
                <!-- Search Input -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="search-friend-input" placeholder="Search Friend">
                </div>
            
                <!-- User List -->
                <div id="loader" class="d-flex justify-content-center" style="display: none !important;">
                    <img src="{{ asset('images/loader.gif') }}" height="60px" alt=""></div>
                <div id="user-list">
                </div>
            </div>
            

        </div>
    </div>
</div>
