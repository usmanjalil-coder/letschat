
import { ZIM } from "zego-zim-web";
import { ZegoUIKitPrebuilt } from '@zegocloud/zego-uikit-prebuilt';

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
        const appID = 1387905384;
        const serverSecret = "2e05b373707491eea63e195d1755ec47";
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
        console.log('receiver nam' ,receivername)
        console.log('receiver id' ,receiverId)
        console.log('user_id' , user_ID)
        // initializeZego(false, receiverId)
        const targetUser = {
            userID: receiverId, 
            userName: receivername.toLowerCase().replace(/\s+/g, '')
        };
        invite(targetUser);
    })

    $('body').on('click','#start-video-call', function(){
        let receiverId =  $(this).data('id')
        initializeZego(true, receiverId)
    })

    const appID = 43410637; 
    const serverSecret = "c72daa980a6bb015e82773f39575c3f7";

    // const zim = new ZIM({
    //     appID: appID,
    //     appSign: serverSecret,
    // });

    // Invite function
    function invite(targetUser) {
        const user_id = user_ID; 
        console.log('invite functiion ---> ',user_id)
        const userName = username.toLowerCase().replace(/\s+/g, '') + user_ID;
        const TOKEN = ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, null, user_id, userName);
    
        const zp = ZegoUIKitPrebuilt.create(TOKEN);
        zp.addPlugins({ ZIM });
    
        zp.sendCallInvitation({
            callees: [targetUser], // Use the dynamic targetUser here
            callType: ZegoUIKitPrebuilt.InvitationTypeVideoCall,
            timeout: 60, 
        }).then((res) => {
            console.warn(res);
        })
        .catch((err) => {
            console.warn('this is error ---->',err); 
        });
    }

    // Trigger the invite function when a button is clicked
    // $('body').on('click','#invite-button', function(){
    //     invite(); // Initiates the invitation
    // });
});


