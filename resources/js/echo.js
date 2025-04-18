import Echo from 'laravel-echo';
import 'toastr/build/toastr.min.css';
import Pusher from 'pusher-js';
window.Pusher = Pusher;
import Noty from 'noty';
import 'noty/lib/noty.css'; // Default CSS for Noty
// import 'noty/lib/themes/mint.css';
import Plyr from 'plyr';
import 'plyr/dist/plyr.css';
window.Plyr = Plyr;
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-player').forEach(player => {
        new Plyr(player, {
            controls: ['play', 'progress', 'current-time', 'duration', 'mute']
        });
    });
});

// Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    // forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    // forceTLS: false,
    // authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    // withCredentials: true,
});

var userId = document.querySelector('meta[name="auth-id"]').getAttribute('content');
var url = document.querySelector('meta[name="url"]').getAttribute('content');
// var userName = document.querySelector('meta[name="auth-name"]').getAttribute('content');

// let authId = $('meta[name="auth-id"]').attr('content')

// $('body').on('keydown', '.message-value', function () {
//     const receiverId = $('#message-input').data('receiver-id');
//     console.log('auth id is ===>', authId);
//     console.log('receiver id is ====> ', receiverId);

//     const channel = window.Echo.private(`typing.${receiverId}`);

//     channel.subscribed(() => {
//         channel.whisper('typing', {
//             sender_id: authId,
//         });
//     }).error((error) => {
//         console.error('Error joining channel:', error);
//     });
// });

function seenMessage(receiver__id) {
    $.ajax({
        url: "/mark-as-seen",
        type: "POST",
        data: {receiver_id: receiver__id},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if (data.status == 200) {
                console.log(data.message)
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}


window.Echo.private(`chat-channel.${userId}`)
    .listen('.chat-event', (e) => {
        // console.log($(`#conservation-${e.sender}`).hasClass('active-conversation'))
        let parent_conservation = $(`#conservation-${e.sender}`)
        if(parent_conservation.hasClass('active-conversation')){
            seenMessage(e.sender)
        
            if (e.message_type === 'message') {
                const messageHtml = `
                    <div class="message received position-relative" style="margin-top: 50px">
                        <div class="receiver_image_and_name">
                            <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                            ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                        </div>
                        ${e.message}
                    </div>`;
                $('.messages').append(messageHtml);
            }

            if (e.message_type === 'audio') {
                const mediaHtml = `
                <div class="message received position-relative" style="margin-top: 50px">
                    <div class="receiver_image_and_name">
                        <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                        ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                    </div>
                    <div class="message sent">
                        <audio class="js-player" controls>
                            <source src="${url + 'storage/' + e.audioUrl}" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>`;
                
                $('.messages').append(mediaHtml);
                document.querySelectorAll('.js-player').forEach(player => {
                    if (!player.plyr) {
                        new window.Plyr(player, {
                            controls: ['play', 'progress', 'current-time', 'duration', 'mute']
                        });
                    }
                });
            }

            if (e.message_type === 'media') {
                if (e.media.length === 1) {
                    const mediaHtml = `
                    <div class="message received position-relative" style="margin-top: 50px;  display: flex; flex-wrap: wrap">
                        <div class="receiver_image_and_name">
                            <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                            ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                        </div>
                        <div class="message sent">
                            <img src="${url + 'storage/' + e.media[0]}" alt="img" width="90" height="90">
                        </div>
                    </div>`;

                    $('.messages').append(mediaHtml);
                } else if (e.media.length === 2) {
                    let mediaHtml = `
                    <div class="message received position-relative" style="margin-top: 50px; width: 240px; display: flex; flex-wrap: wrap">
                        <div class="receiver_image_and_name">
                            <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                            ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                        </div>`;
                    e.media.forEach(image => {
                        mediaHtml += `
                            <div class="message sent">
                                <img src="${url + 'storage/' + image}" alt="img" width="90" height="90">
                            </div>`;
                    });

                    mediaHtml += `</div>`;

                    $('.messages').append(mediaHtml);
                }
                else if (e.media.length === 4) {
                    let mediaHtml = `
                    <div class="message received position-relative" style="margin-top: 50px; width: 240px; display: flex; flex-wrap: wrap">
                        <div class="receiver_image_and_name">
                            <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                            ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                        </div>`;
                    e.media.forEach(image => {
                        mediaHtml += `
                        <div class="message sent">
                            <img src="${url + 'storage/' + image}" alt="img" width="90" height="90">
                        </div>`;
                    });

                    mediaHtml += `</div>`;

                    $('.messages').append(mediaHtml);
                }
                else {
                    e.media.forEach(item => {
                        const mediaHtml = `
                        <div class="message received position-relative" style="margin-top: 50px">
                            <div class="receiver_image_and_name">
                                <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                                ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                            </div>
                            <div class="message sent">
                                <img src="${url + 'storage/' + item}" alt="img" width="90" height="90">
                            </div>
                        </div>`;

                        $('.messages').append(mediaHtml);
                    })

                }
                
            }

            if (e.message_type === "message_with_media") {
                const message_with_media_html = `
                <div class="message received position-relative" style="margin-top: 50px">
                    <div class="receiver_image_and_name">
                        <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                        ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                    </div>
                    <div class="message sent">
                        <img src="${url + 'storage/' + e.media[0]}" alt="img" width="90" height="90">
                    </div>
                    ${e.message}
                </div>`;
                
                $('.messages').append(message_with_media_html);
            }
            $('.messages').animate({ scrollTop: $('.messages')[0].scrollHeight }, 300);
        }
        if(!parent_conservation.hasClass('active-conversation')) {
            if(e.message_count > 0) {
                parent_conservation.find('.noti_counter__conservation').text(e.message_count)
            }else if(e.message_count === 0) {
                parent_conservation.find('.noti_counter__conservation').text('')
            }
        }


        switch (e.message_type) {
            case 'message':
                showNotiIncomeMessage(e, 'send you the message')
                break;
            case 'audio':
                showNotiIncomeMessage(e, 'send you the voice message')
                break;
            case 'message':
                showNotiIncomeMessage(e, 'send you media')
                break;
            case 'message':
                showNotiIncomeMessage(e, 'send you message with media')
                break;
            default:
                break;
        }
        // toastr.success(`<strong>${e.userName}</strong>: ${e.message}`);
    })
    .listen('.notification-counter', (e) => {
        // console.log(e)
        let notiCounter = $("#notification_counter").text();
        let userData = e.user_details[0];
        switch (userData?.message_type) {
            case 'friend_request':
                showNoti(e.userId, userData);
                parseInt(notiCounter) >= 0 ? $("#notification_counter").text(parseInt(notiCounter) + 1) : $("notification_counter").text(0);
                break;

            case 'request_cancel':
                parseInt(notiCounter) > 0 ? $("#notification_counter").text(parseInt(notiCounter) - 1) : $("notification_counter").text(0);
                break;

            default:
                break;
        }
    })
    .listen('.request-accept-event', (e) => {
        console.log(e)
        let conservation_list = `
            <div class="conversation-item" id="conservation-${e.userId}" data-id="${e.userId}">
            <div>
                <div class="all-online-user online_status" id="online-user-${e.userId}"></div>
                <img src="${e.profilePicture ? 'storage/' + e.profilePicture : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="">
                <strong>${e.name} - &nbsp;&nbsp;&nbsp;<span class="user-status"></span></strong>
                <div class="btn-group " style="float: right">
                    <i class="bi bi-three-dots-vertical three-dot" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item unfriend_user" href="javascript:void(0)" data-type="unfriend" data-uid="${e.userId}">Unfriend</a></li>
                    </ul>
                </div>
                <span id="typing-2" class="timestamp d-none">Typing...</span>
            </div>
        </div>`;
        if($('.conversation-item').length === 0) {
            $('.conversation-list').html(conservation_list)
        }else{
            $('.conversation-list').prepend(conservation_list)
        }
    })
    .listen('.unfriend', (e) => {
        $('#user-id-' + e.id).html('<b>You can\'t send message because your friend unfriend you </b>');
    })
    .listen('.seen-all-message', (e) => {
        console.log(e)

        let allSentMessages = $('.messages').find('.message.sent');
        allSentMessages.find('.ticks--div').html('');
        
        let lastMessage = $('.messages').find('.message.sent:last')
        lastMessage.find('.ticks--div').html(`
            <img src="${ e?.receiverImage }" height="15px" width="15px" class="rounded-circle" alt="">
            `);
    })


    // Window.Echo.private(`typing.${userId}`)
    //     .listenForWhisper('typing', (e) => {
    //         console.log(e)
    //         console.log(`User is typing...`);
    //     })

    function showNotiIncomeMessage(e, text) {
        var audio = new Audio('/ringtone/noty.wav');
        audio.play().catch(() => {
            document.addEventListener("click", function playOnce() {
                audio.play();
                document.removeEventListener("click", playOnce);
            });
        });
        var n = new Noty({
            theme: 'sunset',
            layout: 'bottomLeft',
            type: 'info',
            timeout: 3000,
            progressBar: true,
            closeWith: ['click', 'button'],
            text: `
                  <div style="display: flex; align-items: center; gap: 10px; padding: 8px 0;">
                    <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" 
                        alt="User Image" 
                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" />

                    <div style="display: flex; flex-direction: column; flex: 1;">
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <span style="font-size: 16px; font-weight: bold;">${capitalizeFirstLetter(e.userName)}</span>
                            <p style="margin: 0; font-size: 13px; color: white;">${text}</p>
                        </div>
                        <span style="font-size: 15px; color: white; margin-top: 2px;">${e.message ?? ''}</span>
                    </div>
                </div>
                `,
        });
    
        n.show();
    }

function showNoti(userId, userData) {
    var audio = new Audio('/ringtone/noty.wav');
    audio.play().catch(() => {
        document.addEventListener("click", function playOnce() {
            audio.play();
            document.removeEventListener("click", playOnce);
        });
    });
    var n = new Noty({
        theme: 'sunset',
        layout: 'bottomLeft',
        type: 'info',
        timeout: 3000,
        progressBar: true,
        closeWith: ['click', 'button'],
        text: `
              <div style="display: flex; align-items: center;">
                <img src="${userData.image ? 'storage/' + userData.image : 'images/person.jpg'}" alt="User Image" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;" />
                <span style="font-size: 16px;">${capitalizeFirstLetter(userData.name)}</span>&nbsp;
                <span style="font-size: 13px;">${userData.message}</span>
              </div>
            `,
    });

    n.show();
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


let onlineUsers = [];


window.Echo.join('online-users')
    .here(users => {
        onlineUsers = users;
        updateOnlineStatus(onlineUsers);
    })
    .joining(user => {
        onlineUsers.push(user);
        updateOnlineStatus(onlineUsers);
    })
    .leaving(user => {
        onlineUsers = onlineUsers.filter((u) => {
            $('#last-seen-' + user.id).text('Offline')
            return u.id !== user.id
        });
        updateOnlineStatus(onlineUsers);
    });

function updateOnlineStatus(users) {
    // $('#online-users-list').empty();
    $('.all-online-user').removeClass('online_status')

    let onlineUsers = []

    users.forEach(user => {
        $('#online-user-' + user.id).addClass('online_status')
        $('#last-seen-' + user.id).text('Active now')
        onlineUsers.push(user.id)
    });
    localStorage.setItem('online-users', JSON.stringify(onlineUsers))
}

console.log(userId)
// Listen for typing events
// window.Echo.private(`typing.${userId}`)
//     .listenForWhisper('typing', function (event) {
//         console.log(`User ${event.sender_id} is typing...`);
//     })
//     .listenForWhisper('stop-typing', function (event) {
//         console.log('inside stope-typing')
//     });
