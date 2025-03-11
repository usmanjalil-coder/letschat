<!-- Modal -->
<div class="modal fade" id="friendRequestModal" tabindex="-1" role="dialog" aria-labelledby="friendRequestModalTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="friendRequestModalTitle">Notifications</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 70vh; overflow-y:scroll;">
                <div id="spinner" class="d-flex justify-content-center">
                    <img src="{{ asset('images/loader.gif') }}" class="d-none" height="60px" alt=""></div>
                <!-- User List -->
                <div id="request-list-append">
                    
                </div>
            </div>
            

        </div>
    </div>
</div>
