var filter_text_on_paste = false;
var user_allowed_file_types = system_variable('allowed_file_types');
var send_message_button = function(context) {
    var ui = $.summernote.ui;
    var button = ui.button({
        contents: '<span class="send_message_btn"/> Send',
        click: function() {
            send_message();
        }
    });

    return button.render();
}

$("body").on('click', '.main .chatbox > .contents > .chat_messages', function(e) {

    var stop_hide_process = false;

    if ($(e.target).hasClass('tools') || $(e.target).parents().hasClass('tools')) {
        stop_hide_process = true;
    }

    if (!$('.grid_list').hasClass('hidden') && !stop_hide_process) {
        if (!$('.chatbox > .footer > .grid_list').hasClass('hidden') && $('.chatbox > .footer > .attachments').hasClass('hasAttachments')) {
            open_module('.attachments', '.chatbox > .footer');
        } else {
            open_module('.grid_list', '.chatbox > .footer');
        }
    }
});

$("body").on('click', '.main .chatbox > .footer > .editor .send_message_button .send_message', function(e) {
    $(".main .chatbox > .footer > .editor .message_editor .note-editor .note-toolbar").hide();
    send_message();
});

$("body").on('click', '.main .add_to_editor', function(e) {
    if ($(this).attr('content') !== undefined) {
        $('#message_editor').summernote('restoreRange');
        $('#message_editor').summernote('insertText', $(this).attr('content'));
    }
});


$("body").on('click', '.main .chatbox > .footer > .editor .toggle_message_toolbar > div > div.toggle_toolbar_button_old', function(e) {
    $('.main .chatbox > .footer > .editor').toggleClass('show_toolbar');
});


$("body").on('click', '.main .chatbox > .footer > .editor .trigger_attach_files', function(e) {
    var identifier = 'user_input_' + RandomString(6);
    var input_attributes = '';

    if ($.trim(user_allowed_file_types) !== '') {
        input_attributes = ' accept="'+user_allowed_file_types+'" ';
    }

    var new_file_input = '<input type="file" '+input_attributes+' multiple name="file_attachments[]" class="file_attachments '+identifier+'"/>';
    $('.attachments > div > .attached_files > form').append(new_file_input);
    $('.'+identifier).hide();
    $('.'+identifier).trigger('click');
});


$("body").on('click', '.main .chatbox > .footer > .editor .format_text_message', function(e) {
    $('#message_editor').summernote('restoreRange');
    $(".main .chatbox > .footer > .editor .message_editor .note-editor .note-toolbar").toggle();
    $('#message_editor').summernote('focus');
});


$("body").on('click', '.main .chatbox > .footer > .editor .msg_attach_options', function(e) {
    $(".main .chatbox > .footer > .editor .message_editor .note-editor .note-toolbar").hide();
});


$(document).ready(function() {
    if ($('#message_editor').length > 0) {
        $('#message_editor').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
            ],
            icons: {
                bold: "iconic_bold",
                italic: "iconic_italic",
                underline: "iconic_underline",
                unorderedlist: "iconic_list",
                orderedlist: "iconic_list-numbers",
            },
            buttons: {},
            popover: {
                image: [],
            },
            placeholder: language_string('message_textarea_placeholder'),
            codeviewFilter: true,
            disableDragAndDrop: true,
            disableResizeImage: true,
            disableResizeEditor: true,
            maxHeight: '150px',
            tooltip: false,
            hintDirection: 'top',
            hint: [{
                match: /\B@(\w*)$/,
                search: function (keyword, callback) {

                    if ($(".main .chatbox").attr('group_id') !== undefined && keyword.length > 0) {

                        var post_data = {
                            load: 'group_members_mentions',
                            search: keyword,
                            group_id: $(".main .chatbox").attr('group_id')
                        };

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
                            dataType: "json",
                            async: false
                        }).done(function (users) {
                            callback($.grep(users, function (user) {
                                if (user.name.toLowerCase().indexOf(keyword.toLowerCase()) == 0 || user.username.toLowerCase().indexOf(keyword.toLowerCase()) == 0) {
                                    return user;
                                }
                            }));
                        });
                    }

                },

                template: function (user) {
                    return '<span class="search_group_users"><span><img src="'+ user.avatar+'"/></span>' + user.name+'</span>';
                },
                content: function (user) {
                    var mention_content = '@[' + user.username+']';
                    return mention_content;
                }
            }],
            callbacks:
            {
                onChange: function(contents, $editable) {},
                onPaste: function(e) {

                    if (filter_text_on_paste) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    }
                },
                onKeydown: function(e) {


                    if (system_variable('enter_is_send') === 'enable') {
                        if (e.keyCode == 13 && !e.shiftKey) {

                            var restricted_nodes = ['UL', 'OL', 'LI'];
                            var enter_is_send = true;

                            var element_parents = [];
                            element_parents[1] = window.getSelection().anchorNode.parentNode.nodeName;
                            element_parents[2] = window.getSelection().anchorNode.parentNode.parentNode.nodeName;
                            element_parents[3] = window.getSelection().anchorNode.parentNode.parentNode.parentNode.nodeName;
                            element_parents[4] = window.getSelection().anchorNode.parentNode.parentNode.parentNode.parentNode.nodeName;


                            $.each(element_parents, function(key, value) {
                                var index = $.inArray(value, restricted_nodes);
                                if (index != -1) {
                                    enter_is_send = false;
                                }
                            });

                            if ($('.message_editor .note-popover.bottom.note-hint-popover').is(":visible")) {
                                enter_is_send = false;
                            }

                            if ($('.message_editor .note-popover.popover.in.note-hint-popover').is(":visible")) {
                                enter_is_send = false;
                            }

                            if (enter_is_send) {
                                e.preventDefault();
                                $('.main .chatbox > .footer > .editor .send_message_button .send_message').trigger('click');
                            }
                        }
                    }

                    var max_message_length = 0;
                    var totalCharacters = e.currentTarget.innerText;

                    if ($('.main .chatbox > .footer > .editor').attr('max_message_length') !== undefined) {
                        max_message_length = parseInt($('.main .chatbox > .footer > .editor').attr('max_message_length'));

                        if (isNaN(max_message_length)) {
                            max_message_length = 0;
                        }
                    }

                    if (max_message_length != 0 && totalCharacters.trim().length >= max_message_length) {
                        if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey)) e.preventDefault();
                    }
                },

            }
        }).on('summernote.keydown', function(e) {
            typing_indicator();
            $('#message_editor').summernote('saveRange');
        });
    }
});

function CleanHTML(input) {

    var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
    var output = input.replace(stringStripper, ' ');

    var commentSripper = new RegExp('<!--(.*?)-->', 'g');
    var output = output.replace(commentSripper, '');
    var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>', 'gi');

    output = output.replace(tagStripper, '');

    var badTags = ['style', 'script', 'applet', 'embed', 'noframes', 'noscript'];

    for (var i = 0; i < badTags.length; i++) {
        tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
        output = output.replace(tagStripper, '');
    }

    var badAttributes = ['style', 'start'];
    for (var i = 0; i < badAttributes.length; i++) {
        var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"', 'gi');
        output = output.replace(attributeStripper, '');
    }
    return output;
}