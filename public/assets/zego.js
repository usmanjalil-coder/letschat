$(document).ready(function () {
    // Helper function to extract URL parameters
    function getUrlParams(url) {
        let urlStr = url.split('?')[1];
        const urlSearchParams = new URLSearchParams(urlStr);
        const result = Object.fromEntries(urlSearchParams.entries());
        return result;
    }

    // Generate a Token by calling a method.
    // @param 1: appID, @param 2: serverSecret, @param 3: Room ID, @param 4: User ID, @param 5: Username
    var user_ID = document.querySelector('meta[name="auth-id"]').getAttribute('content');
    var username = document.querySelector('meta[name="auth-name"]').getAttribute('content');

    function initializeZego(isVedioCall, receiverId){
        const roomID = Math.floor(Math.random() * 10000) + '-' + receiverId;
        const userID = user_ID;
        const userName = username.toLowerCase().replace(/\s+/g, '') + user_ID;
        const appID = 43410637;
        const serverSecret = "c72daa980a6bb015e82773f39575c3f7";
        const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, roomID, userID, userName);
    
        const zp = ZegoUIKitPrebuilt.create(kitToken);
        zp.joinRoom({
            container: document.querySelector("#root"),
            sharedLinks: [{
                name: 'Personal link',
                url: window.location.protocol + '//' + window.location.host  + window.location.pathname + '?roomID=' + roomID,
            }],
            scenario: {
                mode: isVedioCall ? ZegoUIKitPrebuilt.VideoConference : ZegoUIKitPrebuilt.AudioConference,
            },
            onReturnToHomeScreenClicked: () => {
                console.log('okay')
            },
                
            turnOnMicrophoneWhenJoining: true,
            turnOnCameraWhenJoining: isVedioCall,
            showMyCameraToggleButton: true,
            showMyMicrophoneToggleButton: true,
            showAudioVideoSettingsButton: true,
            showScreenSharingButton: false,
            showTextChat: false,
            showUserList: true,
            maxUsers: 2,
            layout: "Auto",
            showLayoutButton: false,
        });
    }

    $('body').on('click','#start-audio-call', function(){
        let receiverId =  $(this).data('id')
        let receivername =  $(this).data('name')

        const targetUser = {
            userID: receiverId.toString(), 
            userName: receivername.replace(/\b\w/g, char => char.toUpperCase())
        };
        invite(targetUser, ZegoUIKitPrebuilt.InvitationTypeVoiceCall);
    })

    $('body').on('click','#start-video-call', function(){
        let receiverId =  $(this).data('id')
        let receivername =  $(this).data('name')
        // initializeZego(true, receiverId)
        const targetUser = {
            userID: receiverId.toString(), 
            userName: receivername.replace(/\b\w/g, char => char.toUpperCase())
        };
        invite(targetUser, ZegoUIKitPrebuilt.InvitationTypeVideoCall);
    })

    let zp; 

    function init() {
        const appID = 43410637; 
        const serverSecret = "c72daa980a6bb015e82773f39575c3f7";
    
        if (typeof user_ID === 'undefined' || typeof username === 'undefined') {
            console.error('user_ID or username is not defined');
            return;
        }
    
        const user_id = String(user_ID);
        const userName = username.replace(/\b\w/g, char => char.toUpperCase());
        const TOKEN = ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, null, user_id , userName);
    
        zp = ZegoUIKitPrebuilt.create(TOKEN);
        zp.addPlugins({ ZIM });
    }
    

    function invite(targetUser, callType) {
        if (!zp) {
            console.error("Zego SDK is not initialized. Please call init() first.");
            return;
        }
        zp.setCallInvitationConfig({
            ringtoneConfig: {
                incomingCallUrl: '/ringtone/incoming_call.mp3',
                outgoingCallUrl: '/ringtone/outgoing_call.mp3'
            }
        });
    
        zp.sendCallInvitation({
            callees: [targetUser],
            callType: callType,
            timeout: 60, 
        }).then((res) => {

                if (res && res.errorInvitees && res.errorInvitees.length) {
                    alert('The user does not exist or is offline.');
                } else {
                    console.warn(res);
                }
        })
        .catch((err) => {
            console.log(JSON.parse(err)); 
        });
    }

    init();
});