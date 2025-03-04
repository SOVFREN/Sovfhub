let rec_mediaRecorder;
let rec_audioChunks;
let rec_mediaStream;
let rec_audio_blob;
let rec_send_to_server = false;
let rec_mimeType = 'audio/wav';
let rec_timerInterval;
let rec_startTime;
let recording_timeout_id;

$(document).ready(async function () {

    let rec_startRecording = async () => {
        try {
            rec_mediaStream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });

            rec_mediaRecorder = new MediaRecorder(rec_mediaStream);
            rec_audioChunks = [];

            rec_mediaRecorder.ondataavailable = event => {
                if (event.data.size > 0) {
                    rec_audioChunks.push(event.data);
                }
            };

            rec_mediaRecorder.onstop = () => {

                rec_mimeType = rec_mediaRecorder.mimeType;
                let rec_fileName = getFileName('webm');

                rec_mediaStream.getTracks().forEach(track => track.stop());

                if (rec_mimeType.includes('audio/webm')) {
                    rec_mimeType = 'audio/webm';
                } else if (rec_mimeType.includes('audio/wav')) {
                    rec_mimeType = 'audio/wav';
                    rec_fileName = getFileName('wav');
                }

                rec_audio_blob = new Blob(rec_audioChunks, {
                    type: rec_mimeType
                });

                $(".main .chatbox > .footer > .editor .audio_recorder_box > div > .timestamp").text('00:00');
                $(".main .chatbox > .footer > .editor .audio_recorder_box").addClass('d-none');

                if (rec_send_to_server) {
                    const audio_message = new File([rec_audio_blob], rec_fileName);

                    const content = {
                        'audio_message': audio_message,
                    };

                    send_message(content);
                }
            };

            rec_mediaRecorder.start();
            rec_startTime = performance.now();

            $(".main .chatbox > .footer > .editor .audio_recorder_box > div > .timestamp").replace_text(language_string('recording'));

            rec_updateTimer();
            rec_timerInterval = setInterval(rec_updateTimer, 1000);

            var maxRecordingDuration = 20 * 60 * 1000;

            if (recording_timeout_id) {
                clearTimeout(recording_timeout_id);
            }

            recording_timeout_id = setTimeout(function() {
                $('.send_audio_message').trigger('click');
            }, maxRecordingDuration);

            $(".main .chatbox > .footer > .editor .audio_recorder_box").removeClass('d-none');
        } catch (error) {
            console.error('Error accessing microphone:', error);
            alert('Microphone access is required to record audio. Please grant permission.');
        }
    };

    const rec_updateTimer = () => {
        const elapsedTimeInSeconds = Math.floor((performance.now() - rec_startTime) / 1000);
        $(".main .chatbox > .footer > .editor .audio_recorder_box > div > .timestamp").text(calculateTimeDuration(elapsedTimeInSeconds));
    };

    const rec_stopRecording = () => {
        if (rec_mediaRecorder && rec_mediaRecorder.state === 'recording') {
            clearInterval(rec_timerInterval);
            rec_mediaRecorder.stop();
        }
    };


    function calculateTimeDuration(secs) {
        var hr = Math.floor(secs / 3600);
        var min = Math.floor((secs - (hr * 3600)) / 60);
        var sec = Math.floor(secs - (hr * 3600) - (min * 60));

        if (min < 10) {
            min = "0" + min;
        }

        if (sec < 10) {
            sec = "0" + sec;
        }

        if (hr <= 0) {
            return min + ':' + sec;
        }

        return hr + ':' + min + ':' + sec;
    }


    function getFileName(fileExtension) {
        const d = new Date();
        const year = d.getUTCFullYear();
        const month = d.getUTCMonth();
        const date = d.getUTCDate();
        return `Audio_Message-${year}${month}${date}-${getRandomString()}.${fileExtension}`;
    }

    function getRandomString() {
        if (window.crypto && window.crypto.getRandomValues && navigator.userAgent.indexOf('Safari') === -1) {
            const a = window.crypto.getRandomValues(new Uint32Array(3));
            let token = '';
            for (const value of a) {
                token += value.toString(36);
            }
            return token;
        } else {
            return (Math.random() * new Date().getTime()).toString(36).replace(/\./g, '');
        }
    }

    $('body').on('click', '.record_audio_message', function () {
        const btnStartRecording = $(this);

        if ($('.main .chatbox > .footer > .editor .audio_recorder_box').hasClass('d-none')) {
            rec_send_to_server = false;
            rec_startRecording();
        }
    });

    $('body').on('click', '.send_audio_message', function () {

        if (recording_timeout_id) {
            clearTimeout(recording_timeout_id);
        }

        rec_send_to_server = true;
        rec_stopRecording();
    });

    $('body').on('click', '.cancel_recording', function () {

        if (recording_timeout_id) {
            clearTimeout(recording_timeout_id);
        }

        rec_send_to_server = false;
        rec_stopRecording();
    });

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia && window.MediaRecorder) {
        console.log("MediaRecorder API is supported in this browser.");
    } else {
        console.log("MediaRecorder API is not supported in this browser.");
    }
});