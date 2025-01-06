import Echo from 'laravel-echo';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
});

var userId = document.querySelector('meta[name="auth-id"]').getAttribute('content');
var url = document.querySelector('meta[name="url"]').getAttribute('content');
// var userName = document.querySelector('meta[name="auth-name"]').getAttribute('content');

window.Echo.private(`chat-channel.${userId}`)
    .listen('.chat-event', (e) => {

        if(e.message_type === 'message'){
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

        if(e.message_type === 'audio'){
            const mediaHtml = `
            <div class="message received position-relative" style="margin-top: 50px">
                <div class="receiver_image_and_name">
                    <img src="${e.renderImage ? 'storage/' + e.renderImage : 'images/person.jpg'}" class="rounded-circle" height="30px" width="30px" alt="receiver">
                    ${e.userName}, <small>${new Date(e.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                </div>
                <div class="message sent">
                    <audio controls>
                        <source src="${url +'storage/' + e.audioUrl}" type="audio/wav">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            </div>`;
    
            $('.messages').append(mediaHtml);
        }
        
        if(e.message_type === 'media'){
            if(e.media.length === 1){
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
            }else if(e.media.length === 2){
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
            else if(e.media.length === 4){
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
            else{
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

        if(e.message_type === "message_with_media"){
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
        // toastr.success(`<strong>${e.userName}</strong>: ${e.message}`);
    });

    // window.Echo.private(`typing-channel.${userId}`)
    // .listen('.typing-event', (e) => {
    //     console.log('is-typing')
    //     $('.message-input p').removeClass('d-none').text(`${e.senderName} is typing...`);

    //     setTimeout(function() {
    //         $('.message-input p').addClass('d-none');
    //     }, 2000);
    // });

    let onlineUsers = [];


    window.Echo.join('online-users')
        .here(users => {
            console.log('user already ')
            onlineUsers = users;
            updateOnlineStatus(onlineUsers); 
        })
        .joining(user => {
            console.log('user just join')
            onlineUsers.push(user);
            updateOnlineStatus(onlineUsers);
        })
        .leaving(user => {
            console.log('user leave')
            onlineUsers = onlineUsers.filter(u => u.id !== user.id);
            updateOnlineStatus(onlineUsers);
        });

        function updateOnlineStatus(users) {
            // $('#online-users-list').empty();
            $('.all-online-user').removeClass('online_status')

            let onlineUsers = []
            
            users.forEach(user => {
                $('#online-user-' + user.id).addClass('online_status')
                onlineUsers.push(user.id)
            });
            localStorage.setItem('online-users',JSON.stringify(onlineUsers))
            console.log(users)
        }


// for typing status 
// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
// });


$('body').on('keydown', '.message-value',function() {
    let receiverId = $('.message-input .message-value').attr('data-receiver-id')
    console.log(receiverId, userId)
    window.Echo.private(`typing-status.${receiverId}`).whisper('typing', {
        userId: userId
    });
  
});

window.Echo.private(`typing-status.${userId}`)
    .listenForWhisper('typing', (e) => {
        console.log("Whisper received for typing event from user:", e.userId);
        showTypingStatus(e.userId);
    });

function showTypingStatus(userId) {

    console.log('inside ',userId)
    // $(`#typing-status-${userId}`).removeClass('d-none');
    // setTimeout(() => {
    //     $(`#typing-status-${userId}`).addClass('d-none');
    // }, 2000);
}

