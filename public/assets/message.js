$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('body').on('click', '.view_media_image', function () {
        $('#modal_image').attr('src', $(this).attr("src"));
        $('#viewPostModal').modal('show');
    });

    document.querySelectorAll('.three-dot').forEach(dot => {
        dot.addEventListener('click', function (event) {
            event.stopPropagation();
        });
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

    document.getElementById('recording-btn').addEventListener('click', async function () {
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

    function stopRecording() {
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

    document.getElementById('stop-recording-btn').addEventListener('click', stopRecording);

    function sendMessage(_this){

        var message = $("#message-input").data("emojioneArea").getText().trim();
        let receiver_id = _this.attr('data-receiver-id');
        var parent = $("#conservation-"+ receiver_id)
        
        if (message.trim().length > 0) {
            // console.log($('.messages').children('.message').length)
            if($('.messages').children('.message').length > 0) {
                $('.messages .message:last').after(`
                    <div class="message sent position-relative">
                        ${message}
                        <div class="sender_message_time">Just now</div>
                        <div class="ticks--div">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                            </svg>
                        </div>
                    </div>
                `);
            }else{
                $('.messages').append(`
                    <div class="message sent position-relative" style="margin-top: 40px; ">
                    ${message}
                    <div class="sender_message_time">Just now</div>
                    <div class="ticks--div">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                            </svg>
                        </div>
                    </div>
                `);
            }
            if(parent.hasClass('active-conversation')) 
                parent.find('#conservaion__short_message_type').text(
                                                    message.length > 25 ? message.slice(0,25) + '...' : message
                                                )
        }
        $('.messages').animate({ scrollTop: $('.messages')[0].scrollHeight }, 300);
        $("#message-input").data("emojioneArea").setText('');

        let formData = new FormData();
        formData.append("message", message);
        formData.append("receiver_id", receiver_id);

        if ($('.img-container').children().length > 0) {
            $('.img-container .img-div .render__media').each(function () {
                let mediaSrc;

                if ($(this).is('img')) {
                    mediaSrc = $(this).attr('src');
                } else if ($(this).is('video')) {
                    mediaSrc = $(this).find('source').attr('src');
                }
                
                let image = ` <img class="view_media_image" src="${mediaSrc}" alt="img" width="120" height="120">`

                let video = `<video width="220" height="220" controls>
                                <source src="${mediaSrc}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>`

                $('.messages').append(
                    `<div class="message sent position-relative">
                        ${
                            mediaSrc.startsWith('data:image') 
                            ? image : 
                            (
                                mediaSrc.startsWith('data:video') 
                                ? video : ''
                            )
                        }
                       
                        <p class="m-0 p-0">${message === '' ? '' : message}</p>
                         <div class="sender_message_time"> just now </div>
                         <div class="ticks--div" style="bottom: -17px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                            </svg>
                        </div>
                    </div>`
                );
                let mimeType = ''
                mediaSrc.startsWith("data:image") 
                        ? mimeType = 'image/jpeg' : 
                        (
                            mediaSrc.startsWith("data:video")
                            ? mimeType= 'video/mp4': ''
                        )

                let blobBin = atob(mediaSrc.split(',')[1]);
                let array = [];
                for (let i = 0; i < blobBin.length; i++) {
                    array.push(blobBin.charCodeAt(i));
                }
                let file = new Blob([new Uint8Array(array)], { type: mimeType });
                if(mimeType === 'image/jpeg'){
                    formData.append('images[]', file, `image-${Date.now()}.jpg`);
                }else if(mimeType === 'video/mp4') {
                    formData.append('videos[]', file, `video-${Date.now()}.mp4`);
                }
            });
            if(parent.hasClass('active-conversation')) 
                parent.find('#conservaion__short_message_type').html(`
                                            <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -3px" width="14" height="14" fill="gray" class="bi bi-image" viewBox="0 0 16 16">
                                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                                            </svg>
                                            <span class="mt-1">Media</span>`)
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
                        <audio class="js-player" controls>
                            <source src="${URL.createObjectURL(audioBlob)}" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                        <div class="sender_message_time">
                            ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                        </div>
                        <div class="ticks--div">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6464fa" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                            </svg>
                        </div>
                    </div>`
                );

                // Re-initialize Plyr
                document.querySelectorAll('.js-player').forEach(player => {
                    if (!player.plyr) {
                        new window.Plyr(player, {
                            controls: ['play', 'progress', 'current-time', 'duration', 'mute']
                        });
                    }
                });

                sendAjaxRequest(formData);
                isRecording = false
            };
        } else {
            sendAjaxRequest(formData);
        }


        $('.img-container').html('');
        $('.messages').css('height', '70vh');
        
    }

    $('body').on('keydown', '.message-value',function(e) {
        let _this = $("#message-input")
        
        if(e.keyCode === 13) {
            console.log('enter is pressed')
            sendMessage(_this);
        }
    })

    $('body').on('click', '#send-btn, #send-recording-btn', function () {
        var _this = $(this);
        sendMessage(_this);
    });
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
            success: function (data) {
                if (data.status == 200) {
                    $('.messages').scrollTop($('.messages')[0].scrollHeight);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    var receiverID;

    $('body').on('click', '.conversation-item', function () {
        var _this = $(this);
        let receiver_id = _this.data("id");
        receiverID = receiver_id
        $('.conversation-item').removeClass('active-conversation')
        _this.addClass('active-conversation')

        $('#send-btn').attr('data-receiver-id', receiver_id);
        $('#send-recording-btn').attr('data-receiver-id', receiver_id);
        $('#message-input').attr('data-receiver-id', receiver_id);
        $('#message-input').val('');
        $('.starter-text').remove()
        _this.find('.noti_counter__conservation').text('')
        // $('.messages').scrollTop($('.messages')[0].scrollHeight);
        $.ajax({
            url: "/fetch-message",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                receiver_id: receiver_id,
            },
            beforeSend: function () {
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
            success: function (data) {
                $('#chat-area-append').html('')
                if (data.status == 200) {
                    $('#chat-area-append').html(data.view)
                    $('.message-input').removeClass('d-none')
                    // Re-initialize Plyr
                    document.querySelectorAll('.js-player').forEach(player => {
                        if (!player.plyr) {
                            new window.Plyr(player, {
                                controls: ['play', 'progress', 'current-time', 'duration', 'mute']
                            });
                        }
                    });
                }
                setTimeout(function () {
                    scrollToBottom();
                }, 50);

            },
            error: function (error) {
                console.log(error);

            }
        })
            .always(function () {
                let onlineUsers = JSON.parse(localStorage.getItem("online-users"));
                // console.log(receiverID)
                if (onlineUsers.includes(receiverID)) {
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

    // $('body').on('keydown', '.message-value', function() {
    //     let _this = $(this);
    //     let receiver_id = $('#message-input').data('receiver-id')
    //     clearTimeout(typingTimer);
    //     typingTimer = setTimeout(function() {
    //         $.post({
    //             url: '/is-typing',
    //             data: { receiver_id: receiver_id },
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //     }, 1000);
    // });
})


$("#chat-image").on('change', function (e) {
    let files = Array.from(e.target.files);
    $('.messages').css('height', '60vh');
    $('.messages').animate({ scrollTop: $('.messages')[0].scrollHeight }, 300);
    console.log(files);
    
    files.forEach(file => {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // console.log(e.target.result)
                let img = `<div class="img-div mx-3 position-relative">
                                    <i class="bi bi-x delete-image"></i>
                                    <img class="render__media" src="${e.target.result}" alt="img" width="90" height="90">
                                </div> `;
                $('.img-container').append(img);

            }
            reader.readAsDataURL(file);
            $(this).val('');
        }else if(file && file.type.startsWith('video/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let vid = `
                <div class="img-div mx-3 position-relative">
                    <i class="bi bi-x delete-image" style="z-index: 99;"></i>
                    <video class="render__media" width="90" height="90" controls>
                        <source src="${e.target.result}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div> `;
                $('.img-container').append(vid);
            }
            reader.readAsDataURL(file);
            $(this).val('');
        }
        else {
            console.log("Please select the image");
        }
    })
})

$('body').on('click', '.delete-image', function () {
    if ($('.img-container').children().length === 1) {
        // $('.img-container').remove();
        $('.messages').css('height', '70vh');
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
            success: function (response) {
                console.log('Last seen updated due to inactivity');
            }
        });
    }

};

$('body').on('click', '#add_frnd_btn', () => {
    searchAndGetAllFriends()
    $('#addFriendModal').modal('show')
})
$('#addFriendModal .close').on('click', function () {
    $('#addFriendModal').modal('hide')
})

// Friend request modal 
$('body').on('click', '#request-list', () => {
    $("#notification_counter").text('0')
    $('#friendRequestModal').modal('show')
    $("#spinner img").removeClass('d-none')
    $.ajax({
        url: "/fetch-friend-request",
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#spinner').css('display', 'block !important')
        },
        success: function (res) {
            if (res.status == 'success') {
                $('#spinner').css('display', 'none !important')
                $('#request-list-append').html(res.view)
            }
        },
        error: function (err) {
            $('#spinner').css('display', 'none !important')
            console.log(err);
        },
        complete: function() {
            $("#spinner img").addClass('d-none')
        }
    })
})
$('#friendRequestModal .close').on('click', function () {
    $('#friendRequestModal').modal('hide')
})


var debounceTimeout;
var searchInterval = 200;

function searchAndGetAllFriends(serachVal = null) {
    clearTimeout(debounceTimeout)
    debounceTimeout = setTimeout(() => {
        $("#spinner img").removeClass('d-none')
        $.ajax({
            url: "/search-friend",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                search_value: serachVal == null ? '' : serachVal
            },
            success: function (res) {
                if (res.status === 'success') {
                    $('#user-list').html('')
                    $('#user-list').html(res.view)
                }
            },
            error: function (error) {
                console.log(error)
            },
            complete: function () {
                $("#spinner img").addClass('d-none')
            }

        })
    }, searchInterval);
}

$('#addFriendModal').find('input[id="search-friend-input"]')
    .on('input', function () {
        searchAndGetAllFriends($(this).val().trim())
    })
if(!window.location.pathname.includes('/login')) {
    $(document).on('mousemove keypress', resetActivity);
    setInterval(checkActivity, 10000);
}

$(document).ready(function () {
    $('body').on('click', '#add_friend_btn', function () {

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
            success: function (res) {
                if (res.status === 'success') {
                    status = parseInt(_this.attr('data-friend-status'));

                    if (status === 0) {
                        _this.text('Cancel Friend Request')
                            .addClass('btn-success').removeClass('btn-primary')
                            .attr('data-friend-status', 1);
                    } else if (status === 1) {
                        console.log('Inside 1 (Cancel Request)');
                        _this.text('Send Request')
                            .addClass('btn-primary').removeClass('btn-success')
                            .attr('data-friend-status', 0);
                    }
                    showToast(res.message, 'success')
                } else {
                    console.error('Error:', res.message);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    var authUser = $('meta[name="auth-name"]').attr('content')
    var chatAreaDummyHTml = `
    <div class="starter-text">
        <h3 class="h2">Hello, ${authUser} ☺️</h3>
        <p>Let's start a new chat...</p>
    </div>
    <div id="chat-area-append"></div>
    <div class="img-container d-flex py-2"></div>
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

        <label class="start_recording" id="recording-btn" style="padding: 10px 0px 0px 4px; cursor: pointer;">
            <i class="bi bi-mic-fill h5"></i>
        </label>

        <input hidden type="file" id="chat-image" multiple>
        <label for="chat-image" style="padding: 10px 9px 0px 11px; cursor: pointer;">
            <i class="bi bi-camera h5"></i>
        </label>

        <label class="send-message-btn" id="send-btn" style="padding: 10px 9px 0px 0px; cursor: pointer;">
            <i class="bi bi-send h5"></i>
        </label>
    </div>`;

    function friendRequestAcceptORReject(_this, id, type) {
        if(type === 'unfriend') {
            if(!confirm('Your chat will be deleted if you unfriend!')) {
                return
            }
        }
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
            success: function (res) {
                if (res.status === 'success') {
                    if (type === 'accepted') {
                        _this.text('Request accpeted').addClass('btn-success').prop('disabled', true)
                        $('#request_rejected').remove()
                        console.log('inside the accepted ')
                        window.location.reload(true)

                    } else if (type === 'rejected') {
                        _this.text('Request Rejected').removeClass("btn-danger").addClass('btn-success').prop('disabled', true);
                        $('#request_accepted').remove()
                    } else {
                        $('#conservation-' + id).remove();
                        _this.text('Send Request').removeClass("btn-warning").addClass('btn-primary')
                        if ($('.conversation-item').length === 0) {
                            $('.conversation-list').append(`
                                     <div class=" text-center my-5">
                                        <p>You havn't any friend!</p> <div>
                                            <button class="btn btn-sm btn-primary" id="add_frnd_btn">Add friend</button>
                                        </div>
                                    </div>
                                `)
                        }
                        $('.chat-area').html(chatAreaDummyHTml);
                    }
                    showToast(res.message, 'success')
                } else {
                    showToast(res.message, 'error');
                }
                _this.text('Unfriend');
            },
            error: function (error) {
                _this.text('Unfriend');
                console.log(error);
            }
        });
    }

    $('body').on('click', '.request_accept_or_reject, .unfriend_user', function (e) {
        e.stopPropagation()
        let _this = $(this);
        let id = _this.data('uid');
        let type = _this.data('type');
        // if(type != 'accepted' || type != 'rejected' || type != 'unfriend' || type == '') return ;
        friendRequestAcceptORReject(_this, id, type)
    });
});





