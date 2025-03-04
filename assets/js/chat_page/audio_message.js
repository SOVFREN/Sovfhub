var useMediaRecorder_api = false;

$(document).ready(function() {

    var audio_recorder_jsFileUrl = baseurl+'assets/js/chat_page/audio_message_record_rtc.js';

    if (system_variable('ffmpeg') === 'enable') {
        useMediaRecorder_api = true;
    }

    if (useMediaRecorder_api) {
        audio_recorder_jsFileUrl = baseurl+'assets/js/chat_page/audio_message_media_recorder.js';
    }

    $.getScript(audio_recorder_jsFileUrl, function() {
        console.log('Loaded Audio Recorder Files');
    });
});