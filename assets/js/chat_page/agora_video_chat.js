var agora_video_client = null;
var isVideoChatActive = false;
video_chat_available = true;

var audio_only_chat = false;


var videochat_GridContainer = document.getElementById('video-chat-grid');
var audiochat_GridContainer = document.getElementById('audio-chat-grid');
var agora_remoteStreams = {};
var video_chat_formData = new FormData();

var localTracks = {
    videoTrack: null,
    audioTrack: null
};
var previousTracks = {
    videoTrack: null,
    audioTrack: null
};
var remoteUsers = {};
var options = {
    appid: null,
    channel: null,
    uid: null,
    token: null
};

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_chat_window", function(e) {
    $('.main .middle > .video_chat_interface').toggleClass('show_chat_window');
});

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .leave_video_call", function(e) {
    exit_video_chat();
});

$("body").on('mouseenter', '.main .middle > .video_chat_interface', function(e) {
    $(".main .middle>.video_chat_interface>.video_chat_container>.icons").fadeIn();
});

$("body").on('mouseleave', '.main .middle > .video_chat_interface', function(e) {
    $(".main .middle>.video_chat_interface>.video_chat_container>.icons").fadeOut();
});

$("body").on("click", ".main .chatbox .join_video_call", function(e) {

    video_chat_formData = new FormData();
    video_chat_formData.append('add', 'video_chat');

    if ($(".main .chatbox").attr('group_id') !== undefined) {
        video_chat_formData.append('group_id', $(".main .chatbox").attr('group_id'));
    } else if ($(".main .chatbox").attr('user_id') !== undefined) {
        video_chat_formData.append('user_id', $(".main .chatbox").attr('user_id'));
    } else {
        console.log('Error : Failed to fetch conversation info');
        return;
    }

    if ($(this).attr('audio_only') !== undefined && $(this).attr('audio_only') === 'yes') {
        video_chat_formData.append('audio_only', true);
        audio_only_chat = true;
        $('.main .video_chat_container>.icons>span.toggle_video_call_camera').hide();
        $('.main .video_chat_container>.icons>span.toggle_screen_share').hide();
    } else {
        audio_only_chat = false;
        $('.main .video_chat_container>.icons>span.toggle_video_call_camera').show();
        $('.main .video_chat_container>.icons>span.toggle_screen_share').show();
    }

    if (user_csrf_token !== null) {
        video_chat_formData.append('csrf_token', user_csrf_token);
    }

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        video_chat_formData.append('login_session_id', user_login_session_id);
        video_chat_formData.append('access_code', user_access_code);
        video_chat_formData.append('session_time_stamp', user_session_time_stamp);
    }

    $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').html('');
    $('.main .middle > .video_chat_interface').removeClass('d-none');
    $('.call_notification').addClass('d-none');


    if (isVideoChatActive) {
        exit_video_chat();
    } else {

        if ($('.main .chatbox').attr('user_id') !== undefined) {
            current_video_caller_id = $('.main .chatbox').attr('user_id');
        }

        initilazing_video_chat();
        create_video_chat();
    }
});


$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_mic", function(e) {

    if (!agora_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    if ($(this).find('.mic_muted').hasClass('d-none')) {

        localTracks.audioTrack.setMuted(true);
        $(this).find('.mic_not_muted').addClass('d-none');
        $(this).find('.mic_muted').removeClass('d-none');

    } else {
        localTracks.audioTrack.setMuted(false);
        $(this).find('.mic_muted').addClass('d-none');
        $(this).find('.mic_not_muted').removeClass('d-none');

    }

});


$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_screen_share", function(e) {

    if (!agora_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    if ($(this).find('.share_user_screen').hasClass('d-none')) {
        stop_share_device_screen();
        $(this).find('.stop_screen_share').addClass('d-none');
        $(this).find('.share_user_screen').removeClass('d-none');
        $('.main .video_chat_container>.icons>span.toggle_video_call_camera').show();
    } else {
        share_device_screen();
        $(this).find('.share_user_screen').addClass('d-none');
        $(this).find('.stop_screen_share ').removeClass('d-none');
        $('.main .video_chat_container>.icons>span.toggle_video_call_camera').hide();
    }

});


$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_camera", function(e) {

    if (!agora_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    if ($(this).find('.cam_disabled').hasClass('d-none')) {

        if (localTracks.videoTrack) {
            localTracks.videoTrack.setEnabled(false);
        }

        $(this).find('.cam_not_disabled').addClass('d-none');
        $(this).find('.cam_disabled').removeClass('d-none');
    } else {

        if (localTracks.videoTrack) {
            localTracks.videoTrack.setEnabled(true);
        }

        $(this).find('.cam_disabled').addClass('d-none');
        $(this).find('.cam_not_disabled').removeClass('d-none');
    }

});


agora_video_client = AgoraRTC.createClient({
    mode: "rtc", codec: "vp8"
});
agora_video_client.on("user-published", handleUserPublished);
agora_video_client.on("user-unpublished", handleUserUnpublished);

var create_video_chat = async () => {
    try {

        if (isVideoChatActive) {
            console.log('Video chat is already active. Leaving current session.');
            leaveChannel();
        }

        await joinChannel();
        console.log('AgoraRTC client initialized');
    } catch (error) {
        console.log(error);
    }
};

var exit_video_chat = async () => {
    $('.main .middle > .video_chat_interface').addClass('d-none');
    leaveChannel();
    stop_update_video_chat_status();
};

async function stop_share_device_screen() {

    try {

        if (localTracks.screenTrack) {
            await agora_video_client.unpublish(localTracks.screenTrack);
            localTracks.screenTrack.stop();
            localTracks.screenTrack.close();
            localTracks.screenTrack = null;
        }


        var localParticipantWindow = document.querySelector('.video-window.local-participant-window');

        if (localTracks.videoTrack) {
            localTracks.videoTrack.play(localParticipantWindow);
        }

        if (localTracks.audioTrack && !localTracks.videoTrack) {
            await agora_video_client.publish([localTracks.audioTrack]);
        } else if (localTracks.videoTrack) {
            await agora_video_client.publish([localTracks.videoTrack]);
        }

    } catch (error) {
        console.error("Error sharing screen: " + error);
    }
}

async function share_device_screen() {
    try {
        const screenTrack = await AgoraRTC.createScreenVideoTrack({
            audio: 'auto',
        });

        if (isVideoChatActive) {

            if (localTracks.videoTrack) {
                await agora_video_client.unpublish(localTracks.videoTrack);
                localTracks.videoTrack.stop();
            }


            var localParticipantWindow = document.querySelector('.video-window.local-participant-window');

            localTracks.screenTrack = screenTrack;
            localTracks.screenTrack.play(localParticipantWindow);

            await agora_video_client.publish(localTracks.screenTrack);
        }

        console.log("Screen sharing started");
    } catch (error) {
        console.error("Error sharing screen: " + error);
    }
}

async function checkWebcamAndPermission() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const hasWebcam = devices.some(device => device.kind === 'videoinput');

        if (!hasWebcam) {
            return false;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            stream.getTracks().forEach(track => track.stop());
            return true;
        } catch (error) {
            return false;
        }
    } catch (error) {
        return false;
    }
}

async function checkMicrophonePermission() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const hasMicrophone = devices.some(device => device.kind === 'audioinput');

        if (!hasMicrophone) {
            return false;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });

            stream.getTracks().forEach(track => track.stop());
            return true;
        } catch (error) {
            return false;
        }
    } catch (error) {
        return false;
    }
}


async function joinChannel() {

    var response = await fetch(api_request_url, {
        method: 'POST',
        body: video_chat_formData,
    });

    if (!response.ok) {
        throw new Error('Failed to fetch token from the server');
    }

    var data = await response.json();

    if (!data || typeof data !== 'object') {
        exit_video_chat();
        console.log('Invalid JSON data');
        return;
    } else if (data.alert_message !== undefined) {
        alert(data.alert_message);
        exit_video_chat();
        return;
    } else if (!data.channel) {
        exit_video_chat();
        console.log('Channel property is missing in JSON data');
        return;
    }

    const cam_permissionGranted = await checkWebcamAndPermission();
    const mic_permissionGranted = await checkMicrophonePermission();


    if ($('.video-window.local-participant-window').length > 0) {
        return;
    }

    options = {
        appid: data.app_id,
        channel: data.channel,
        uid: null,
        token: data.token
    };

    let videoTrack_create = cam_permissionGranted ? await AgoraRTC.createCameraVideoTrack(): null;
    let audioTrack_create = mic_permissionGranted ? await AgoraRTC.createMicrophoneAudioTrack(): null;

    if (audio_only_chat) {
        videoTrack_create = null;
    }

    [options.uid, localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
        agora_video_client.join(options.appid, options.channel, options.token || null, data.uid),
        audioTrack_create,
        videoTrack_create
    ]);


    if (call_notification_timeout_id) {
        clearTimeout(call_notification_timeout_id);
    }

    $('.call_notification').attr('current_call_id', 0);

    isVideoChatActive = true;
    if ($('.main .middle > .video_chat_interface').hasClass('d-none')) {
        exit_video_chat();
        return;
    }

    var localVideoContainer = document.getElementById('video-chat-grid');
    var localVideoElement = document.createElement('div');
    localVideoElement.className = 'video-window local-participant-window';

    var participantName = document.createElement('span');
    participantName.className = 'participant_name';
    participantName.textContent = 'You';

    localVideoElement.appendChild(participantName);

    localVideoContainer.appendChild(localVideoElement);

    $('.local-participant-window').find('.participant_name').addClass('get_info');
    $('.local-participant-window').find('.participant_name').attr('user_id', $('.logged_in_user_id').text());

    if ($(".main .chatbox").attr('group_id') !== undefined) {
        $('.local-participant-window').find('.participant_name').attr('data-group_identifier', $(".main .chatbox").attr('group_id'));
    }

    if (audio_only_chat) {
        $('.local-participant-window').append('<span class="participant_img"><img src="'+$('.logged_in_user_avatar').attr('src')+'"/></span>');
    }

    if (localTracks.videoTrack) {
        localTracks.videoTrack.play(localVideoElement);
    }

    if (localTracks.videoTrack && localTracks.audioTrack) {
        await agora_video_client.publish(Object.values(localTracks));
    } else if (localTracks.audioTrack) {
        await agora_video_client.publish([localTracks.audioTrack]);
    } else if (localTracks.videoTrack) {
        await agora_video_client.publish([localTracks.videoTrack]);
    }

    console.log("publish success");

    arrange_video_chat_grid();
    update_video_chat_status();
}

async function leaveChannel() {
    if (!agora_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    for (trackName in localTracks) {
        var track = localTracks[trackName];
        if (track) {
            track.stop();
            track.close();
            localTracks[trackName] = undefined;
        }
    }

    remoteUsers = {};
    await agora_video_client.leave();

    var agoraVideoContainer = document.getElementById('video-chat-grid');
    agoraVideoContainer.innerHTML = '';

    isVideoChatActive = false;

    console.log("client leaves channel success");
}

async function videoChat_channel_subscribe(user, mediaType) {
    const uid = user.uid;
    var unique_id_hash = unicodeHash(uid);
    await agora_video_client.subscribe(user, mediaType);

    if (mediaType === 'video' || mediaType === 'audio') {
        const player_id = `player-${unique_id_hash}`;

        fetch_user_info(uid).then(function (userData) {
            var participantUsername = userData.username;

            var group_attribute = '';

            if ($(".main .chatbox").attr('group_id') !== undefined) {
                group_attribute = 'data-group_identifier="'+$(".main .chatbox").attr('group_id')+'"';
            }

            let agora_player = $(`<div class="video-window player" id="${player_id}"><span ${group_attribute} class="participant_name get_info" user_id="${uid}">@${participantUsername}</span></div>`);

            if (audio_only_chat) {
                agora_player = $(`<div class="video-window player" id="${player_id}"><span class="participant_name">@${participantUsername}</span><span class="participant_img"><img src="${userData.image}"/></span></div>`);
            }

            if (!$(`#${player_id}`).length) {
                $("#video-chat-grid").append(agora_player);
            }


            if (mediaType === 'video') {
                user.videoTrack.play(`player-${unique_id_hash}`);
            }

            if (mediaType === 'audio') {
                user.audioTrack.play();
            }


        }).catch(function (error) {
            console.log(error);
            let agora_player = $(`<div class="video-window player" id="${player_id}"><span class="participant_name">@${uid}</span></div>`);


            if (!$(`#${player_id}`).length) {
                $("#video-chat-grid").append(agora_player);
            }


            if (mediaType === 'video') {
                user.videoTrack.play(`player-${unique_id_hash}`);
            }

            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
        });


    }
    arrange_video_chat_grid();
}

function handleUserPublished(user, mediaType) {
    const id = user.uid;
    remoteUsers[id] = user;
    videoChat_channel_subscribe(user, mediaType);
}

function handleUserUnpublished(user, mediaType) {
    const id = user.uid;
    var unique_id_hash = unicodeHash(id);

    if (mediaType === 'video' || mediaType === 'audio') {
        delete remoteUsers[id];
    }
    $(`#player-${unique_id_hash}`).remove();
}