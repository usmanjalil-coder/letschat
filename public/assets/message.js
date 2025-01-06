$(document).ready(function(){

    $('body').on('click', '.view_media_image', function() {
        $('#modal_image').attr('src', $(this).attr("src"));
        $('#viewPostModal').modal('show');
    });

    // $('body').on('click', '.close-modal', function() {
    //     alert('hhh')
    //     $('#modal_image').attr('src', '');
    //     $('#viewPostModal').modal('hide');
    // });


    $("#message-input").emojioneArea({
        buttonTitle: "Use the TAB key to insert emoji faster"
    });

    let mediaRecorder;
    let audioChunks = [];
    let timerInterval;
    let audioStream;
    let isRecording = false;

    document.getElementById('recording-btn').addEventListener('click', async function() {
        document.getElementById('audio-recording-ui').classList.remove('d-none');
        let waveform = document.getElementById('recording_span');
        
        let m = 0;
        let s = 0;
    
        clearInterval(timerInterval);

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            try {
            audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(audioStream);
            audioChunks = [];
            mediaRecorder.start();
            isRecording = true;
            timerInterval = setInterval(function () {
                if (s < 59) {
                    s++;
                } else {
                    s = 0;
                    m++;
                }
                waveform.textContent = "Recording... " + m + " : " + (s < 10 ? '0' + s : s);
            }, 1000);


            mediaRecorder.ondataavailable = (event) => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                clearInterval(timerInterval); // Stop timer
                document.getElementById('audio-recording-ui').classList.add('d-none');
                waveform.textContent = "Recording... 0 : 00";
                isRecording = false;
            };

            } catch (error) {
                console.error("Error accessing microphone", error);
            }
        }
    
    });

    function stopRecording(){
        document.getElementById('audio-recording-ui').classList.add('d-none');
        
        let waveform = document.getElementById('recording_span');
        waveform.textContent = "Recording... 0 : 00";
    
        clearInterval(timerInterval);
    
    
        if (mediaRecorder && mediaRecorder.state !== "inactive") {
            mediaRecorder.stop();
        }

        if (audioStream) {
            audioStream.getTracks().forEach(track => track.stop());
        }
    }

    document.getElementById('stop-recording-btn').addEventListener('click',stopRecording);


    $('body').on('click', '#send-btn', function() {
        var _this = $(this);
        let message = $('#message-input').val();
        
        if ($('#message-input').val().length > 0) {
            $('.messages .message:last').after('<div class="message sent">' + message + '</div>');
        }
        $('.messages').animate({ scrollTop: $('.messages')[0].scrollHeight }, 300);
        $("#message-input").data("emojioneArea").setText('');
        
        let receiver_id = _this.attr('data-receiver-id');
        let formData = new FormData();
        formData.append("message", message);
        formData.append("receiver_id", receiver_id);

        if ($('.img-container').children().length > 0) {
            $('.img-container .img-div img').each(function() {
                let imgSrc = $(this).attr('src');
                $('.messages').append(
                    `<div class="message sent">
                        <img src="${imgSrc}" alt="img" width="90" height="90">
                        <p class="m-0 p-0">${message === '' ? '' : message}</p>
                         <div class="sender_message_time"> just now </div>
                    </div>`
                );
                let blobBin = atob(imgSrc.split(',')[1]);
                let array = [];
                for (let i = 0; i < blobBin.length; i++) {
                    array.push(blobBin.charCodeAt(i));
                }
                let file = new Blob([new Uint8Array(array)], { type: 'image/jpeg' });
                formData.append('images[]', file, `image-${Date.now()}.jpg`);
            });
        }
    
        if (isRecording) {
            mediaRecorder.stop();
            
            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: "audio/wav" });
                console.log("Audio Blob:", audioBlob);  // Add this line for debugging
                formData.append("audio", audioBlob, "recording.wav");
                audioChunks = [];
                
                if (audioStream) {
                    audioStream.getTracks().forEach(track => track.stop());
                }

                document.getElementById('audio-recording-ui').classList.add('d-none');
        
                let waveform = document.getElementById('recording_span');
                waveform.textContent = "Recording... 0 : 00";
            
                clearInterval(timerInterval);

                $('.messages .message:last').after(
                   `<div class="message sent position-relative">
                        <audio controls>
                            <source src="${URL.createObjectURL(audioBlob)}" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                        <div class="sender_message_time">
                            ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                        </div>
                    </div>`
                );

                

                sendAjaxRequest(formData);
                isRecording = false
            };
        } else {
            sendAjaxRequest(formData);
        }
    
        
        $('.img-container').html('');
        $('.messages').css('height','70vh');
        function sendAjaxRequest(formData) {
            $.ajax({
                url: "/send-message",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200) {
                        $('.messages').scrollTop($('.messages')[0].scrollHeight);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });
    
    var receiverID;
    
    $('body').on('click','.conversation-item', function(){
        var _this = $(this);
        let receiver_id = _this.data("id");
        receiverID = receiver_id
        $('.conversation-item').removeClass('active-conversation')
        _this.addClass('active-conversation')
       
        $('#send-btn').attr('data-receiver-id', receiver_id);
        $('#message-input').attr('data-receiver-id', receiver_id);
        $('#message-input').val('');
        $('.starter-text').remove()
        // $('.messages').scrollTop($('.messages')[0].scrollHeight);
        $.ajax({
            url: "/fetch-message",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                receiver_id : receiver_id,
            },
            beforeSend: function() {
                $('.messages').html(`
                    <div class="skeleton-loader">
                        <div class="skeleton-message sender"></div>
                    </div>
                    <div class="skeleton-loader">
                        <div class="skeleton-message receiver"></div>
                    </div>
                                        <div class="skeleton-loader">
                        <div class="skeleton-message sender"></div>
                    </div>
                    <div class="skeleton-loader">
                        <div class="skeleton-message receiver"></div>
                    </div>
                    <div class="skeleton-loader">
                        <div class="skeleton-message sender"></div>
                    </div>
                    <div class="skeleton-loader">
                        <div class="skeleton-message receiver"></div>
                    </div>

                `);

                $('.last_seen_class').removeClass('d-none')
                $('.active_class').addClass('d-none')
            },
            success: function(data){
                $('#chat-area-append').html('')
                if(data.status == 200){
                    $('#chat-area-append').html(data.view)
                    $('.message-input').removeClass('d-none')
                }
                setTimeout(function () {
                    scrollToBottom();
                }, 50);
               
            },
            error: function(error){
                console.log(error);
                
            }
        })
        .always(function(){
            let onlineUsers = JSON.parse(localStorage.getItem("online-users"));
            // console.log(receiverID)
            if(onlineUsers.includes(receiverID)){
                $('#last-seen-' + receiverID).addClass('d-none')
                $('#active-id-' + receiverID).removeClass('d-none')
            }
        })
    })

    function scrollToBottom() {
        const messagesContainer = document.querySelector('.messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // let typingTimer;

    // $('body').on('keydown', '#message-input', function() {
    //     let _this = $(this);
    //     let receiver_id = _this.attr('data-receiver-id');
    //     clearTimeout(typingTimer);

    //     $.post({
    //         url: '/is-typing',
    //         data: { receiver_id: receiver_id },
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    
    //     typingTimer = setTimeout(function() {
 
    //     }, 1000);
    // });
    

})


    $("#chat-image").on('change', function(e){
        let files = Array.from(e.target.files);
        $('.messages').css('height','60vh');
        $('.messages').animate({ scrollTop: $('.messages')[0].scrollHeight }, 300);
        files.forEach(file => {
            if(file && file.type.startsWith('image/')){
                const reader = new FileReader();
                reader.onload = function(e){
                    // console.log(e.target.result)
                    let img = `<div class="img-div mx-3 position-relative">
                                    <i class="bi bi-x delete-image"></i>
                                    <img src="${e.target.result}" alt="img" width="90" height="90">
                                </div> `;
                                $('.img-container').append(img);
                                
                }
                reader.readAsDataURL(file);
                $(this).val('');
            }else{
                console.log("Please select the image");
            }
        })
    })

    $('body').on('click','.delete-image', function(){
        if($('.img-container').children().length === 1){
            // $('.img-container').remove();
            $('.messages').css('height','70vh');
        }
        $(this).parents('.img-div').remove()
    })

    let userActive = true;
    let activityTimeout;
    
    const resetActivity = () => {
        userActive = true;
        clearTimeout(activityTimeout);
        activityTimeout = setTimeout(() => {
            userActive = false;
        }, 30000);
    };
    
    const checkActivity = () => {
        if (!userActive) {
            $.ajax({
                url: '/update-last-seen',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Last seen updated due to inactivity');
                }
            });
        }

    };
    
    $('body').on('click', '#add_frnd_btn', () => {
        searchAndGetAllFriends()
        $('#addFriendModal').modal('show')
    })
    $('#addFriendModal .close').on('click', function(){
        $('#addFriendModal').modal('hide')
    })

    // Friend request modal 
    $('body').on('click', '#request-list', () => {
        $('#friendRequestModal').modal('show')
        $.ajax({
            url: "/fetch-friend-request",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(){
                $('#loader').css('display', 'block !important')
            },
            success: function(res){
                if(res.status == 'success'){
                    $('#loader').css('display', 'none !important')
                    $('#request-list-append').html(res.view)
                }
            },
            error: function(err){
                $('#loader').css('display', 'none !important')
                console.log(err);
            }
        })
    })
    $('#friendRequestModal .close').on('click', function(){
        $('#friendRequestModal').modal('hide')
    })

   
    var debounceTimeout; 
    var searchInterval = 200;

    function searchAndGetAllFriends(serachVal = null){
        clearTimeout(debounceTimeout)
        // $("#loader").fadeIn()
        debounceTimeout = setTimeout(() => {
            $.ajax({
                url: "/search-friend",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    search_value : serachVal == null ? '': serachVal
                },
                success: function(res){
                    if(res.status === 'success'){
                        $('#user-list').html('')
                        $('#user-list').html(res.view)
                    }
                },
                error: function(error){
                    console.log(error)
                },
                complete: function() {
                    $("#loader").fadeOut()
                }

            })
        }, searchInterval);
    }

    $('#addFriendModal').find('input[id="search-friend-input"]')
            .on('input', function(){
                searchAndGetAllFriends($(this).val().trim())
            })

    $(document).on('mousemove keypress', resetActivity);
    setInterval(checkActivity, 10000);

    $(document).ready(function() {
        $('body').on('click', '#add_friend_btn', function() {
            let _this = $(this);
            let id = _this.data('id');
            let status = parseInt(_this.data('friend-status'));
    
            _this.text('Loading...');
    
            $.ajax({
                url: "/send-friend-request",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                success: function(res) {
                    if (res.status === 'success') {
                        status = parseInt(_this.attr('data-friend-status')); 
    
                        if (status === 0) {
                            console.log('Inside 0 (Send Request)');
                            _this.text('Cancel Friend Request')
                                 .addClass('btn-success').removeClass('btn-primary')
                                 .attr('data-friend-status', 1); // Change to cancel type
                        } else if (status === 1) {
                            console.log('Inside 1 (Cancel Request)');
                            _this.text('Send Request')
                                 .addClass('btn-primary').removeClass('btn-success')
                                 .attr('data-friend-status', 0); // Change to send type
                        }
                    } else {
                        console.error('Error:', res.message);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $('body').on('click', '.request_accept_or_reject', function() {
            let _this = $(this);
            let id = _this.data('uid');
            let type = _this.data('type');
            if(type === '') return ;
            _this.text('Loading...');
    
            $.ajax({
                url: "/request-accept-reject",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    type: type
                },
                success: function(res) {
                    if (res.status === 'success') {
                        if(type === 'accepted') {
                            _this.text('Request accpeted').addClass('btn-success').prop('disabled', true)
                            $('#request_rejected').remove()
                            
                        }else{
                            _this.text('Request Rejected').removeClass("btn-danger").addClass('btn-success').prop('disabled', true);
                            $('#request_accepted').remove()
                        }
                    } else {
                        console.error('Error:', res.message);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
    




