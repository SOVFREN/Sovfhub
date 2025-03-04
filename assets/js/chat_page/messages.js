var load_messages_request = null;
var alert_message_time_interval = null;
var load_messages_reload_timeout = null;
var audio_message_preview = document.getElementById("audio_message_preview");
var page_rendering_fix = false;
var load_messages_reload_loop = 0;
var focus_on_send_button = false;
var shared_screenshot_BlobIds = [];

$('.main .chatbox > .contents > .chat_messages').on('scroll', function(e) {

    $('.main .chatbox > .contents > .date').hide();

    if (Math.abs($(this).scrollTop()) + $(this).innerHeight() >= $(this)[0].scrollHeight-60) {
        if (!$('.main .chatbox > .contents > .chat_messages').hasClass('EndResults') && !$('.main .chatbox > .contents > .chat_messages').hasClass('loading')) {
            $('.main .chatbox > .contents > .chat_messages').addClass('loading');

            var data = {
                message_id_less_than: get_message_id('first'),
                prepend_messages: true,
            };

            if ($(".main .chatbox").attr('group_id') !== undefined) {
                data['group_id'] = $(".main .chatbox").attr('group_id');
            } else if ($(".main .chatbox").attr('user_id') !== undefined) {
                data['user_id'] = $(".main .chatbox").attr('user_id');
            }

            if ($('.main .chatbox > .contents > .chat_messages').hasClass('searching')) {
                if ($('.main .middle .search_messages > div > .search > div > input').val().length > 0) {
                    data['search'] = $('.main .middle .search_messages > div > .search > div > input').val();
                }
            }

            if (data['group_id'] !== undefined || data['user_id'] !== undefined) {
                load_conversation(data);
            }

        }
    }

    if (page_rendering_fix) {
        $('.main .chatbox > .contents > .chat_messages').attr('style', 'overflow:auto');
        setTimeout(function() {
            $('.main .chatbox > .contents > .chat_messages').attr('style', 'overflow-x:hidden');
        }, 500);
    }
});

$("body").on('click', '.main .load_message', function(e) {
    if ($(this).attr('message_id') !== undefined) {
        var data = {
            message_id: $(this).attr('message_id'),
            prepend_messages: true,
            find_message: true,
        };

        if ($(".main .chatbox").attr('group_id') !== undefined) {
            data['group_id'] = $(".main .chatbox").attr('group_id');
        } else if ($(".main .chatbox").attr('user_id') !== undefined) {
            data['user_id'] = $(".main .chatbox").attr('user_id');
        }

        if (data['group_id'] !== undefined || data['user_id'] !== undefined) {

            if (data['group_id'] == $(".main .chatbox").attr('group_id') || data['user_id'] == $(".main .chatbox").attr('user_id')) {
                var message_element = $('.main .chatbox > .contents > .chat_messages > ul > li[message_id="'+data['message_id']+'"]');
                if (message_element.length > 0) {
                    highlight_message(data['message_id']);
                } else {
                    load_conversation(data);
                }
            }
        }
    }

});




$("body").on("keyup", ".main .middle .search_messages > div > .search > div > input", function(e) {
    if (e.which == 13 || search_on_change_of_input) {
        var data = [];

        if ($(this).val().length > 0) {
            data['search'] = $(this).val();
            $('.main .chatbox').addClass('searching_chat_message');
        }

        if ($(".main .chatbox").attr('group_id') !== undefined) {
            data['group_id'] = $(".main .chatbox").attr('group_id');
        } else if ($(".main .chatbox").attr('user_id') !== undefined) {
            data['user_id'] = $(".main .chatbox").attr('user_id');
        }
        load_conversation(data);
    }
});

$("body").on("click", ".main .reload_conversation", function(e) {

    $('.main .middle .search_messages').hide();

    var data = [];

    if ($(".main .chatbox").attr('group_id') !== undefined) {
        data['group_id'] = $(".main .chatbox").attr('group_id');
    } else if ($(".main .chatbox").attr('user_id') !== undefined) {
        data['user_id'] = $(".main .chatbox").attr('user_id');
    }

    load_conversation(data);
});

$("body").on("click", ".main .share_file", function(e) {

    open_column('second');

    var content = {
        'share_file': $(this).attr('file_name'),
        'scrollToBottom': true,
    };
    send_message(content);
});


$("body").on("click", ".main .share_user_location", function(e) {

    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var content = {
                'share_user_location': true,
                'latitude': position.coords.latitude,
                'longitude': position.coords.longitude,
                'scrollToBottom': true,
            };
            send_message(content);
        }, function(error) {
            alert("Error getting location: " + error.message);
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }

});



function highlight_message(message_id) {
    var message_element = $('.main .chatbox > .contents > .chat_messages > ul > li[message_id="'+message_id+'"]');
    if (message_element.length > 0) {

        var scroll_to_position = $('.main .chatbox > .contents > .chat_messages > ul > li[message_id="'+message_id+'"]')[0].offsetTop;
        scroll_to_position = scroll_to_position-10;

        message_element.addClass('highlight');
        $('.main .chatbox > .contents > .chat_messages').animate({
            scrollTop: scroll_to_position
        }, 1500);
        setTimeout(function() {
            message_element.removeClass('highlight');
        }, 5000);
    }
}



$("body").on('click', '.main .chatbox .toggle_switch_user', function(e) {

    if (!$('.main .chatbox > .header > .switch_user').hasClass('open')) {
        $('.main .middle .search_messages').hide();
    }

    $('.main .chatbox > .header > .switch_user').toggleClass('open');
    $('.main .chatbox > .header > .switch_user > div.search_users > .search > div > input').trigger('focus');
});

$("body").on('click', '.main .switch_user_id', function(e) {
    if ($(this).attr('user_id') !== undefined) {
        $('.main .chatbox > .header > .switch_user > .user_id > input').val($(this).attr('user_id'));
        if ($(this).find('.image > img').attr('src') !== undefined) {
            $('.main .chatbox > .header > .switch_user > .username').text($(this).find('.title').text());

            if ($(this).attr('user_id') === $('.logged_in_user_id').text()) {
                $('.main .chatbox > .header > .switch_user > .current_selected_user > .user_image').html('');
            } else {
                $('.main .chatbox > .header > .switch_user > .current_selected_user > .user_image').html('<img src="'+$(this).find('.image > img').attr('src')+'"/>');
            }
        }
        $('.main .chatbox > .header > .switch_user').removeClass('open');

    }
});



$("body").on("keyup", ".main .chatbox > .header > .switch_user > div.search_users > .search > div > input", function(e) {
    if (e.which == 13) {
        var keyword = $(this).val();
        if ($(".main .chatbox").attr('group_id') !== undefined && keyword.length > 0) {

            var user_info = '';
            var post_data = {
                load: 'group_members_mentions',
                search: keyword,
                group_id: $(".main .chatbox").attr('group_id')
            };

            $('.main .chatbox > .header > .switch_user > div.search_users > .list > ul').html('');

            if (user_csrf_token !== null) {
                post_data["csrf_token"] = user_csrf_token;
            }

            if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
                post_data["login_session_id"] = user_login_session_id;
                post_data["access_code"] = user_access_code;
                post_data["session_time_stamp"] = user_session_time_stamp;
            }

            $.ajax({
                type: 'POST',
                url: api_request_url,
                data: post_data,
                async: true
            }).done(function (data) {
                if (isJSON(data)) {
                    data = $.parseJSON(data);
                    if (Object.keys(data).length > 0) {
                        $.each(data, function(key, val) {
                            user_info = '<li class="switch_user_id" user_id="'+data[key].user_id+'">';
                            user_info += '<span class="image"> <img onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';" src="'+data[key].avatar+'"> </span>';
                            user_info += '<span class="title">'+data[key].name+'</span>';
                            user_info += '</li>';
                            $('.main .chatbox > .header > .switch_user > div.search_users > .list > ul').append(user_info);
                        });
                    }
                }
            });
        }
    }
});

function get_message_id(find) {

    var message_ids = [];
    var message_id = 0;

    if (find !== undefined && find === 'last_seen_by_recipient') {
        var all_messages = $('.main .chatbox > .contents > .chat_messages > ul > li.seen_by_recipient');
        find = 'last';
    } else {
        var all_messages = $('.main .chatbox > .contents > .chat_messages > ul > li');
    }

    all_messages.each(function() {
        if ($(this).attr('message_id') != undefined && !$(this).hasClass('skip_message')) {
            message_ids.push($(this).attr('message_id'));
        }
    });

    if (find !== undefined && find === 'last') {
        message_ids.sort(function(a, b) {
            return b-a;
        });
    } else {
        message_ids.sort(function(a, b) {
            return a-b;
        });
    }

    if (message_ids[0] !== undefined) {
        message_id = message_ids[0];
    }

    return message_id;
}

$("body").on('click', '.send_sticker', function(e) {

    var content = {
        'sticker': $(this).attr('sticker'),
        'sticker_pack': $(this).attr('sticker_pack'),
        'scrollToBottom': true,
    };

    send_message(content);
});

$("body").on('click', '.add_emoji', function(e) {
    $('#message_editor').summernote('restoreRange');
    var emojiClass = 'emoji_icon emoji-'+$(this).attr('emoji');
    var emoji_tag = "<span contenteditable=false class='"+emojiClass+"'>&nbsp;</span>";
    emoji_tag += "<span>&nbsp;</span>";

    var emoji_tag = "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=' class='"+emojiClass+"'/>";
    $('#message_editor').summernote('pasteHTML', emoji_tag);
});


$("body").on('click', '.detach_message', function(e) {
    $('.main .attached_message_id > input').val('0');
    $('.main .chatbox > .footer .attached_message').hide();
    $('#message_editor').summernote('restoreRange');
});

$("body").on('click', '.attach_message', function(e) {

    $('.main .chatbox > .footer .attached_message > .attached_message_preview > div.content > .right').hide();

    var message_id = $(this).attr('message_id');
    var send_by = $(this).parents('.message').find('.header .send_by').text();
    var content = $(this).parents('.message').find('.message_content').html();
    var thumbnail = $(this).parents('.message').find('.files .preview_image').attr('src');

    if (content === undefined || content.length === 0) {
        content = $(this).parents('.message').attr('message_label');
    }

    if (thumbnail !== undefined) {
        thumbnail = '<img src="'+thumbnail+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';
        $('.main .chatbox > .footer .attached_message > .attached_message_preview > div.content > .right > .thumbnail').html(thumbnail);
        $('.main .chatbox > .footer .attached_message > .attached_message_preview > div.content > .right').show();
    }

    $('.main .attached_message_id > input').val(message_id);
    $('.main .chatbox > .footer .attached_message > .attached_message_preview > div.content > .left > .send_by').replace_text(send_by);
    $('.main .chatbox > .footer .attached_message > .attached_message_preview > div.content > .left > .text').html(content);
    $('.main .chatbox > .footer .attached_message').show();
    $('#message_editor').summernote('restoreRange');
});


function send_message(content) {

    var data = new FormData();
    var empty_message = false;
    var message = gif_url = '';
    var files_attached = false;
    var attached_media_type;
    data.append("add",
        "message");

    typing_indicator('reset');

    $('.main .middle .chatbox > .alert_message').hide();

    close_module('.grid_list',
        '.chatbox > .footer');

    $('.message_editor .note-editor.note-frame .note-editing-area .note-editable').removeClass('highlight');

    if ($('.main .chatbox').attr('group_id') !== undefined) {
        data.append("group_id", $('.main .chatbox').attr('group_id'));
    } else if ($('.main .chatbox').attr('user_id') !== undefined) {
        data.append("user_id", $('.main .chatbox').attr('user_id'));
    } else {
        return false;
    }

    if (content !== undefined) {
        $.each(content, function(key, value) {
            data.append(key, value);
        });
        if (content.sticker !== undefined || content.share_user_location !== undefined || content.audio_message !== undefined || content.share_file !== undefined || content.screenshot !== undefined) {
            empty_message = true;
        }
    } else {

        if (!$('#message_editor').summernote('isEmpty')) {
            message = $('#message_editor').summernote('code');
        }

        data.append("message", message);

        if ($('.main .chatbox > .footer > .editor > div > .attached_media > .media_url > input.attached_media_url').val().length !== 0) {


            var attached_media_url = $('.main .chatbox > .footer > .editor > div > .attached_media > .media_url > input.attached_media_url').val();
            var attached_media_type = $('.main .chatbox > .footer > .editor > div > .attached_media > .media_url > input.attached_media_type').val();

            if (attached_media_type === 'gif') {
                gif_url = attached_media_url;
                data.append("gif_url", gif_url);
            } else {
                data.append(attached_media_type, attached_media_url);
            }

            empty_message = true;

        } else if ($(".file_attachments").length !== 0) {

            var files = [];
            var file_index = 0;

            $(".file_attachments").each(function() {
                files = $(this).get(0).files;
                for (var i = 0; i < files.length; i++) {
                    var file_identifier = (files[i].name.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, ''))+'_'+files[i].lastModified;
                    var check_removed = $(".attachments li.removed[file_identifier='"+file_identifier+"']");
                    if (check_removed.length == 0) {
                        data.append("file_attachments["+file_index+"]", files[i]);
                        file_index = file_index+1;
                        files_attached = true;
                    }
                }
            });

            empty_message = true;
        }

        $('.main .chatbox > .footer > .attachments > div > .files > ul').html('');
        $('.main .chatbox > .footer > .grid_list > div > .results > div > ul > li').removeClass('selected');
        deattach_media();
        $('.attachments > div > .attached_files > form').html('');

    }

    var characters = message.replace(/(<([^>]+)>)/ig,
        "");
    var totalCharacters = characters.length;

    var min_message_length = 1;

    if ($('.main .chatbox > .footer > .editor').attr('min_message_length') !== undefined) {
        min_message_length = parseInt($('.main .chatbox > .footer > .editor').attr('min_message_length'));

        if (isNaN(min_message_length)) {
            min_message_length = 1;
        }
    }

    if (totalCharacters === 0) {
        if (message.match(/<img [^>]*src="[^"]*"[^>]*>/gm)) {
            totalCharacters = message.match(/<img [^>]*src="[^"]*"[^>]*>/gm).length;
        }
    }

    if (totalCharacters < min_message_length && !empty_message) {
        $('.message_editor .note-editor.note-frame .note-editing-area .note-editable').addClass('highlight');
        return false;
    } else {
        var pending_message_identifier = 'pending_'+RandomString(6);
        var sample_message_alignment = '';
        var sample_message = message;

        if (content !== undefined && content.share_user_location != undefined) {
            sample_message = language_string('sharing_your_location');
        }

        if (attached_media_type !== undefined && attached_media_type === 'video_url') {
            if (message.length === 0) {
                sample_message = language_string('sharing_video');
            }
        }

        var send_sample_data = {
            "messages": [{
                "class": 'sample_message own_message '+pending_message_identifier,
                "image": $('.logged_in_user_avatar').attr('src'),
                "posted_by": $('.logged_in_user_name').text(),
                "name_color": $('.logged_in_user_name_color').text(),
                "sample_data": true,
                "content": sample_message,
            }],
            "append": true
        };

        if (content !== undefined && content.scrollToBottom !== undefined) {
            send_sample_data['scrollToBottom'] = true;
        } else if (content === undefined) {
            send_sample_data['scrollToBottom'] = true;
        }

        if (system_variable('own_message_alignment') === 'right') {
            sample_message_alignment = ' align_right';
        }

        $('#message_editor').summernote('code', '');

        if ($('.main .chatbox').attr('group_id') !== undefined) {
            if ($('.main .chatbox > .header > .switch_user').length > 0) {
                if ($('.main .chatbox > .header > .switch_user > .current_selected_user > .user_image > img').length > 0) {
                    send_sample_data.messages[0].image = $('.main .chatbox > .header > .switch_user > .current_selected_user > .user_image > img').attr('src');
                    send_sample_data.messages[0].posted_by = $('.main .chatbox > .header > .switch_user > .username').text();
                    var send_as_user_id = $('.main .chatbox > .header > .switch_user > .user_id > input').val();
                    if (send_as_user_id.length > 0 && send_as_user_id !== '0') {
                        data.append('send_as_user_id', send_as_user_id);
                        if (system_variable('message_alignment') === 'right') {
                            sample_message_alignment = ' align_right';
                        } else {
                            sample_message_alignment = '';
                        }
                    }
                }
            }
        }

        send_sample_data.messages[0].class += sample_message_alignment;

        if (gif_url.length !== 0) {
            send_sample_data.messages[0].class = send_sample_data.messages[0].class+' gif';
            send_sample_data.messages[0].attachments = new Array();
            send_sample_data.messages[0].attachments[0] = {
                "image": gif_url,
                "original": gif_url,
            };
        } else if (content !== undefined) {
            if (content.sticker != undefined && content.sticker_pack != undefined) {
                send_sample_data.messages[0].content = '';
                send_sample_data.messages[0].class = send_sample_data.messages[0].class+' sticker';
                var sticker_url = baseurl+'assets/files/stickers/'+content.sticker_pack+'/'+content.sticker;
                send_sample_data.messages[0].attachments = new Array();
                send_sample_data.messages[0].attachments[0] = {
                    "image": sticker_url,
                    "original": sticker_url,
                };
            } else if (content.screenshot != undefined) {
                send_sample_data.messages[0].content = '';
                send_sample_data.messages[0].class = send_sample_data.messages[0].class+' screenshot';
                send_sample_data.messages[0].attachments = new Array();
                send_sample_data.messages[0].attachments[0] = {
                    "image": content.screenshot,
                    "original": content.screenshot,
                };
            } else if (content.audio_message != undefined && content.blob != undefined) {
                send_sample_data.messages[0].content = '';
                send_sample_data.messages[0].attachment_type = 'audio_message';
                send_sample_data.messages[0].class = send_sample_data.messages[0].class+' audio_message';
                var audioURL = window.URL.createObjectURL(content.blob);
                send_sample_data.messages[0].attachments = new Array();
                send_sample_data.messages[0].attachments[0] = {
                    "audio_file": audioURL,
                    "file_type": 'audio/webm',
                };
            }
        } else if (files_attached) {
            send_sample_data.messages[0].attachments = new Array();
            send_sample_data.messages[0].attachments[0] = {
                "file_name": language_string('uploading_files'),
                "file_icon": baseurl+'assets/files/defaults/uploading_files.png',
                "subtitle": "0%",
            };
        }

        if ($('.main .attached_message_id > input').val().length !== 0) {
            data.append("attach_message", $('.main .attached_message_id > input').val());
        }

        load_messages(send_sample_data);

        var pending_message_element = $('.main .chatbox > .contents > .chat_messages > ul > li.'+pending_message_identifier);


        if ($('.main .chatbox > .contents > .chat_messages > ul > li > div > .left > .selector').length > 0) {
            $('.main .chatbox .selector').removeClass('multi_selection');
            $('.main .chatbox .selector').addClass('d-none');
        }

        if (user_csrf_token !== null) {
            data.append("csrf_token", user_csrf_token);
        }

        if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
            data.append("login_session_id", user_login_session_id);
            data.append("access_code", user_access_code);
            data.append("session_time_stamp", user_session_time_stamp);
        }

        $.ajax({
            type: 'POST',
            url: api_request_url,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                if (files_attached) {
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = ((evt.loaded / evt.total) * 100);
                            pending_message_element.find('.file_details > .subtitle').text(parseInt(percentComplete)+'%');
                        }
                    },
                        false);
                }
                return xhr;
            },
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            async: true,
            success: function(data) {}
        }).done(function(data) {
            if (isJSON(data)) {
                data = $.parseJSON(data);

                if (data.alert !== undefined) {

                    $('.main .middle .chatbox > .alert_message > div > .message').removeClass('error warning success');

                    if (data.alert.type !== undefined) {
                        $('.main .middle .chatbox > .alert_message > div > .message').addClass(data.alert.type);
                    }

                    $('.main .middle .chatbox > .alert_message > div > .message').replace_text(data.alert.message);
                    $('.main .middle .chatbox > .alert_message').show();

                    if (alert_message_time_interval !== null) {
                        clearTimeout(alert_message_time_interval);
                    }

                    alert_message_time_interval = setTimeout(function() {
                        $('.main .middle .chatbox > .alert_message').hide();
                        alert_message_time_interval = null;
                    }, 3000);
                }

                if (data.refresh !== undefined) {
                    location.reload(true);
                } else if (data.message !== undefined) {
                    data.message.append = true;
                    load_messages(data.message);
                }
                pending_message_element.remove();
            } else {
                console.log('ERROR : ' + data);

                pending_message_element.addClass('failed');

                setTimeout(function() {
                    pending_message_element.remove();
                }, 2000);
            }

        }) .fail(function(qXHR, textStatus, errorThrown) {
            console.log('ERROR : ' + errorThrown);

            pending_message_element.addClass('failed');

            setTimeout(function() {
                pending_message_element.remove();
            }, 2000);
        });

        $('.main .attached_message_id > input').val('0');
        $('.main .chatbox > .footer .attached_message').hide();
        $('.chatbox > .footer > .attachments').removeClass('hasAttachments');

        if (focus_on_send_button) {
            $('#message_editor').summernote('focus');
        }
    }
}

$('.main .chatbox').on('drop', function (e) {
    e.stopPropagation();
    e.preventDefault();

    if (!$('.main .chatbox').hasClass('d-none') && !$('.main .chatbox > .footer').hasClass('d-none')) {
        var dropped_files = e.originalEvent.dataTransfer.files;

        var identifier = 'user_input_' + RandomString(6);
        var new_file_input = '<input type="file" multiple name="file_attachments[]" class="file_attachments '+identifier+'"/>';
        $('.attachments > div > .attached_files > form').append(new_file_input);
        $('.'+identifier).hide();
        $('.'+identifier).prop('files', dropped_files);
        $('.'+identifier).trigger('change');
    }
});

$("body").on('click', '.attach_file', function(e) {
    var identifier = 'user_input_' + RandomString(6);
    var new_file_input = '<input type="file" multiple name="file_attachments[]" class="file_attachments '+identifier+'"/>';
    $('.attachments > div > .attached_files > form').append(new_file_input);
    $('.'+identifier).hide();
    $('.'+identifier).trigger('click');
});


$("body").on('click', '.attach_media ', function(e) {
    $(this).parent().find('li').removeClass('selected');
    $(this).addClass('selected');
    $('.main .chatbox > .footer > .editor > div > .attached_media > .media_image > img').removeAttr('src');
    $('.main .chatbox > .footer > .editor > div > .attached_media > .media_image > img').attr('src', $(this).find('img').attr('src'));
    $('.main .chatbox > .footer > .editor > div > .attached_media > .media_url > input.attached_media_url').val($(this).attr('media_url'));

    var attached_media_type = 'gif';
    if ($(this).attr('media_type') !== undefined) {
        attached_media_type = $(this).attr('media_type');
    }

    $('.main .chatbox > .footer > .editor > div > .attached_media > .media_url > input.attached_media_type').val(attached_media_type);
    $('.main .chatbox > .footer > .editor > div > .attached_media').removeClass('d-none');
});

$("body").on('click', '.deattach_media ', function(e) {
    deattach_media();
});

function deattach_media() {
    $('.main .chatbox > .footer > .grid_list > div > .results > div > ul > li.attach_media').removeClass('selected');
    $('.main .chatbox > .footer > .editor > div > .attached_media > .media_image > img').removeAttr('src');
    $('.main .chatbox > .footer > .editor > div > .attached_media').addClass('d-none');
    $('.main .chatbox > .footer > .editor > div > .attached_media > .media_url > input').val('');
}

$("body").on('change', '.file_attachments', function(e) {

    open_module('.attachments', '.chatbox > .footer', true);

    var file = '';

    $('.chatbox > .footer > .attachments').addClass('hasAttachments');

    for (var i = 0; i < $(this).get(0).files.length; ++i) {

        var filesize = ($(this).get(0).files[i].size/1024/1024).toFixed(2);
        var extension = $(this).get(0).files[i].type;
        var file_identifier = ($(this).get(0).files[i].name.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, ''))+'_'+$(this).get(0).files[i].lastModified;

        file = '<li file_identifier="'+file_identifier+'"><span class="file">';
        file += '<span class="file_preview">';

        if (extension === 'image/jpeg' || extension === 'image/webp' || extension === 'image/png' || extension === 'image/gif' || extension === 'image/bmp' || extension === 'image/x-ms-bmp') {
            var preview = URL.createObjectURL($(this).get(0).files[i]);
            file += '<span class="image">';
            file += '<img src="'+preview+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';">';
            file += '</span>';
        } else {
            file += '<span class="icon"></span>';
        }

        file += '</span>';

        file += '<span class="file_details">';
        file += '<span class="file_name" title="'+$(this).get(0).files[i].name+'">'+$(this).get(0).files[i].name+'</span>';
        file += '<span class="file_size">'+filesize+' mb</span>';
        file += '</span>';
        file += '</span>';
        file += '<span class="remove_file"><span class="bi bi-x-circle-fill"></span></span>';
        file += '</li>';
        $('.main .chatbox > .footer > .attachments > div > .files > ul').append(file);
        deattach_media();
    }
});

$("body").on('click', '.main .chatbox > .footer > .attachments > div > .files > ul > li > .remove_file', function(e) {
    $(this).parent('li').addClass('d-none removed');
    if ($('.main .chatbox > .footer > .attachments > div > .files > ul > li:visible').length === 0) {
        open_module('.attachments', '.chatbox > .footer');
        $('.chatbox > .footer > .attachments').removeClass('hasAttachments');
    }
});


$("body").on('click', '.load_conversation', function(event) {
    if (!$(event.target).hasClass('prevent_default') && !$(event.target).parent().hasClass('prevent_default') || $(this).hasClass('force_request')) {
        var load = [];
        shared_screenshot_BlobIds = [];

        if ($(this).attr('group_id') !== undefined) {
            load['group_id'] = $(this).attr('group_id');

            if (system_variable('load_group_info_on_group_load') === 'enable') {
                if ($(window).width() > 992) {
                    load['load_group_info'] = true;
                }
            }

            $('.main .side_navigation .menu_items').find('li.realtime_module[module="groups"] > .menu_item > .unread').html('');

        } else if ($(this).attr('user_id') !== undefined) {
            load['user_id'] = $(this).attr('user_id');

            if (system_variable('show_profile_on_pm_open') === 'enable') {
                if ($(window).width() > 992) {
                    load['load_user_info'] = true;
                }
            }

            $('.main .side_navigation .menu_items').find('li.realtime_module[module="private_conversations"] > .menu_item > .unread').html('');
        }
        $('.main .middle .search_messages').hide();

        if ($(this).attr('search') !== undefined) {
            load['search'] = $(this).attr('search');
            if ($(this).attr('search_message_id') !== undefined) {
                load['search_message_id'] = true;
            }
        }

        if ($(this).hasClass('info_panel_message_button')) {
            load['hide_infopanel_onload'] = true;
        }

        if ($(this).hasClass('attend_video_call')) {
            if ($(this).hasClass('audio_only_chat')) {
                load['open_audio_call'] = true;
            } else {
                load['open_video_call'] = true;
            }
        }

        if (!$('.main .chatbox > .footer > .editor .audio_recorder_box').hasClass('d-none')) {
            $('.cancel_recording').trigger('click');
        }

        load_conversation(load);
    }
});


function load_conversation(load) {

    $('.main .chatbox > .contents > .chat_messages').addClass('loading');

    typing_indicator('reset');

    var browser_title = default_meta_title;
    var browser_address_bar = baseurl;
    var hide_infopanel_onload = true;

    if (load.prepend_messages === undefined && load.append_messages === undefined) {

        if (system_variable('video_chat') !== 'disable') {
            if (video_chat_available) {
                exit_video_chat();
            }
        }

        open_column('second');

        $('.main .chatbox > .header > .icons').addClass('d-none');
        $('.main .chatbox > .contents > .error_message').hide();

        if ($(window).width() <= 991 && $(window).width() >= 770.98) {
            if (load.hide_infopanel_onload !== undefined) {
                hide_infopanel_onload = false;
            }
        }

        $('.main .chatbox > .info_box').html('');
        $('.main .chatbox > .info_box').hide();

        close_chat_header_window('all');


        $('.main .chatbox > .header > .image').show();
        $('.main .chatbox > .header').removeClass('d-none');

        $('.main .chatbox > .header').removeClass('view_info');
        $('.main .chatbox > .header > .image').removeClass('get_info');
        $('.main .chatbox > .header > .heading').removeClass('get_info');

        $(".main .chatbox > .header > .icons > span.delete_multiple_messages").addClass('d-none');
        $(".main .chatbox > .header > .image > .thumbnail").removeClass('img_loaded');
        $(".main .chatbox > .header > .image > .thumbnail > img").attr('src', baseurl+'assets/files/defaults/loading.gif');
        $(".main .chatbox > .header > .heading > .title").replace_text(language_string('loading'));

        $('.main .middle > .content > div').addClass('d-none');
        $('.main .middle > .content > .chatbox').removeClass('d-none');
        $('.main .chatbox > .footer').removeClass('d-none');

        $('.main .middle').removeClass('col-lg-5');
        $('.main .middle').removeClass('col-lg-6');
        $('.main .middle').addClass('col-lg-9');

        if (hide_infopanel_onload) {
            $('.main .info_panel').addClass('d-none');
        }

        $('.main .formbox').addClass('d-none');
        $('.main .chatbox > .contents > .chat_messages > ul').html('');
        $('.main .chatbox > .header > .switch_user').addClass('d-none');
        $('.main .chatbox > .header .toggle_switch_user').addClass('d-none');
        $('.main .chatbox > .header > .switch_user').removeClass('open');
        $('.main .chatbox > .header > .switch_user > .current_selected_user > .user_image').html('');
        $('.main .chatbox > .header > .switch_user > div.search_users > .search > div > input').val('');
        $('.main .chatbox > .header > .switch_user > div.search_users > .list > ul').html('');
        $('.main .chatbox > .header > .switch_user > .user_id > input').val('0');
        $('.main .chatbox > .header > .switch_user > .username').text('');
        $(".main .chatbox > .header > .message_selection").find('input').prop('checked', false);
        $(".main .chatbox > .header > .message_selection").addClass('d-none');
        $(".main .chatbox > .contents > .chat_messages").removeClass('multi_selection');
        $(".main .chatbox > .header > .icons .toggle_checkbox").addClass('d-none');
        $('.main .middle .confirm_box > .error').hide();
        $('.main .middle .confirm_box').addClass('d-none');
        $('.main .middle .chatbox > .alert_message').hide();
        $('.main .chatbox > .header .join_video_call_icon').removeClass('online');
        $('.main .chatbox > .header .join_video_call_icon').addClass('d-none');

        $('.main .chatbox > .header .join_audio_call_icon').removeClass('online');
        $('.main .chatbox > .header .join_audio_call_icon').addClass('d-none');

        if (audio_message_preview !== undefined && audio_message_preview !== null) {
            audio_message_preview.pause();
        }
    }

    var data = {
        load: "group_messages",
    };

    if (load.group_id !== undefined) {
        data['load'] = "group_messages";
        data['group_id'] = load.group_id;

        var list_element = $('.main .aside > .site_records > .records > .list > li.group_conversation[group_id="'+load.group_id+'"]');
        list_element.find('.title > .unread').html('');
    } else if (load.user_id !== undefined) {
        data['load'] = "private_chat_messages";
        data['user_id'] = load.user_id;
        var list_element = $('.main .aside > .site_records > .records > .list > li.private_conversation[user_id="'+load.user_id+'"]');
        list_element.find('.title > .unread').html('');
    }

    if (load.find_message !== undefined) {
        data['find_message'] = true;
    }

    if (load.search !== undefined) {

        if (load.search_message_id !== undefined && load.search_message_id) {
            data['search_message_id'] = true;
        }

        data['search'] = load.search;
        $('.main .chatbox > .header > .icons > span.reload_conversation').removeClass('d-none');
        $('.main .chatbox > .contents > .chat_messages').addClass('searching');
    } else {
        $('.main .chatbox').removeClass('chat_message_selection searching_chat_message');
        $('.main .middle .search_messages > div > .search > div > input').val('');
        $('.main .chatbox > .header > .icons > span.reload_conversation').addClass('d-none');
        $('.main .chatbox > .contents > .chat_messages').removeClass('searching');
    }

    if (load.message_id_less_than !== undefined) {
        data['message_id_less_than'] = load.message_id_less_than;
    }

    if (load.prepend_messages === undefined && load.append_messages === undefined) {
        close_module('.grid_list', '.chatbox > .footer');
        close_module('.attachments', '.chatbox > .footer');
        $(".main .chatbox > .footer").hide();
        $('.main .attached_message_id > input').val('0');
        $('.main .chatbox > .footer .attached_message').hide();

        if ($('.main .middle .conversation_loader > ul > li').length === 0) {
            $('.main .middle .conversation_loader > ul').html(loader_content());
        }

        $('.main .middle .conversation_loader').removeClass('error').show();
    }

    if (load.message_id !== undefined) {
        data['message_id'] = load.message_id;
    }

    if (user_csrf_token !== null) {
        data["csrf_token"] = user_csrf_token;
    }

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        data["login_session_id"] = user_login_session_id;
        data["access_code"] = user_access_code;
        data["session_time_stamp"] = user_session_time_stamp;
    }

    load_conversation_ajax_request(data, load);
}

function load_conversation_ajax_request(data, load) {

    var old_data = data;
    var browser_title = default_meta_title;
    var browser_address_bar = baseurl;

    load_messages_request = $.ajax({
        type: 'POST',
        url: api_request_url,
        data: data,
        async: true,
        beforeSend: function() {
            if (load_messages_request != null) {
                load_messages_request.abort();
                load_messages_request = null;
            }
            if (load_group_header_request != null) {
                load_group_header_request.abort();
                load_group_header_request = null;
            }

            if (load_messages_reload_timeout !== null) {
                clearTimeout(load_messages_reload_timeout);
                load_messages_reload_timeout = null;
            }
        },
        success: function(data) {}
    }).done(function(data) {
        if (isJSON(data)) {
            data = $.parseJSON(data);
            load_messages_reload_loop = 0;

            if (load.prepend_messages === undefined && load.append_messages === undefined) {

                if (data.error_message !== undefined) {
                    $('.main .chatbox > .contents > .error_message > div > div > .image').html('').hide();
                    $('.main .chatbox > .contents > .error_message > div > div > .text > .title').replace_text(data.error_message.title);
                    $('.main .chatbox > .contents > .error_message > div > div > .text > .subtitle').replace_text(data.error_message.subtitle);

                    if (data.error_message.image !== undefined) {
                        var error_image = '<img src="'+data.error_message.image+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';
                        $('.main .chatbox > .contents > .error_message > div > div > .image').html(error_image).show();
                    }

                    $('.main .chatbox > .contents > .error_message').show();
                }


                if (data.info_box !== undefined) {

                    var info_box = '<div ';

                    $.each(data.info_box.attributes, function(attr_key, attr_value) {
                        info_box += attr_key+'="'+attr_value+'" ';
                    });

                    info_box += '>';
                    info_box += '<div class="content">'+data.info_box.content+'</div>';
                    info_box += '</div>';

                    $('.main .chatbox > .info_box').html(info_box);
                    $('.main .chatbox > .info_box').show();
                }

                if (data.loaded !== undefined) {

                    if (data.error_message === undefined) {
                        $('.main .chatbox > .header > .icons').removeClass('d-none');
                    }

                    if (data.loaded.react_messages !== undefined && data.loaded.react_messages) {
                        $(".main .chatbox").attr('react_messages', true);
                    } else {
                        $(".main .chatbox").removeAttr('react_messages');
                    }

                    if (data.loaded.view_info !== undefined) {
                        $('.main .chatbox > .header').addClass('view_info');
                        $('.main .chatbox > .header > .image').addClass('get_info');
                        $('.main .chatbox > .header > .heading').addClass('get_info');
                    }

                    if (data.loaded.browser_title !== undefined) {
                        browser_title = data.loaded.browser_title;
                    }

                    if (data.loaded.browser_address_bar !== undefined) {
                        browser_address_bar = data.loaded.browser_address_bar;
                    }

                    if (data.loaded.title !== undefined) {
                        $(".main .chatbox > .header > .heading > .title").replace_text(data.loaded.title);
                    }

                    if (data.loaded.subtitle !== undefined) {
                        $(".main .chatbox > .header > .heading > .subtitle").replace_text(data.loaded.subtitle);
                    } else {
                        $(".main .chatbox > .header > .heading > .subtitle").replace_text('');
                    }

                    if (data.loaded.image !== undefined) {
                        $(".main .chatbox > .header > .image > .thumbnail").addClass('img_loaded');
                        $(".main .chatbox > .header > .image > .thumbnail > img").attr('src', data.loaded.image);
                        $(".main .chatbox > .header > .image > .thumbnail > img").on('error', function() {
                            $(this).attr("src", blur_img_url);
                        });
                    }

                    if (data.loaded.group_header !== undefined) {
                        if (data.loaded.group_id !== undefined) {
                            load_group_header(data.loaded.group_id);
                        }
                    }

                    if (data.loaded.multi_select !== undefined && data.loaded.multi_select) {
                        $(".main .chatbox > .header > .message_selection").removeClass('d-none');
                        $(".main .chatbox > .header > .icons .toggle_checkbox").removeClass('d-none');
                        //$(".main .chatbox > .contents > .chat_messages").addClass('multi_selection');
                    }

                    if (data.loaded.background_image !== undefined) {
                        $(".main .middle .chatbox > .background_image").html('<img src="'+data.loaded.background_image+'" onerror="this.onerror=null; this.style.display='+"'none'"+';"/>');
                    } else {
                        $(".main .middle .chatbox > .background_image").html('');
                    }

                    if (data.loaded.group_id !== undefined) {
                        $(".main .chatbox").removeAttr('user_id');
                        $(".main .chatbox").attr('group_id', data.loaded.group_id);

                        if ($('.main .chatbox').attr('group_id') !== 'all' && $('.main .chatbox > .header > .switch_user').length > 0) {
                            var default_user_image = $('.logged_in_user_avatar').attr('src');
                            default_user_image = "<img src='"+default_user_image+"'"+' onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';
                            $('.main .chatbox > .header > .switch_user').removeClass('d-none');
                            $('.main .chatbox > .header > .switch_user > .username').text($('.logged_in_user_name').text());
                            $('.main .chatbox > .header > .switch_user > .current_selected_user > .user_image').html('');
                            $('.main .chatbox > .header > .switch_user > .user_id > input').val('0');
                            $('.main .chatbox > .header .toggle_switch_user').removeClass('d-none');
                        }

                    } else if (data.loaded.user_id !== undefined) {
                        $(".main .chatbox").removeAttr('group_id');
                        $(".main .chatbox").attr('user_id', data.loaded.user_id);
                    } else {
                        $(".main .chatbox").removeAttr('user_id');
                        $(".main .chatbox").removeAttr('group_id');
                    }

                    if (data.loaded.video_chat !== undefined && data.loaded.video_chat === true) {
                        $('.main .chatbox > .header .join_video_call_icon').removeClass('d-none');

                        if (data.loaded.video_chat_online !== undefined && data.loaded.video_chat_online === true) {
                            $('.main .chatbox > .header .join_video_call_icon').addClass('online');
                        }

                        if (load.open_video_call !== undefined) {
                            $('.main .chatbox > .header .join_video_call_icon').trigger('click');
                        }

                    }

                    if (data.loaded.audio_chat !== undefined && data.loaded.audio_chat === true) {
                        $('.main .chatbox > .header .join_audio_call_icon').removeClass('d-none');

                        if (data.loaded.audio_chat_online !== undefined && data.loaded.audio_chat_online === true) {
                            $('.main .chatbox > .header .join_audio_call_icon').addClass('online');
                        }

                        if (load.open_audio_call !== undefined) {
                            $('.main .chatbox > .header .join_audio_call_icon').trigger('click');
                        }

                    }

                    if (data.loaded.messaging !== undefined && data.loaded.messaging === true) {
                        $(".main .chatbox > .footer").show();
                    } else {
                        $(".main .chatbox > .footer").hide();
                    }
                    $(".main .chatbox > .footer .messenger_features").show();

                    if (data.loaded.disable_features !== undefined) {
                        $.each(data.loaded.disable_features, function(feature_index, feature) {

                            if (feature === 'voice_message') {
                                if (!$('.main .chatbox > .footer > .editor .audio_recorder_box').hasClass('d-none')) {
                                    $('.cancel_recording').trigger('click');
                                }
                            }

                            if (feature === 'attach_files') {
                                $('.main .chatbox > .footer > .attachments > div > .files > ul').html('');
                                $('.attachments > div > .attached_files > form').html('');
                                $('.chatbox > .footer > .attachments').removeClass('hasAttachments');
                            }

                            if (feature === 'gifs') {
                                deattach_media();
                            }

                            feature = ".main .chatbox > .footer .messenger_features."+feature+"_feature";
                            $(feature).hide();
                        });
                    }

                }
            }

            if (load.load_group_info !== undefined || load.load_user_info !== undefined) {
                $('.main .chatbox > .header > .heading > .view_info').trigger('click');
            }

            if (load.prepend_messages !== undefined) {
                data.prepend_messages = true;
            } else {
                data.scrollToBottom = true;
            }

            load_messages(data);
        } else {
            console.log('ERROR : ' + data);
        }

        if (load.prepend_messages === undefined && load.append_messages === undefined) {
            if (browser_title !== undefined) {
                change_browser_title(browser_title);
                history.pushState({}, null, browser_address_bar);
            }
        }

        $('.main .middle .conversation_loader').removeClass('error').hide();
        $('.main .chatbox > .contents > .chat_messages').removeClass('loading EndResults');
    }) .fail(function(qXHR, textStatus, errorThrown) {
        if (qXHR.statusText !== 'abort' && qXHR.statusText !== 'canceled') {
            console.log('ERROR : '+errorThrown);
            console.log('PAGE CONTENT : ' + data);
            $('.main .middle .conversation_loader').addClass('error');

            if (load.prepend_messages === undefined && load.append_messages === undefined) {
                load_messages_reload_loop = load_messages_reload_loop+1;
                if (load_messages_reload_loop < 5) {
                    load_messages_reload_timeout = setTimeout(function() {
                        load_conversation_ajax_request(old_data, load)
                    }, 3500);
                } else {
                    load_messages_reload_loop = 0;
                }
            }
        }
        $('.main .chatbox > .contents > .chat_messages').removeClass('loading EndResults');
    });
}

function load_messages(data) {

    var messages = '';
    var highlight_message_element = false;
    var highlight_message_id = 0;
    var total_messages = 0

    if (data.loaded !== undefined) {

        if (data.loaded.group_id !== undefined && data.loaded.group_id != $('.main .chatbox').attr('group_id')) {
            data.messages = [];
        } else if (data.loaded.user_id !== undefined && data.loaded.user_id != $('.main .chatbox').attr('user_id')) {
            data.messages = [];
        }
    }

    if (data.messages !== undefined) {

        total_messages = data.messages.length;

        $.each(data.messages, function(key, val) {

            var message = data.messages[key];
            var add_to_list = true;

            if (message.message_id !== undefined) {
                if ($('.main .chatbox > .contents > .chat_messages > ul > li[message_id="'+message.message_id+'"]').length > 0) {
                    add_to_list = false;
                }
                if (message.highlight_message !== undefined) {
                    highlight_message_element = true;
                    highlight_message_id = message.message_id;
                }
            }

            if (add_to_list) {
                if (message.message_label === undefined) {
                    message.message_label = 'Message';
                }

                if (message.message_id === undefined) {
                    messages = messages+'<li class="set_message_date has_excerpt message '+message.class+'" message_label="'+message.message_label+'">';
                } else {
                    messages = messages+'<li class="set_message_date has_excerpt message '+message.class+'" message_label="'+message.message_label+'" message_id="'+message.message_id+'">';
                }

                if (message.date !== undefined) {
                    messages = messages+'<span class="date" message_sent_on="'+message.date+'"><span>'+message.date+'</span></span>';
                }

                messages = messages+'<div>';

                messages = messages+'<div class="left">';

                if (data.loaded !== undefined && data.loaded.multi_select !== undefined && data.loaded.multi_select) {
                    messages = messages+'<label class="selector select_item">';
                    messages = messages+'<input type="checkbox" name="message_ids[]" value="'+message.message_id+'">';
                    messages = messages+'<span class="checkmark"></span>';
                    messages = messages+'</label>';
                }

                if (message.image !== undefined) {
                    messages = messages+'<span class="image">';
                    messages = messages+'<img src="'+message.image+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';">';
                    messages = messages+'</span>';
                }

                messages = messages+'</div>';

                messages = messages+'<div class="right">';
                messages = messages+'<div class="header">';

                if (message.name_color === undefined) {
                    message.name_color = '#e91e63';
                }

                if (message.sender_user_id !== undefined) {

                    var group_id_attribute = '';

                    if (message.message_group_id !== undefined) {
                        group_id_attribute = 'data-group_identifier="'+message.message_group_id+'"';
                    }

                    messages = messages+'<span class="send_by get_info" '+group_id_attribute+' user_id="'+message.sender_user_id+'" role="button" style="color:'+message.name_color+';">';
                    messages = messages+message.posted_by+'</span>';
                } else {
                    messages = messages+'<span class="send_by" style="color:'+message.name_color+';">'+message.posted_by+'</span>';
                }


                if (message.read_status !== undefined) {
                    if (message.read_status === 'read') {
                        messages = messages+'<span class="read_status read"><i class="bi bi-check-all"></i></span>';
                    } else {
                        messages = messages+'<span class="read_status"><i class="bi bi-check d-none"></i></span>';
                    }
                }

                if (message.badge != undefined) {
                    messages = messages+'<span class="text_badge"';

                    if (message.badge.text_color != undefined && message.badge.background != undefined) {
                        messages = messages+' style="color:'+message.badge.text_color+';';
                        messages = messages+'border:0px;';
                        messages = messages+'background:'+message.badge.background+';"';
                    }

                    messages = messages+'>'+message.badge.text+'</span>';
                }

                if (message.sample_data != undefined) {
                    messages = messages+'<div class="tools">';
                } else {
                    messages = messages+'<div class="tools dropdown_button">';
                }

                if (message.time != undefined) {
                    messages = messages+'<span class="timestamp">'+message.time+'</span>';
                }

                if (message.sample_data != undefined) {
                    messages = messages+'<div class="options">';
                    messages = messages+'<span class="bi bi-clock"></span>';
                    messages = messages+'</div>';
                } else if (message.options != undefined && message.options.length != 0) {
                    messages = messages+'<div class="options">';
                    messages = messages+'<span class="iconic_arrow-down"></span>';
                    messages = messages+'<div class="dropdown_list">';
                    messages = messages+'<ul>';
                    $.each(message.options, function(optkey, optval) {
                        var attributes = message.options[optkey].attributes;
                        messages = messages+'<li ';
                        $.each(attributes, function(attrkey, attrval) {
                            messages = messages+attrkey+'="'+attributes[attrkey]+'" ';
                        });
                        messages = messages+'class="hide_onClick '+message.options[optkey].class+'">'+message.options[optkey].option+'</li>';
                    });
                    messages = messages+'</ul>';
                    messages = messages+'</div>';
                    messages = messages+'</div>';
                }

                messages = messages+'</div>';
                messages = messages+'</div>';

                var empty_message_content = true;

                if (message.content !== undefined && message.content.trim() !== '' || message.reply_message !== undefined) {
                    empty_message_content = false;
                    messages = messages+'<div class="content">';
                    messages = messages+'<div>';

                    if (message.reply_message !== undefined) {
                        var attached_message = '';

                        attached_message += 'message_id="'+message.parent_message_id+'" ';
                        messages = messages+'<div class="quote load_message" '+attached_message+'>';
                        messages = messages+'<span class="transparent_layer"></span>';
                        messages = messages+'<div>';
                        messages = messages+'<div class="left">';
                        messages = messages+'<div class="send_by">'+message.attached_message_author+'</div>';
                        messages = messages+'<div class="text">'+message.reply_message+'</div>';
                        messages = messages+'</div>';

                        if (message.reply_thumbnail !== undefined) {
                            messages = messages+'<div class="right">';
                            messages = messages+'<div class="thumbnail">';
                            messages = messages+'<img src="'+message.reply_thumbnail+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';
                            messages = messages+'</div>';
                            messages = messages+'</div>';
                        }

                        messages = messages+'</div></div>';
                    }

                    if (message.content !== undefined && message.content.trim() !== '') {
                        messages = messages+'<div class="message_content">';
                        messages = messages+message.content
                        messages = messages+'</div>';
                        messages = messages+'<span class="read_more_toggle">';
                        messages = messages+'<span><i class="bi bi-chevron-double-down"></i></span>';
                        messages = messages+'</span>';
                    }

                    messages = messages+'</div>';
                    messages = messages+'</div>';
                }

                if (message.attachments !== undefined) {

                    var additional_class = '';

                    if (empty_message_content) {
                        additional_class = 'empty_message_content';
                    }

                    messages = messages+'<div class="files '+additional_class+'">';
                    messages = messages+'<div>';
                    messages = messages+'<ul class="'+message.attachment_type+'">';
                    var single_image_class = 'single_image';

                    if (message.attachments.length > 1) {
                        single_image_class = '';
                    }

                    $.each(message.attachments, function(attachment_index, attachment_value) {

                        var attachment = message.attachments[attachment_index];

                        if (attachment.image !== undefined) {
                            var image_attributes = '';

                            if (attachment.image_size !== undefined && message.attachments.length === 1) {
                                image_attributes = 'style="width:'+attachment.image_size.width+'px; height:'+attachment.image_size.height+'px;"';
                            }

                            messages = messages+'<li>';
                            messages = messages+'<span class="file image '+single_image_class+'" '+image_attributes+'>';
                            messages = messages+'<img class="preview_image" original="'+attachment.original+'" src="'+attachment.image+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';

                            if (message.attachment_type === 'image_files' && attachment.download_file !== undefined) {
                                var download_attributes = '';

                                $.each(attachment.download_file, function(download_attr_key, download_attr_value) {
                                    download_attributes = download_attributes+download_attr_key+'="'+download_attr_value+'" ';
                                });
                                messages += '<span class="file_download">';
                                messages += '<span class="download_file" download="attachment" '+download_attributes+'><i class="bi bi-arrow-down-circle-fill"></i></span>';
                                messages += '</span>';
                            }

                            messages = messages+'</span>';
                            messages = messages+'</li>';
                        } else if (message.attachment_type === 'video_files' && attachment.thumbnail !== undefined) {

                            messages = messages+'<li>';
                            messages = messages+'<span class="file video">';
                            messages = messages+'<img src="'+attachment.thumbnail+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';
                            messages = messages+'<span class="play_icon preview_video" video_file="'+attachment.video+'" mime_type="'+attachment.file_type+'" thumbnail="'+attachment.thumbnail+'">';
                            messages = messages+'<i class="bi bi-play-fill"></i></span>';

                            if (attachment.download_file !== undefined) {
                                var download_attributes = '';

                                $.each(attachment.download_file, function(download_attr_key, download_attr_value) {
                                    download_attributes = download_attributes+download_attr_key+'="'+download_attr_value+'" ';
                                });
                                messages += '<span class="file_download">';
                                messages += '<span class="download_file" download="attachment" '+download_attributes+'>';
                                messages += '<i class="bi bi-arrow-down-circle-fill"></i>';
                                messages += '</span>';
                                messages += '</span>';
                            }

                            messages = messages+'</span>';

                            messages = messages+'</li>';

                        } else if (message.attachment_type === 'user_location') {

                            messages = messages+'<li>';
                            messages = messages+'<div class="user_location generate_user_location" lat="'+attachment.latitude+'" long="'+attachment.longitude+'">';
                            messages = messages+'</div>';

                            messages = messages+'</li>';

                        } else if (message.attachment_type === 'url_meta') {
                            var url_attributes = 'class="url_meta open_link" link="'+attachment.url+'" target="_blank"';
                            messages = messages+'<li>';

                            if (attachment.mime_type === 'video/vimeo' || attachment.mime_type === 'video/youtube' || attachment.mime_type === 'video/dailymotion') {
                                url_attributes = 'class="url_meta preview_video" mime_type="'+attachment.mime_type+'" video_url="'+attachment.url+'" thumbnail="'+attachment.meta_image+'"';
                            } else if (attachment.iframe_embed !== undefined) {
                                url_attributes = 'class="url_meta iframe_embed" embed_url="'+attachment.iframe_embed+'"';

                                if (attachment.iframe_class !== undefined) {
                                    url_attributes = url_attributes+' iframe_class="'+attachment.iframe_class+'"';
                                }

                                if (attachment.iframe_relative_height !== undefined) {
                                    url_attributes = url_attributes+' iframe_relative_height="'+attachment.iframe_relative_height+'"';
                                }
                            }
                            messages = messages+'<span '+url_attributes+'>';
                            messages = messages+'<span class="image">';
                            messages = messages+'<img src="'+attachment.meta_image+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';

                            if (attachment.mime_type === 'video/vimeo' || attachment.mime_type === 'video/youtube' || attachment.mime_type === 'video/dailymotion') {
                                messages = messages+'<span class="play_icon">';
                                messages = messages+'<i class="bi bi-play-fill"></i>';
                                messages = messages+'</span>';
                            }

                            messages = messages+'</span>';

                            messages = messages+'<span class="details">';
                            messages = messages+'<span class="title">'+attachment.meta_title+'</span>';
                            messages = messages+'<span class="description">'+attachment.meta_description+'</span>';
                            messages = messages+'</span>';

                            messages = messages+'</span>';

                            messages = messages+'</li>';

                        } else if (message.attachment_type === 'audio_files' || message.attachment_type === 'audio_message') {
                            messages = messages+'<li>';

                            messages = messages+'<div class="file audio_preview"><div>';

                            messages = messages+'<div class="audio_controls">';

                            messages = messages+'<div><div><div>';

                            messages = messages+'<span class="play_button" audio="'+attachment.audio_file+'" mime_type="'+attachment.file_type+'">';
                            messages = messages+'<i class="bi bi-play-fill"></i></span>';

                            messages = messages+'<span class="current_timestamp"><span>00:00</span></span> ';

                            messages = messages+'<div class="control"><div> ';
                            messages = messages+'<input type="range" min="1" max="100" value="1" class="audio_player_range_control audio_preview_seekbar"> ';
                            messages = messages+'</div></div>';

                            messages = messages+'<span class="duration"> <span>00:00</span> </span>';

                            messages = messages+'<div class="volume d-none"> ';
                            messages = messages+'<div class="control" style="display: none;"> ';
                            messages = messages+'<div> <div> ';
                            messages = messages+'<input type="range" min="1" max="100" value="1" class="audio_player_range_control">';
                            messages = messages+'</div> </div> </div>';
                            messages = messages+'<span class="icon"><i class="bi bi-volume-down-fill"></i></span>';
                            messages = messages+'</div> </div>';



                            messages = messages+'</div> </div>';

                            messages = messages+'</div>';

                            if (message.attachment_type === 'audio_files') {
                                if (attachment.download_file !== undefined) {

                                    var download_attributes = '';

                                    $.each(attachment.download_file, function(download_attr_key, download_attr_value) {
                                        download_attributes = download_attributes+download_attr_key+'="'+download_attr_value+'" ';
                                    });

                                    messages = messages+'<div class="icon download_file" download="attachment" '+download_attributes+'> ';
                                    messages = messages+'<i class="bi bi-arrow-down"></i>';
                                    messages = messages+'</div>';
                                } else {
                                    messages = messages+'<div class="icon"> ';
                                    messages = messages+'<i class="bi bi-music-note"></i>';
                                    messages = messages+'</div>';
                                }
                            } else {
                                messages = messages+'<div class="icon"> ';
                                messages = messages+'<i class="bi bi-mic-fill"></i>';
                                messages = messages+'</div>';
                            }


                            messages = messages+'</div></div>';

                            messages = messages+'</li>';
                        } else if (attachment.file_name !== undefined) {
                            messages = messages+'<li>';
                            messages = messages+'<span class="file others"><span>';

                            if (message.attachment_type === 'pdf_files' && attachment.thumbnail !== undefined) {

                                messages = messages+'<span class="icon pdf_preview_thumnail">';
                                var pdf_preview_tag = 'class="preview_pdf" pdf="'+attachment.preview_pdf+'"';

                                messages = messages+'<img '+pdf_preview_tag+' src="'+attachment.thumbnail+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';
                                messages = messages+'</span>';

                            } else if (attachment.file_icon !== undefined) {

                                messages = messages+'<span class="icon">';
                                messages = messages+'<img src="'+attachment.file_icon+'" onerror="this.onerror=null; this.src='+"'"+blur_img_url+"'"+';"/>';
                                messages = messages+'</span>';
                            }

                            messages = messages+'<span class="file_details"><span class="name">'+attachment.file_name+'</span>';
                            if (attachment.file_size !== undefined) {
                                messages = messages+'<span class="subtitle">'+attachment.file_size+'</span>';
                            } else if (attachment.subtitle !== undefined) {
                                messages = messages+'<span class="subtitle">'+attachment.subtitle+'</span>';
                            }
                            messages = messages+'</span>';

                            if (attachment.download_file !== undefined) {
                                var download_attributes = '';

                                $.each(attachment.download_file, function(download_attr_key, download_attr_value) {
                                    download_attributes = download_attributes+download_attr_key+'="'+download_attr_value+'" ';
                                });
                                messages += '<span class="file_download">';
                                messages += '<span class="download_file" download="attachment" '+download_attributes+'><i class="bi bi-arrow-down-circle-fill"></i></span>';
                                messages += '</span>';
                            }

                            messages = messages+'</span></span>';

                            messages = messages+'</li>';
                        }

                        messages = messages+'</li>';
                    });


                    messages = messages+'</ul>';
                    messages = messages+'</div>';
                    messages = messages+'</div>';
                }

                if (message.audio_message != undefined) {

                    messages = messages+'<li>';
                    messages = messages+'<span class="file video"></span>';
                    messages = messages+'</li>';
                    messages = messages+'<div class="audio_file">';
                    messages = messages+'<div>';
                    messages = messages+'</div>';
                    messages = messages+'</div>';
                }

                if (data.loaded !== undefined && data.loaded.react_messages !== undefined && data.loaded.react_messages) {
                    var react_messages = true;
                } else {
                    var react_messages = false;
                }

                if (!message.system_message && message.reactions !== undefined && message.reactions.total_reactions !== undefined || !message.system_message && react_messages) {

                    var message_reactions_class = '';

                    if (message.reactions.total_reactions === undefined) {
                        message_reactions_class = ' d-none';
                    }

                    messages = messages+'<div class="message_reactions'+message_reactions_class+'">';

                    if (react_messages) {
                        messages = messages+'<div class="selection d-none">';
                        messages = messages+'<ul>';

                        var reactions = ['like',
                            'haha',
                            'love',
                            'angry',
                            'wow',
                            'sad'];
                        var reaction_class = '';

                        $.each(reactions, function(r_index, reaction) {

                            reaction_class = 'reaction reaction-'+reaction;

                            if (message.reactions.user_reaction !== undefined && reaction == message.reactions.user_reaction) {
                                reaction_class += ' reacted';
                            }

                            if (data.loaded.group_id !== undefined) {
                                messages = messages+'<li class="api_request" data-reaction="'+reaction+'" data-add="group_message_reaction"';
                                messages = messages+' data-group_message_id="'+message.message_id+'">';
                            } else {
                                messages = messages+'<li class="api_request" data-reaction="'+reaction+'" data-add="private_chat_message_reaction"';
                                messages = messages+' data-private_chat_message_id="'+message.message_id+'">';
                            }

                            messages = messages+'<span class="'+reaction_class+'"></span>';
                            messages = messages+'</li>';
                        });

                        messages = messages+'</ul>';
                        messages = messages+'</div>';
                    }

                    messages = messages+'<div class="reactions">';
                    messages = messages+'<ul>';

                    if (message.reactions.total_reactions !== undefined) {

                        $.each(message.reactions.total_reactions, function(reaction, total) {
                            messages = messages+'<li>';
                            messages = messages+'<span class="reaction reaction-'+reaction+'"></span>';
                            messages = messages+'<span class="total_reactions">'+total+'</span>';
                            messages = messages+'</li>';
                        });
                    } else if (react_messages) {
                        // messages = messages+'<li>';
                        // messages = messages+'<span class="reaction reaction-react"></span>';
                        // messages = messages+'</li>';
                    }
                    messages = messages+'</ul>';
                    messages = messages+'</div>';
                    messages = messages+'</div>';
                }

                messages = messages+'</div>';
                messages = messages+'</div>';

                messages = messages+'</li>';
            }
        });
    }

    var append_messages = false;

    if (data.append !== undefined || data.append_messages !== undefined) {
        append_messages = true;
        $('.main .chatbox > .contents > .chat_messages > ul').prepend(messages);
    } else if (data.prepend_messages !== undefined) {
        $('.main .chatbox > .contents > .chat_messages > ul').append(messages);
    } else {
        $('.main .chatbox > .contents > .chat_messages > ul').html(messages);
        $('.main .chatbox .selector').addClass('d-none');
    }

    if (!$('.main .chatbox .message_selection > .selector').is(":visible")) {
        $('.main .chatbox .selector').addClass('d-none');
    }

    append_read_more();
    set_message_dates(append_messages);
    generate_user_location();

    if (data.scrollToBottom !== undefined && total_messages > 0) {
        $('.main .chatbox > .contents > .chat_messages').scrollTop(0);
    } else if (highlight_message_element) {
        highlight_message(highlight_message_id);
    }

}

$('body').on('click', ".main .chatbox > .header > .icons .toggle_checkbox", function(e) {

    if ($('.main .chatbox .selector').length > 0) {
        $('.main .chatbox .selector').toggleClass('d-none multi_selection');
        $('.main .chatbox').toggleClass('chat_message_selection');
    }

    if ($('.main .chatbox > .header > div > .selector').hasClass('d-none')) {
        $('.main .chatbox .selector > input[type="checkbox"]').prop('checked', false);
        $(".main .chatbox > .header > .icons > span.delete_multiple_messages").addClass('d-none');
    }

});

function remove_messages(data) {
    var message_element = '';
    var noerror = true;
    if (data !== undefined) {

        if (data.conversation_type !== undefined) {
            if (data.conversation_type === 'group_chat' && $(".main .chatbox").attr('group_id') === undefined) {
                noerror = false;
            } else if (data.conversation_type === 'private_chat' && $(".main .chatbox").attr('user_id') === undefined) {
                noerror = false;
            }
        }

        if (noerror && data.message_id !== undefined) {
            if (Array.isArray(data.message_id)) {
                $.each(data.message_id, function(index, message_id) {
                    message_element = $('.main .chatbox > .contents > .chat_messages > ul > li[message_id="'+message_id+'"]');
                    message_element.remove();
                });
            } else {
                message_element = $('.main .chatbox > .contents > .chat_messages > ul > li[message_id="'+data.message_id+'"]');
                message_element.remove();
            }
        }
    }
}

function generate_user_location() {
    var jsFileUrl = 'https://unpkg.com/leaflet/dist/leaflet.js';

    if (!window.isLeafletLoaded) {
        addCssFile('https://unpkg.com/leaflet/dist/leaflet.css');
        $.getScript(jsFileUrl, function() {
            window.isLeafletLoaded = true;
            generate_user_location_callback();
        });
    } else {
        generate_user_location_callback();
    }
}

function generate_user_location_callback() {

    if (typeof L !== 'undefined' && typeof L.map === 'function') {
        $('.main .chat_messages > ul > li > div > .right > .files > div > .user_location > li > .generate_user_location').each(function() {
            $(this).html('<div class="map_wrapper"> <div class="map"></div> <div class="open_map"></div> </div> ');
            $(this).removeClass('generate_user_location');

            var latitude = $(this).attr('lat');
            var longitude = $(this).attr('long');
            var map_link = `https://www.google.com/maps?q=${latitude},${longitude}`;

            $(this).find('.map_wrapper > .open_map').addClass('open_link');
            $(this).find('.map_wrapper > .open_map').attr('target', '_blank');
            $(this).find('.map_wrapper > .open_map').attr('link', map_link);

            var mapContainer = $(this).find('.map_wrapper .map')[0];
            var map = L.map(mapContainer, {
                zoomControl: false
            }).setView([latitude, longitude], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            L.marker([latitude, longitude]).addTo(map);

        });
    }
}

function mention_everyone(realtime_log_id) {
    var data = {
        add: 'notify_group_members',
        realtime_log_id: realtime_log_id,
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
        if (isJSON(data)) {
            data = $.parseJSON(data);

            if (data.continue_process !== undefined) {
                mention_everyone(realtime_log_id);
            }

        } else {
            console.log('ERROR : ' + data);
        }

    }) .fail(function(qXHR, textStatus, errorThrown) {
        console.log('ERROR : ' + errorThrown);
    });
}

function update_message_reactions(data) {

    if (data !== undefined) {

        var update_reactions = false;

        if (data.message_id !== undefined) {

            if (data.group_id !== undefined && $(".main .chatbox").attr('group_id') !== undefined) {
                if ($(".main .chatbox").attr('group_id') == data.group_id || $(".main .chatbox").attr('group_id') == 'all') {
                    update_reactions = true;
                }
            } else if (data.user_ids !== undefined && $(".main .chatbox").attr('user_id') !== undefined) {
                update_reactions = true;
            }

            if (update_reactions) {
                var message_element = $('.main .chatbox > .contents > .chat_messages > ul > li[message_id="'+data.message_id+'"]');
                var update_content = '';

                message_element.find('.message_reactions > .selection > ul > li > .reaction').removeClass('reacted');

                if (data.user_reaction !== undefined) {
                    message_element.find('.message_reactions > .selection > ul > li > .reaction').removeClass('reacted');
                    var user_reaction_class = '.reaction-'+data.user_reaction;
                    message_element.find('.message_reactions > .selection > ul > li > .reaction'+user_reaction_class).addClass('reacted');
                }

                var total_reactions = 0;

                if (data.total_reactions !== undefined) {
                    total_reactions = Object.keys(data.total_reactions).length;
                }

                if (total_reactions > 0) {
                    $.each(data.total_reactions, function(reaction, total) {
                        update_content = update_content+'<li>';
                        update_content = update_content+'<span class="reaction reaction-'+reaction+'"></span>';
                        update_content = update_content+'<span class="total_reactions">'+total+'</span>';
                        update_content = update_content+'</li>';
                    });
                } else {
                    if ($(".main .chatbox").attr('react_messages') !== undefined) {
                        // update_content = update_content+'<li>';
                        // update_content = update_content+'<span class="reaction reaction-react"></span>';
                        // update_content = update_content+'</li>';
                    }
                }

                message_element.find('.message_reactions > .reactions > ul').html(update_content);

                if (total_reactions > 0) {
                    message_element.find('.message_reactions').removeClass('d-none');
                }
            }
        }
    }
}



$('body').on('click', '.main .chatbox .selector.select_all > input', function(e) {
    $('.main .chatbox .selector.select_item > input').prop('checked', this.checked).change();
});


$('body').on('change', '.main .chatbox .selector.select_item > input', function(e) {
    if ($(".main .chatbox .selector.select_item > input:checked").length > 0) {
        $(".main .chatbox > .header > .icons > span.delete_multiple_messages").removeClass('d-none');
    } else {
        $(".main .chatbox > .header > .icons > span.delete_multiple_messages").addClass('d-none');
    }
});


$('.main .chat_messages').on('click', '.message_reactions > .selection > ul > li', function(e) {
    $('.main .chatbox > .contents > .chat_messages .message_reactions > .selection').addClass('d-none');
});

$('.main .chat_messages').on('click', '.message_reactions > .reactions > ul > li > .reaction', function(e) {

    if ($(this).parent().parent().parent().parent().find('.selection').hasClass('d-none')) {
        $('.main .chatbox > .contents > .chat_messages .message_reactions > .selection').addClass('d-none');
        $(this).parent().parent().parent().parent().find('.selection').removeClass('d-none');
    } else {
        $('.main .chatbox > .contents > .chat_messages .message_reactions > .selection').addClass('d-none');
    }
});

$('.main').on('click', '.middle', function(e) {
    var hide_reaction_selection = true;

    if ($(e.target).hasClass('react_to_messages')) {
        hide_reaction_selection = false;
    } else if ($(e.target).parents('.reactions').hasClass('reactions')) {
        hide_reaction_selection = false;
    }

    if (hide_reaction_selection) {
        $('.main .chatbox > .contents > .chat_messages .message_reactions > .selection').addClass('d-none');
    }
});

$('.main .chat_messages').on('click', '.right > .header > .tools > div .dropdown_list > ul > li.react_to_messages', function(e) {

    $reaction_element = $(this).parent().parent().parent().parent().parent().parent().find('.message_reactions');

    if ($reaction_element.find('.selection').hasClass('d-none')) {
        $('.main .chatbox > .contents > .chat_messages .message_reactions > .selection').addClass('d-none');
        $reaction_element.find('.selection').removeClass('d-none');

        if ($reaction_element.hasClass('d-none')) {
            $reaction_element.addClass('reaction_d-none');
            $reaction_element.removeClass('d-none');
        }

    } else {
        if ($reaction_element.hasClass('reaction_d-none')) {
            $reaction_element.removeClass('reaction_d-none');
            $reaction_element.addClass('d-none');
        }
        $('.main .chatbox > .contents > .chat_messages .message_reactions > .selection').addClass('d-none');
    }
});


function set_message_dates(append_messages = false) {
    $('.main .chatbox > .contents > .chat_messages > ul > li.set_message_date').each(function() {
        var message_sent_on = '';

        if ($(this).find('.date').attr('message_sent_on') !== undefined) {
            message_sent_on = $(this).find('.date').attr('message_sent_on');

            if (!append_messages) {
                $('.main .chatbox > .contents > .chat_messages > ul > li > .date[message_sent_on="'+message_sent_on+'"]').removeClass('show');
                $(this).find('.date').addClass('show');
            } else {
                if ($('.main .chatbox > .contents > .chat_messages > ul > li > .date[message_sent_on="'+message_sent_on+'"]').length < 2) {
                    $(this).find('.date').addClass('show');
                }
            }
        }

        $(this).removeClass('set_message_date');
    });
}

function append_read_more() {

    var read_more_criteria = 300;

    if ($('.main .chatbox > .contents').attr('read_more_criteria') !== undefined) {
        read_more_criteria = parseInt($('.main .chatbox > .contents').attr('read_more_criteria'));

        if (isNaN(read_more_criteria)) {
            read_more_criteria = 300;
        }
    }

    $('.main .chatbox > .contents > .chat_messages > ul > li.has_excerpt').each(function() {
        if ($(this).find('div > .right > .content > div > .message_content').outerHeight() >= read_more_criteria) {
            $(this).find('div > .right > .content > div > .message_content').css('max-height', read_more_criteria+'px');
            $(this).addClass('excerpt');
        }
        $(this).removeClass('has_excerpt');
    });
}

$('.main .chat_messages').on('click', 'li.excerpt .content > div > .read_more_toggle', function(e) {
    $(this).parent().find('.message_content').css('max-height', '');
    $(this).parents('li.excerpt').removeClass('excerpt');
});

$('.main .chat_messages').on('click', '.play_button', function(e) {

    if ($(this).find('i').hasClass('bi-stop-fill')) {
        $('.main .chatbox > .contents > .chat_messages .file.audio_preview').removeClass('current_playing');
        audio_message_preview.pause();
        audio_message_preview.currentTime = 0;
    } else {
        var audio_tag = $('#audio_message_preview');

        audio_tag.find('source').attr('src', $(this).attr('audio'));

        if ($(this).attr('mime_type') !== undefined) {
            audio_tag.find('source').attr('type', $(this).attr('mime_type'));
        } else {
            audio_tag.find('source').removeAttr('type');
        }

        $(".main .chat_messages .audio_controls > div > div > div > .current_timestamp > span").text('00:00');
        $(".main .chat_messages .audio_controls > div > div > div > .duration > span").text('00:00');
        $(".audio_preview_seekbar").val(0).trigger("input");
        $('.main .chatbox > .contents > .chat_messages .file.audio_preview').removeClass('current_playing');
        $(this).parents('.audio_preview').addClass('current_playing');
        audio_message_preview.pause();
        audio_message_preview.load();
        audio_message_preview.play();
    }
});


audio_message_preview.addEventListener('play', function() {

    if (audio_player !== undefined && audio_player !== null) {
        audio_player.pause();
    }

    if (video_preview !== undefined && video_preview !== null) {
        video_preview.pause();
    }

    $('.main .chat_messages .play_button > i').removeClass('bi-stop-fill');
    $('.main .chat_messages .play_button > i').addClass('bi-play-fill');
    $('.main .chat_messages .current_playing .play_button > i').removeClass('bi-play-fill');
    $('.main .chat_messages .current_playing .play_button > i').addClass('bi-stop-fill');
});

audio_message_preview.addEventListener('pause', function() {
    $('.main .chat_messages .play_button > i').removeClass('bi-stop-fill');
    $('.main .chat_messages .play_button > i').addClass('bi-play-fill');
});


audio_message_preview.addEventListener('ended', function() {
    $('.main .chat_messages .play_button > i').removeClass('bi-stop-fill');
    $('.main .chat_messages .play_button > i').addClass('bi-play-fill');
});

audio_message_preview.ontimeupdate = function() {


    if (isFinite(audio_message_preview.currentTime)) {
        var current_timestamp = timestamp_convertor(audio_message_preview.currentTime);
        $(".main .chat_messages .current_playing .audio_controls > div > div > div > .current_timestamp > span").text(current_timestamp);
    }

    if (isFinite(audio_message_preview.duration)) {
        var audio_duration = timestamp_convertor(audio_message_preview.duration);
        $(".main .chat_messages .current_playing .audio_controls > div > div > div > .duration > span").text(audio_duration);
    }

    var percentage = (audio_message_preview.currentTime / audio_message_preview.duration) * 100;
    if (isFinite(percentage)) {
        $(".current_playing .audio_preview_seekbar").val(percentage).trigger("input");
    }
};


$('.main').on('mouseup', '.current_playing .audio_preview_seekbar', function(e) {
    audio_message_preview.play();
});

$('.main').on('mousedown touchstart', '.current_playing .audio_preview_seekbar', function(e) {
    audio_message_preview.pause();
});

$('.main').on('mouseenter', '.chatbox > .contents > .chat_messages.show_timestamp_on_mouseover > ul > li', function(e) {
    $(this).find('.header > .tools > .timestamp').addClass('visible');
});

$('.main').on('mouseleave', '.chatbox > .contents > .chat_messages.show_timestamp_on_mouseover > ul > li', function(e) {
    $('.chat_messages.show_timestamp_on_mouseover > ul > li .header > .tools > .timestamp').removeClass('visible');
});

$('.main').on('click', '.current_playing .audio_preview_seekbar', function(e) {
    var offset = $(this).offset();
    var left = (e.pageX - offset.left);
    var totalWidth = $(this).width();
    var percentage = (left / totalWidth);
    var audioTime = audio_message_preview.duration * percentage;
    audio_message_preview.currentTime = audioTime;
});

$('.main').on('touchend', '.current_playing .audio_preview_seekbar', function(e) {
    var offset = $(this).offset();
    var left = (e.changedTouches[0].pageX - offset.left);
    var totalWidth = $(this).width();
    var percentage = (left / totalWidth);
    var audioTime = audio_message_preview.duration * percentage;
    audio_message_preview.currentTime = audioTime;
    audio_message_preview.play();
});


function getscrenshotBlobId(blob) {
    return blob.size.toString();
}


$(document).on('paste', function(event) {
    if (!$('.main .chatbox').hasClass('d-none') && !$('.main .chatbox > .footer').hasClass('d-none')) {
        var items = (event.clipboardData || event.originalEvent.clipboardData).items;
        var blob = null;
        for (var i = 0; i < items.length; i++) {
            if (items[i].type.indexOf("image") === 0) {
                blob = items[i].getAsFile();
                break;
            }
        }

        if (blob) {

            var blobId = getscrenshotBlobId(blob);

            if (shared_screenshot_BlobIds.includes(blobId)) {
                console.log("This screenshot has been previously shared.");
                return;
            }

            shared_screenshot_BlobIds.push(blobId);

            var reader = new FileReader();
            reader.onload = function(event) {
                var content = {
                    'screenshot': event.target.result,
                    'scrollToBottom': true,
                };

                send_message(content);
            };
            reader.readAsDataURL(blob);
        }
    }
});