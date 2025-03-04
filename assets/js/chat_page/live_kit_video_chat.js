var live_kit_video_client = null;
var isVideoChatActive = false;
video_chat_available = true;
var localTracks = [];
var localParticipantContainer;
var audio_only_chat = false;


var videochat_GridContainer = $('#video-chat-grid');
var video_chat_formData = {
    add: 'video_chat'
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

    if ($(".main .chatbox").attr('group_id') !== undefined) {
        video_chat_formData = {
            add: 'video_chat',
            group_id: $(".main .chatbox").attr('group_id')
        };
    } else if ($(".main .chatbox").attr('user_id') !== undefined) {
        video_chat_formData = {
            add: 'video_chat',
            user_id: $(".main .chatbox").attr('user_id')
        };
    } else {
        console.log('Error : Failed to fetch conversation info');
        return;
    }

    if ($(this).attr('audio_only') !== undefined && $(this).attr('audio_only') === 'yes') {
        video_chat_formData['audio_only'] = true;
        $('.main .video_chat_container>.icons>span.toggle_video_call_camera').hide();
        $('.main .video_chat_container>.icons>span.toggle_screen_share').hide();
    } else {
        $('.main .video_chat_container>.icons>span.toggle_video_call_camera').show();
        $('.main .video_chat_container>.icons>span.toggle_screen_share').show();
    }

    if (user_csrf_token !== null) {
        video_chat_formData['csrf_token'] = user_csrf_token;
    }

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        video_chat_formData["login_session_id"] = user_login_session_id;
        video_chat_formData["access_code"] = user_access_code;
        video_chat_formData["session_time_stamp"] = user_session_time_stamp;
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

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_camera", function(e) {

    if (!live_kit_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    localTracks.forEach(function (track) {
        if (track.kind === 'video') {

            if (track.isMuted) {
                track.unmute();
            } else {
                track.mute();
            }
        }
    });


    if ($(this).find('.cam_disabled').hasClass('d-none')) {
        live_kit_video_client.localParticipant.setCameraEnabled(false);
    } else {
        live_kit_video_client.localParticipant.setCameraEnabled(true);
    }

    if ($(this).find('.cam_disabled').hasClass('d-none')) {
        $(this).find('.cam_not_disabled').addClass('d-none');
        $(this).find('.cam_disabled').removeClass('d-none');
    } else {
        $(this).find('.cam_disabled').addClass('d-none');
        $(this).find('.cam_not_disabled').removeClass('d-none');
    }

});

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_mic", function(e) {

    if (!live_kit_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    localTracks.forEach(function (track) {
        if (track.kind === 'audio') {

            if (track.isMuted) {
                track.unmute();
            } else {
                track.mute();
            }
        }
    });

    if ($(this).find('.mic_muted').hasClass('d-none')) {
        live_kit_video_client.localParticipant.setMicrophoneEnabled(false);
    } else {
        live_kit_video_client.localParticipant.setMicrophoneEnabled(true);
    }

    if ($(this).find('.mic_muted').hasClass('d-none')) {
        $(this).find('.mic_not_muted').addClass('d-none');
        $(this).find('.mic_muted').removeClass('d-none');
    } else {
        $(this).find('.mic_muted').addClass('d-none');
        $(this).find('.mic_not_muted').removeClass('d-none');
    }

});

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_screen_share", function(e) {

    if (!live_kit_video_client) {
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
        $('.main .video_chat_container>.icons>span.toggle_video_call_camera').hide();
        $(this).find('.stop_screen_share ').removeClass('d-none');
    }

});


function exit_video_chat() {
    $('.main .middle > .video_chat_interface').addClass('d-none');
    leaveChannel();
    stop_update_video_chat_status();
}

function leaveChannel() {

    if (!isVideoChatActive) {
        return;
    }

    if (live_kit_video_client) {
        live_kit_video_client.disconnect();
        live_kit_video_client = null;
    }
    localTracks.forEach(function (track) {
        track.stop();
    });
    localTracks = [];
    if (localParticipantContainer !== undefined && localParticipantContainer) {
        localParticipantContainer.remove();
    }

    videochat_GridContainer.innerHTML = '';

    isVideoChatActive = false;
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



function add_livekit_participant(participant, track) {

    if (track.kind === 'video' || track.kind === 'audio') {

        if ($('.participant-container[user_sid="'+participant.sid+'"]').length > 0) {

            if (track.kind === 'video' && $('.participant-container[user_sid="'+participant.sid+'"] > video').length > 0) {
                $('.participant-container[user_sid="'+participant.sid+'"]').find('video').remove();
            }
            var mediaElement = track.attach();
            $('.participant-container[user_sid="'+participant.sid+'"]').append(mediaElement);
        } else {
            var participantContainer = $('<div></div>').addClass('participant-container').attr("user_sid", participant.sid);

            var mediaElement = track.attach();
            participantContainer.append(mediaElement);

            videochat_GridContainer.append(participantContainer);
            arrange_video_chat_grid();

            fetch_user_info(participant.identity).then(function (userData) {
                var participantUsername = userData.username;

                var group_attribute = '';

                if ($(".main .chatbox").attr('group_id') !== undefined) {
                    group_attribute = 'data-group_identifier="'+$(".main .chatbox").attr('group_id')+'"';
                }

                $('.participant-container[user_sid="'+participant.sid+'"]').append('<span '+group_attribute+' class="participant_name get_info" user_id="'+participant.identity+'">@' + participantUsername + '</span>');

                if (audio_only_chat) {
                    $('.participant-container[user_sid="'+participant.sid+'"]').append('<span class="participant_img"><img src="'+userData.image+'"/></span>');
                }
            }).catch(function (error) {
                console.log(error);
                $('.participant-container[user_sid="'+participant.sid+'"]').append('<span class="participant_name">@'+participant.identity+'</span>');
            });

        }
    }
}


async function share_device_screen() {

    const screen_share_tracks = await live_kit_video_client.localParticipant.createScreenTracks({
        audio: true,
    });

    screen_share_tracks.forEach((track) => {
        live_kit_video_client.localParticipant.publishTrack(track);
    });


    localParticipantContainer.find('video').remove();
    const localVideoElement = screen_share_tracks.find(track => track.kind === 'video').attach();
    localParticipantContainer.append(localVideoElement);
}

async function stop_share_device_screen() {

    live_kit_video_client.localParticipant.setScreenShareEnabled(false);

    const cam_permissionGranted = await checkWebcamAndPermission();
    const mic_permissionGranted = await checkMicrophonePermission();

    const local_video_tracks = await live_kit_video_client.localParticipant.createTracks({
        audio: mic_permissionGranted, video: cam_permissionGranted
    });

    local_video_tracks.forEach((track) => {
        live_kit_video_client.localParticipant.publishTrack(track);
    });

    localParticipantContainer.find('video').remove();

    if (cam_permissionGranted) {
        const localVideoElement = local_video_tracks.find(track => track.kind === 'video').attach();
        localParticipantContainer.append(localVideoElement);
    }
}


async function create_video_chat() {

    const cam_permissionGranted = await checkWebcamAndPermission();
    const mic_permissionGranted = await checkMicrophonePermission();
    let video_cam_enabled = false;
    audio_only_chat = false;

    $.ajax({
        type: "POST",
        url: api_request_url,
        data: video_chat_formData,
        dataType: "json",
        success: function(data) {},
        error: function(jqXHR, textStatus, errorThrown) {}
    }).done(function (data) {

        if (data.alert_message !== undefined) {
            alert(data.alert_message);
            exit_video_chat();
            return;
        }

        var token = data.token;
        var roomName = data.channel;
        var live_kit_url = data.live_kit_url;

        live_kit_video_client = new LivekitClient.Room({
            adaptiveStream: true,
            autoSubscribe: true,
        });

        if (data.audio_only !== undefined) {
            video_cam_enabled = false;
            audio_only_chat = true;
        } else {
            video_cam_enabled = cam_permissionGranted;
        }

        live_kit_video_client.prepareConnection(live_kit_url, token).then(function () {

            live_kit_video_client.connect(live_kit_url, token).then(function () {
                console.log('Connected to Room: ' + roomName);
                update_video_chat_status();

                isVideoChatActive = true;

                if (call_notification_timeout_id) {
                    clearTimeout(call_notification_timeout_id);
                }

                $('.call_notification').attr('current_call_id', 0);

                if ($('.main .middle > .video_chat_interface').hasClass('d-none')) {
                    exit_video_chat();
                    return;
                }


                live_kit_video_client.remoteParticipants.forEach(participant => {
                    participant.trackPublications.forEach(publication => {
                        if (publication.track) {
                            add_livekit_participant(participant, publication.track);
                        }
                    });

                });

                live_kit_video_client.on(LivekitClient.RoomEvent.TrackSubscribed, (track, publication, participant) => {
                    add_livekit_participant(participant, track);
                });

                if ($('.participant-container.local-participant-window').length > 0) {
                    return;
                }

                LivekitClient.createLocalTracks({
                    audio: mic_permissionGranted, video: video_cam_enabled
                }).then(function (tracks) {
                    localTracks = tracks;

                    localParticipantContainer = $('<div></div>').addClass('participant-container');

                    if (cam_permissionGranted && video_cam_enabled) {
                        const localVideoElement = tracks.find(track => track.kind === 'video').attach();
                        localParticipantContainer.append(localVideoElement);
                    } else if (audio_only_chat) {
                        localParticipantContainer.append('<span class="participant_img"><img src="'+$('.logged_in_user_avatar').attr('src')+'"/></span>');
                    }
                    var group_attribute = '';

                    if ($(".main .chatbox").attr('group_id') !== undefined) {
                        group_attribute = 'data-group_identifier="'+$(".main .chatbox").attr('group_id')+'"';
                    }

                    localParticipantContainer.addClass('identity').addClass('You local-participant-window');
                    localParticipantContainer.append('<span '+group_attribute+' class="participant_name get_info" user_id="'+$('.logged_in_user_id').text()+'">You</span>');

                    if (!video_cam_enabled) {
                        live_kit_video_client.localParticipant.setMicrophoneEnabled(true);
                        live_kit_video_client.localParticipant.setCameraEnabled(false);
                    } else {
                        live_kit_video_client.localParticipant.enableCameraAndMicrophone();
                    }

                    videochat_GridContainer.append(localParticipantContainer);
                    arrange_video_chat_grid();


                    live_kit_video_client.on('participantDisconnected', function (disconnectedParticipant) {
                        console.log('Participant ' + disconnectedParticipant.identity + ' has disconnected');
                        $('.participant-container').each(function () {
                            if ($(this).attr("user_sid") !== undefined && $(this).attr("user_sid") === disconnectedParticipant.sid) {
                                $(this).remove();
                            }
                        });
                    });
                }).catch(function (error) {
                    exit_video_chat();
                    console.error('Error accessing local media:', error);
                });
            }).catch(function (error) {
                console.error('Error connecting to room:', error);
            });
        }).catch(function (error) {
            exit_video_chat();
            console.error('Error connecting to Room:', error);
        });
    })
    .fail(function (error) {
        exit_video_chat();
        console.error('Error fetching token:', error);
    });

}