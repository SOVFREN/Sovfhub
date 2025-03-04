var video_preview = null;
var group_header_contents = null;
var load_group_header_request = null;
var video_chat_available = false;
var videoChatStatusUpdateTimeoutId;
var videoChatStatusUpdateRequest;
var call_notification_timeout_id;

$("body").on('click', '.load_page', function(e) {

    if (!$(this).hasClass('processing')) {

        $(this).addClass('processing');
        open_column('second');

        var browser_title = default_meta_title;
        var browser_address_bar = baseurl;
        var element = $(this);

        if ($(this).attr('loader') !== undefined) {
            $($(this).attr('loader')).show();
        }

        $('.main .middle > .content > div').addClass('d-none');
        $('.main .middle > .content > .custom_page').removeClass('d-none');
        $('.main .middle > .content > .custom_page > .page_content').hide();
        $('.main .middle > .content > .custom_page > .page_content > div').html('');

        var data = {
            load: 'custom_page_content',
            page_id: $(this).attr('page_id'),
        };

        if (user_csrf_token !== null) {
            data["csrf_token"] = user_csrf_token;
        }

        if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
            data["login_session_id"] = user_login_session_id;
            data["access_code"] = user_access_code;
            data["session_time_stamp"] = user_session_time_stamp;
        }

        $.ajax({
            type: 'POST',
            url: api_request_url,
            data: data,
            async: true,
            success: function(data) {}
        }).done(function(data) {
            if (data === '') {
                location.reload(true);
            } else if (isJSON(data)) {
                data = $.parseJSON(data);


                if (data.browser_title !== undefined) {
                    browser_title = data.browser_title;
                }

                if (data.browser_address_bar !== undefined) {
                    browser_address_bar = data.browser_address_bar;
                }

                if (data.title != undefined) {
                    $('.main .middle > .content > .custom_page > .header > .left > .title').replace_text(data.title);
                }

                if (data.subtitle != undefined) {
                    $('.main .middle > .content > .custom_page > .header > .left > .sub_title').replace_text(data.subtitle);
                } else {
                    $('.main .middle > .content > .custom_page > .header > .left > .sub_title').replace_text('');
                }

                if (data.page_content != undefined) {
                    $('.main .middle > .content > .custom_page > .page_content > div').html(data.page_content);
                    $('.main .middle > .content > .custom_page > .page_content').show();
                }
            } else {
                console.log('ERROR : ' + data);
            }
            if (element.attr('loader') !== undefined) {
                $(element.attr('loader')).hide();
            }
            element.removeClass('processing');

            change_browser_title(browser_title);

            history.pushState({}, null, browser_address_bar);

        }) .fail(function(qXHR, textStatus, errorThrown) {
            if (element.attr('loader') !== undefined) {
                $(element.attr('loader')).hide();
            }
            element.removeClass('processing');
            console.log('ERROR : ' + errorThrown);
        });
    }
});


$("body").on('click', '.preview_pdf', function(e) {
    var pdf_url = $(this).attr('pdf');
    generate_pdf_preview(pdf_url);
});

function generate_pdf_preview(pdf_url) {

    if (new URL(pdf_url).hostname === 'localhost' || new URL(pdf_url).hostname === '127.0.0.1') {
        console.log('The PDF URL is from localhost.');
    } else {
        pdf_url = 'https://docs.google.com/viewer?embedded=true&url='+pdf_url;
    }

    if ($('#pdfModal').length === 0) {
        var content_modal =
        '<div class="modal fade pdf_modal_box" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">' +
        '<div class="modal-dialog modal-dialog-centered" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-body">' +
        '<iframe src="' + pdf_url + '" width="100%" height="100%" style="border: none;"></iframe>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
        $('body').append(content_modal);
    } else {
        $('.pdf_modal_box .modal-body').html('<iframe src="' + pdf_url + '" width="100%" height="100%" style="border: none;"></iframe>');
    }

    $('#pdfModal').modal('show');
}

function fetch_user_info(user) {
    return new Promise(function (resolve, reject) {
        var data = {
            fetch_info: true,
            fetch: 'site_user',
            user_id: user,
        };

        var userData = {
            username: 'unknown'
        };

        if (user_csrf_token !== null) {
            data["csrf_token"] = user_csrf_token;
        }

        if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
            data["login_session_id"] = user_login_session_id;
            data["access_code"] = user_access_code;
            data["session_time_stamp"] = user_session_time_stamp;
        }

        $.ajax({
            type: 'POST',
            url: api_request_url,
            data: data,
            async: true,
            success: function (data) {
                if (isJSON(data)) {
                    userData = $.parseJSON(data);
                    if (userData.username !== undefined) {
                        resolve(userData);
                    } else {
                        reject("Invalid User ID");
                    }
                } else {
                    reject("Invalid JSON format");
                }
            },
            error: function (qXHR, textStatus, errorThrown) {
                console.log('ERROR : ' + errorThrown);
                reject(errorThrown);
            }
        });
    });
}


$("body").on('mouseenter', '.main .chatbox > .header.view_info > .heading,.main .chatbox > .header.view_info > .image', function(e) {
    if ($(window).width() > 767.98) {
        $('.main .chatbox > .header > .heading > .subtitle').hide();
        $('.main .chatbox > .header > .heading > .whos_typing').hide();
        $('.main .chatbox > .header > .heading > .view_info').fadeIn();
    }
});

$("body").on('mouseleave', '.main .chatbox > .header.view_info > .heading,.main .chatbox > .header.view_info > .image', function(e) {
    if ($(window).width() > 767.98) {
        $('.main .chatbox > .header > .heading > .view_info').hide();
        $('.main .chatbox > .header > .heading > .subtitle').fadeIn();
        $('.main .chatbox > .header > .heading > .whos_typing').fadeIn();
    }
});


$("body").on('click', '.call_notification > .action-buttons > .action-button.reject_video_call', function(e) {

    if (!$('.call_notification .call_ringtone')[0].paused) {
        $('.call_notification .call_ringtone')[0].pause();
        $('.call_notification .call_ringtone')[0].currentTime = 0;
    }

    var post_data = {
        update: 'video_chat_status',
        call_log_delete: true
    };

    $.ajax({
        type: 'POST',
        url: api_request_url,
        data: post_data,
        async: true,
        success: function(response) {},
        error: function(xhr, status, error) {}
    });

    if (call_notification_timeout_id) {
        clearTimeout(call_notification_timeout_id);
    }

    $('.call_notification').attr('current_call_id', 0);
    $('.call_notification').addClass('d-none');
});

$("body").on('click', '.call_notification > .action-buttons > .action-button.attend_video_call', function(e) {

    if (call_notification_timeout_id) {
        clearTimeout(call_notification_timeout_id);
    }

    if (!$('.call_notification .call_ringtone')[0].paused) {
        $('.call_notification .call_ringtone')[0].pause();
        $('.call_notification .call_ringtone')[0].currentTime = 0;
    }

    $('.call_notification').addClass('d-none');

});

function initilazing_video_chat() {
    close_chat_header_window('all');
    $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').removeClass('full_view_container');

    if (audio_message_preview !== undefined && audio_message_preview !== null) {
        audio_message_preview.pause();
    }

    if (audio_player !== undefined && audio_player !== null) {
        audio_player.pause();
    }
}

function call_notification_timeout(todo) {

    if (todo == 'start') {
        call_notification_timeout_id = setTimeout(function () {

            if (!$('.main .middle > .video_chat_interface').hasClass('d-none')) {
                if ($('.main .chatbox').attr('user_id') !== undefined) {
                    current_video_caller_id = $('.main .chatbox').attr('user_id');
                } else {
                    current_video_caller_id = 0;
                }
            }
            if (!$('.call_notification .call_ringtone')[0].paused) {
                $('.call_notification .call_ringtone')[0].pause();
                $('.call_notification .call_ringtone')[0].currentTime = 0;
            }

            if (current_video_caller_id == 0 || current_video_caller_id !== $('.call_notification').attr('current_call_id')) {
                $('.call_notification > .action-buttons > .action-button.reject_video_call').trigger('click');
            }

            $('.call_notification').addClass('d-none');
        }, 25000);
    } else {
        if (!$('.call_notification .call_ringtone')[0].paused) {
            $('.call_notification .call_ringtone')[0].pause();
            $('.call_notification .call_ringtone')[0].currentTime = 0;
        }
        if (call_notification_timeout_id) {
            clearTimeout(call_notification_timeout_id);
        }
        $('.call_notification').addClass('d-none');
    }
}

function arrange_video_chat_grid() {

    var flexItems = $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid > div');
    var itemCount = flexItems.length;

    $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').removeClass('grid_one grid_two grid_three');

    if (itemCount >= 10) {
        $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').addClass('grid_three');
    } else if (itemCount >= 8) {
        $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').addClass('grid_two');
    } else if (itemCount >= 5) {
        $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').addClass('grid_one');
    }
}



function update_video_chat_status() {
    if (!$('.main .middle > .video_chat_interface').hasClass('d-none')) {
        var post_data = {
            update: 'video_chat_status',
        };

        if ($('.main .chatbox').attr('group_id') !== undefined) {
            post_data['group_id'] = $('.main .chatbox').attr('group_id');
        } else if ($('.main .chatbox').attr('user_id') !== undefined) {
            post_data['user_id'] = $('.main .chatbox').attr('user_id');
        }

        videoChatStatusUpdateRequest = $.ajax({
            type: 'POST',
            url: api_request_url,
            data: post_data,
            async: true,
            beforeSend: function() {
                if (videoChatStatusUpdateRequest) {
                    videoChatStatusUpdateRequest.abort();
                }
                if (videoChatStatusUpdateTimeoutId) {
                    clearTimeout(videoChatStatusUpdateTimeoutId);
                }
            },
            success: function(response) {},
            error: function(xhr, status, error) {},
            complete: function() {
                videoChatStatusUpdateTimeoutId = setTimeout(update_video_chat_status, 30000);
            }
        });
    }
}

function stop_update_video_chat_status() {
    if (videoChatStatusUpdateRequest) {
        videoChatStatusUpdateRequest.abort();
    }
    if (videoChatStatusUpdateTimeoutId) {
        clearTimeout(videoChatStatusUpdateTimeoutId);
    }

    var post_data = {
        update: 'video_chat_status',
        offline: true
    };

    if ($('.main .chatbox').attr('group_id') !== undefined) {
        post_data['group_id'] = $('.main .chatbox').attr('group_id');
    } else if ($('.main .chatbox').attr('user_id') !== undefined) {
        post_data['user_id'] = $('.main .chatbox').attr('user_id');

        if ($('.call_notification').attr('current_call_id') == $('.main .chatbox').attr('user_id')) {
            return;
        }
    }

    $.ajax({
        type: 'POST',
        url: api_request_url,
        data: post_data,
        async: true,
        success: function(response) {},
        error: function(xhr, status, error) {}
    });
}



$("body").on('click', '.toggle_search_messages', function(e) {
    if ($('.main .middle .search_messages').is(':visible')) {
        $('.main .middle .search_messages').hide();
    } else {
        $('.main .chatbox > .header > .switch_user').removeClass('open');
        $('.main .middle .search_messages').fadeIn();
        $('.main .middle .search_messages > div > .search > div > input').trigger('focus');
    }
});

$("body").on('click', '.main .middle > .video_preview .close_player', function(e) {
    close_chat_header_window('video_preview');

});

$("body").on('click', '.main .middle > .iframe_window .close_iframe_window', function(e) {
    close_chat_header_window('iframe_window');
});


$("body").on('click', '.main .middle > .group_headers .close_group_header', function(e) {
    close_chat_header_window('group_header');
});


function close_chat_header_window(window) {

    if (window === 'video_preview' || window === 'all') {
        $('.main .middle > .video_preview').removeClass('fixed_draggable_layout');
        $('.main .middle > .video_preview').addClass('d-none');
        $('.main .middle > .video_preview > div').html('');

        if ($('.main .middle > .group_headers').hasClass('header_content_loaded')) {
            $('.main .middle > .group_headers').removeClass('d-none');
            $('.main .middle > .group_headers > .header_content').html(group_header_contents);
        }
    }

    if (window === 'group_header' || window === 'all') {
        group_header_contents = null;
        $('.main .middle > .group_headers > .header_content').html('');
        $('.main .middle > .group_headers').removeClass('header_content_loaded');
        $('.main .middle > .group_headers').addClass('d-none');
    }

    if (window === 'iframe_window' || window === 'all') {
        $('.main .middle > .iframe_window').addClass('d-none');
        $('.main .middle > .iframe_window > div').html('');

        if ($('.main .middle > .group_headers').hasClass('header_content_loaded')) {
            $('.main .middle > .group_headers').removeClass('d-none');
            $('.main .middle > .group_headers > .header_content').html(group_header_contents);
        }
    }

}


$("body").on('mouseenter', '.main .chatbox > .contents > .chat_messages > ul > li > div >.right > .header > .tools > .timestamp', function(e) {
    $('.main .chatbox > .contents > .date').hide();
    if ($(this).parents('.message').find('.date').attr('message_sent_on') !== undefined) {
        var message_sent_on = $(this).parents('.message').find('.date').attr('message_sent_on');
        $('.main .chatbox > .contents > .date > span').text(message_sent_on);
        $('.main .chatbox > .contents > .date').show();
    }
});

$("body").on('mouseleave', '.main .chatbox > .contents > .chat_messages > ul > li > div >.right > .header > .tools > .timestamp', function(e) {
    $('.main .chatbox > .contents > .date').hide();
});



$("body").on('click', '.preview_video', function(e) {

    $('.main .middle > .iframe_window').addClass('d-none');
    $('.main .middle > .iframe_window > div').html('');

    $('.main .middle > .group_headers').addClass('d-none');
    $('.main .middle > .group_headers > .header_content').html('');

    $('.main .middle > .video_preview').addClass('d-none');
    $('.main .middle > .video_preview > div').html('');

    $('.main .middle > .video_preview').removeAttr("style");
    $('.main .middle > .video_preview').removeClass('fixed_draggable_layout');

    if (!$('.main .middle > .video_chat_interface').hasClass('d-none')) {
        $('.main .middle > .video_preview').addClass('fixed_draggable_layout');
    }

    var content = '';

    if ($(this).attr('video_file') !== undefined) {

        if ($(this).attr('mime_type') === undefined) {
            $(this).attr('mime_type', '');
        }

        if ($(this).attr('thumbnail') === undefined) {
            $(this).attr('thumbnail', '');
        }

        content += '<video id="video_preview" class="video-js vjs-theme-city" autoplay playsinline controls poster="'+$(this).attr('thumbnail')+'">';
        content += '<source src="'+$(this).attr('video_file')+'" type="'+$(this).attr('mime_type')+'" />';
        content += '</video>';
    } else if ($(this).attr('video_url') !== undefined) {
        content += '<video id="video_preview" class="video-js vjs-theme-city" autoplay playsinline controls poster="'+$(this).attr('thumbnail')+'">';
        content += '</video>';
    }

    if (content.length !== 0) {
        open_column('second');
        $('.main .middle > .video_preview > div').html(content);
        $('.main .middle > .video_preview').removeClass('d-none');

        fixed_layout_draggable();

        if (video_preview !== null) {
            videojs('video_preview').dispose();
        }
        if ($(this).attr('video_url') === undefined) {
            video_preview = videojs('video_preview');
        } else {
            var video_provider = ($(this).attr('mime_type')).replace("video/", "");
            var video_muted = false;

            if (window.self !== window.top) {
                video_muted = true;
            }

            video_preview = videojs('video_preview', {
                controls: true,
                muted: video_muted,
                sources: [{
                    src: $(this).attr('video_url'), type: $(this).attr('mime_type')
                }],
                techOrder: [video_provider]
            });

        }

        video_preview.on('play', () => {

            if (video_preview.muted()) {
                video_preview.muted(false);
            }

            if (audio_message_preview !== undefined && audio_message_preview !== null) {
                audio_message_preview.pause();
            }

            if (audio_player !== undefined && audio_player !== null) {
                audio_player.pause();
            }
        });
    }
});


function load_group_header(group_identifier) {
    if (group_identifier !== undefined) {

        var data = {
            load: 'group_header',
            group_id: group_identifier,
            skip_output: true
        };

        if (user_csrf_token !== null) {
            data["csrf_token"] = user_csrf_token;
        }

        if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
            data["login_session_id"] = user_login_session_id;
            data["access_code"] = user_access_code;
            data["session_time_stamp"] = user_session_time_stamp;
        }

        load_group_header_request = $.ajax({
            type: 'POST',
            url: api_request_url,
            data: data,
            async: true,
            beforeSend: function() {
                if (load_group_header_request != null) {
                    load_group_header_request.abort();
                    load_group_header_request = null;
                }
            },
            success: function(data) {}
        }).done(function(data) {
            if ($.trim(data) !== '') {
                group_header_contents = data;
                $('.main .middle > .group_headers > .header_content').html(data);
                $('.main .middle > .group_headers').removeClass('d-none');
                $('.main .middle > .group_headers').addClass('header_content_loaded');
            }
        }) .fail(function(qXHR, textStatus, errorThrown) {
            if (qXHR.statusText !== 'abort' && qXHR.statusText !== 'canceled') {
                console.log('ERROR : ' + errorThrown);
            }
        });
    }
}


$("body").on('click', '.iframe_embed', function(e) {

    if (!$('.main .middle > .video_chat_interface').hasClass('d-none')) {
        e.preventDefault();
        window.open($(this).attr('embed_url'), '_blank');
        return;
    }

    if (video_preview !== undefined && video_preview !== null) {
        video_preview.pause();
    }

    if (audio_message_preview !== undefined && audio_message_preview !== null) {
        audio_message_preview.pause();
    }

    if (audio_player !== undefined && audio_player !== null) {
        audio_player.pause();
    }

    $('.main .middle > .video_preview').addClass('d-none');
    $('.main .middle > .video_preview > div').html('');

    $('.main .middle > .group_headers').addClass('d-none');

    $('.main .middle > .group_headers > .header_content').html('');

    $('.main .middle > .iframe_window > div').html('<iframe></iframe>');
    $('.main .middle > .iframe_window > div > iframe').attr('frameborder', 0);
    $('.main .middle > .iframe_window > div > iframe').attr('allowFullScreen', true);
    $('.main .middle > .iframe_window > div > iframe').attr('webkitallowfullscreen', true);
    $('.main .middle > .iframe_window > div > iframe').attr('mozallowfullscreen', true);
    $('.main .middle > .iframe_window > div > iframe').attr('src', $(this).attr('embed_url'));

    if ($(this).attr('iframe_class') !== undefined) {
        $('.main .middle > .iframe_window > div > iframe').addClass($(this).attr('iframe_class'));
    }

    if ($(this).attr('iframe_relative_height') !== undefined) {
        $('.main .middle > .iframe_window').css('position', 'relative');
        $('.main .middle > .iframe_window').css('height', $(this).attr('iframe_relative_height'));
    } else {
        $('.main .middle > .iframe_window').removeAttr('style');
    }

    $('.main .middle > .iframe_window').removeClass('d-none');
});