var baseurl = $('base').eq(0).attr('href');
var api_request_url = baseurl+'api_request/';
var default_meta_title = decode_specialchars($("meta[name='default-title']").attr("content"));
var meta_title_timeout = null;
var user_typing_log_request = null;
var user_typing_log_timeout = null;
var users_typing_timeout = null;
var user_csrf_token = null;
var search_on_change_of_input = false;
var user_login_session_id = WebStorage('get', 'login_session_id');
var user_access_code = WebStorage('get', 'access_code');
var user_session_time_stamp = WebStorage('get', 'session_time_stamp');
var remove_login_session = WebStorage('get', 'remove_login_session');
var blur_img_url = baseurl+'assets/files/defaults/image_thumb.jpg';
var blur_img_url = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAcFBQYFBAcGBQYIBwcIChELCgkJChUPEAwRGBUaGRgVGBcbHichGx0lHRcYIi4iJSgpKywrGiAvMy8qMicqKyr/2wBDAQcICAoJChQLCxQqHBgcKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKir/wAARCADIAMgDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD56d23n5j19aTc394/nSsPnP1puKYC7m/vH86Nzep/OjFJigBdzf3j+dG9vU/nSUUDHbm9T+dLub1P502lAoAdub+8fzo3N/eP50YpaBhub1P50m5v7x/OlxRigBNzf3j+dJub1P50uKTFAhCzf3j+dJub+8fzpSKTFAhNzf3j+dJub+8fzpcUmKAE3t/eP50m9v7x/OlIpCKAE3N/eP50m9v7x/OlpKQBvb+8fzpN7f3j+dFJQAu5v7x/OikooAtOPnP1pMVI6/OfrSbaYxlJT8UYpAMpcU7FGKBiYpQKMUuKQwpaXFLigYmKMUuKXFMQwikxUmKbimIZikxUm2k20EkeKTFS7aTbQIiIpuKlK0hFAEWKTFSEU0igBmKSn4pMUgG0UtFAGg6/OfrTStTuvzn60wrSNCEikxU22k20gIsUbakxRtouOwzFLin7aXbSuVYYBS4p4Wl20XCwzFGKk20oWmJoj20bal20uyqIZDto21NspdlMkr7aTZVnZSFKZJWK0wrVopTClICsVphWrBWmFaAICKTFSlaaRQMjxRTsUUgNh0+Y/WoytWnX5jUZWoNSuVppWpytNK0rlWIdtG2pdtLtqblpEW2l21LtpQtK5XKRhaULUoSnBKLj5SIJTglShKeI6pMhogCZpwjqwI6eI6tGTRVEdL5dWxFS+VVoyZU8ummOrvlU0x0ySiY6YyVdaOomSgCkyVEy1cZKhZaQFVlphWrDLUZWkMhxRTyKKBm44+Y1GRUz/eNMIrM3RCRTdtSkUmKhstIj20bakxQBU3NEhoWnBaeFp4WpuapEYWnhKkVakVKLj5SNUqRY6lVKlWOqTM5IhEVPEVWFjqQR1qjnkVhFS+VVoR0vl1qjBlMx0xo6uFKjZKozKTJUDpV51qu60gKTrUDrVx1qu4pFFVlqNhVhhUTCpGQEUU8jmigZsOfnNMNDH5jSZrJnQgNNpc0neoZaFpQKSnCoZqhwFPApq1ItSaoeoqVFpiip0FIocqVMqUiCp0WrRlIVUp4SnqtSBa3ics2RbKClTbaQit0jlkysy1E61ZYVC9VYzuVHFV5BVqSq0lJlJlVxVdxVl6rvUForsKiYVM1RNSKIyOaKU9aKALzN8xpu6mM3zH60m6sTdEmaM1Hupc1LLRIDTwahBp6moZoiZTUq1ApqZDUmqLCVOlV0NTpUllhKsJVdKsIa0iYzZMoqQVGpqQGumCOGpIdTDTs00mumKOKUyJqgep2qvJVWM+cryVVkqzIaqyVDRrGRXkNV3NTSGq7ms2bJkTGomNPY1ExpGghNFNJooAnZvnP1pN1MdvnP1pu6sTYm3Uuah3U4NUlImBp4NQA1IDUs0RYU1MhqspqdDWbNkWUNWENVUNToakotIasIaqIanVq0iYVCyrVIGqsrVIGrrgebVkTZpCaZuoJrqijzpyEY1A9StUL1pYyUytJVWWrUlVJKzkjohIqyGqzmp5KrOaxZ1xZExqJjT2NQsag2QE0UwmigZK5+c/WkzTXPzn60mayNiQGnA1EDT1qWUiUGpFqJalWoZrEmWp0qBKnSsmzeJOlTIagWplNSUydTUqtVdTTw1axOWoWQ1PDVVD08PXXA8qsWg1LuqANTw1dcTzJseajenZpjVqYp6leSqktW5KqS1lI6qbKctVJKty1UkrBnfAruaiY1I9QMag6EITRTSaKQyVvvn60UN98/WkFZmyHinrTBUiioZaHqKmUVGoqdBWTZtFEiCp1FRoKlUVk2dCRItSCoxT80kDHg0bqjzSbq2iclQnD04PVbfTleuyB5VUuK9SK1VFepVauqJ5tRFkGkY0wNQTWlzC2pHJVWWrLmq0tYyZ1U0UpaqSVclqnLWLZ3wKr1C1TuKgaoudKIzRSGigZO33z9aAKVh85+tOArJs2QqipFFIoqRRWbZokPUVOgqNRUyispM3iSKKlUUxRUgrJmyHUE0maaTTRMmKTTC1IWqMtXRA46jJN1KHqDdShq64HnVEW1epleqSvUyPXRFnBOJcV6duqurU8NVXMeUcxqvJUpaoXNZSZvTRWlqnIKtyVVkrBs74IqvUDCrDioGFTc3RCRRSmimUWWX5z9acBTyvzn60oWsWzZIFFSKKQCpFFZtmqHKKmUVGoqUVkzVEgpwNMFLmpNLjiajZqC1Rs1XFGUmDNUZahmqMtXTFHJNi7qUNURNKGroiccywrVMrVTVqmVq2RyyiXFapA1VVapA1O5jyk26onNLupjGspGsERP0qtIKsvVd6xZ2QKzioGFWHFQsKk3RARRTyKKdxl5kO4/WlCn0oorBnQhwX2p4U+lFFZstDwD6U8CiipNEO5pDn0oopAxpJ9KjYmiitYmUiJiajJooroic0hvNKDRRWyOeQ4E1KpoorVHPIlXNSqTRRTMh/NNOfSiispFxGMD6VAw9qKKxZ1QIXU+lQsKKKg3RGy0UUUyj//Z';
var sw_registerations = [];
var viewerjs;


$(document).ready(function() {
    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        if (system_variable('login_from_storage') === 'true') {
            $('body').addClass('d-none');
            var current_site_url = $(location).attr('href');

            var url_divider = (current_site_url.indexOf('?') !== -1) ? '&': '?';

            var user_login_register = current_site_url+url_divider;
            user_login_register = user_login_register+'login_session_id='+user_login_session_id+'&access_code='+user_access_code;
            user_login_register = user_login_register+'&session_time_stamp='+user_session_time_stamp;
            window.location.href = user_login_register;
        }
    }
});

if (system_variable('search_on_change_of_input') === 'enable') {
    search_on_change_of_input = true;
}

if ($('meta[name="csrf-token"]').attr('content') !== undefined) {
    user_csrf_token = $('meta[name="csrf-token"]').attr('content');
}

var mobile_page_transitions = ['animate__backInUp', 'animate__zoomInUp', 'animate__rotateInUpLeft'];

var mobile_page_transition = 'animate__fadeInRightBig';




$('.main').on('click', function(e) {
    if (!$(e.target).parents('.switchuser').hasClass('switchuser')) {
        $('.main .panel > .textbox > .box > .switchuser > .uslist').hide();
    }
});

function addCssFile(url) {
    if ($('link[rel="stylesheet"][href="' + url + '"]').length === 0) {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = url;

        document.head.appendChild(link);
    }
}


$('.main').on('click', function(e) {
    if ($(window).width() < 1200) {
        if (!$(e.target).parents('.side_navigation').hasClass('side_navigation')) {
            if ($('.main .chat_page_container').hasClass('show_navigation')) {
                toggle_side_navigation();
            }
        }
    }
});

function unicodeHash(inputString) {
    const utf8Bytes = new TextEncoder().encode(inputString);
    let hash = 0;

    for (const byte of utf8Bytes) {
        hash += byte;
    }

    return hash;
}

function handleImageError(imageElement) {
    imageElement.src = blur_img_url;
}

$(document).ready(function() {
    $('body').on('contextmenu', 'img', function(e) {
        return false;
    });

    if ($('body').hasClass('right_click_disabled')) {
        document.addEventListener("contextmenu", (event) => {
            event.preventDefault();
        });
    }

    var force_ios_lockdown = false;

    if (force_ios_lockdown || isLockdown.isLockdownEnabled()) {
        var lockdown_stylesheet = $('<link>', {
            rel: 'stylesheet',
            type: 'text/css',
            href: baseurl+'assets/css/chat_page/lockdown_stylesheet.css'
        });

        $('head').append(lockdown_stylesheet);
    }
});


$("body").on('click', '.main .dropdown_button > .icon', function(evt) {
    if ($(window).width() > 767.98) {
        if ($(evt.target).parents('.dropdown_list').length == 0) {
            $(this).parent().find(".dropdown_list > ul > li").first().trigger("click");
        }
    }
});

function registerServiceWorker(update_worker) {
    var sw_location = baseurl+'service_worker.js';

    if (update_worker !== undefined) {
        var sw_location = baseurl+'service_worker.js?v='+update_worker;
    }

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register(sw_location)
        .then(function(registration) {
            if (update_worker !== undefined) {
                registration.update();
                console.log('Service worker updated successfully:', registration);
            } else {
                console.log('Service worker registered successfully:', registration);
            }
        })
        .catch(function(error) {
            console.log('Service worker registration failed:', error);
        });
    } else {
        console.log('Service workers are not supported.');
    }
}

function show_dropdown(element) {
    element.find(".dropdown_list").removeClass('reverse');
    element.find(".dropdown_list").show();

    var dropdown_box = {
        bottom: 552.6375122070312,
        height: 225,
        left: 641.4249877929688,
        right: 764.6749877929688,
        top: 327.63751220703125,
        width: 123.25,
        x: 641.4249877929688,
        y: 327.63751220703125,
    };

    if (element.find(".dropdown_list").length > 0) {
        dropdown_box = element.find(".dropdown_list").get(0).getBoundingClientRect();
    }

    var newtop = parseInt(element.find(".dropdown_list").height())-parseInt(dropdown_box.top);
    var isInViewport = (
        dropdown_box.top >= newtop &&
        dropdown_box.left >= 0 &&
        dropdown_box.bottom <= (element.parent('.boundary').innerHeight()) &&
        dropdown_box.right <= (element.parents('.boundary').innerWidth())
    );

    if (dropdown_box.top < newtop) {
        element.find(".dropdown_list").addClass('reverse');
    } else if (dropdown_box.bottom > (element.parent('.boundary').innerHeight())) {
        element.find(".dropdown_list").addClass('reverse');
    }
}


$("body").on('mouseenter', '.main .dropdown_button', function(e) {
    if ($(window).width() > 767.98) {
        //show_dropdown($(this))
    }
});

$("body").on('click', '.main .side_navigation .menu_items li', function(e) {
    if ($(window).width() < 767.98) {
        if (!$(this).hasClass('has_child')) {
            $('.main .chat_page_container').removeClass('show_navigation');
        }
    }
});

$("body").on('click', '.main .dropdown_button', function(e) {
    if (!$(e.target).hasClass('hide_onClick')) {
        $(".main .dropdown_list").hide();
        show_dropdown($(this));
    } else {
        $(".main .dropdown_list").hide();
    }
});

function update_user_online_status(status) {

    var update_user_online_status = baseurl+'entry/user_online_status/';

    if (status !== undefined && status === 'offline') {
        var update_data = {
            offline: true,
        };
    } else {
        var update_data = {
            online: true,
        };
    }

    if (navigator.sendBeacon) {
        navigator.sendBeacon (update_user_online_status, JSON.stringify (update_data));
    }
}

window.addEventListener('beforeunload', function (e) {
    update_user_online_status('offline');
});

$(window).on("load", function() {
    update_user_online_status('online');
});

document.addEventListener("visibilitychange", function() {
    if ($(window).width() < 767.98) {
        if (document.visibilityState === 'hidden') {
            update_user_online_status('offline');
        } else if (document.visibilityState === 'visible') {
            update_user_online_status('online');
        }
    }
});

$(".main").on('click', function(e) {
    if (!$(e.target).hasClass('dropdown_button')) {
        $(".main .dropdown_list").hide();
    }

    if (!$(e.target).parents().hasClass('switch_user')) {
        $('.main .chatbox > .header > .switch_user').removeClass('open');
    }

    if (!$(e.target).hasClass('site_record_item') && $(e.target).parents('.site_record_item').length == 0) {
        $(".main .aside > .site_records > .records > .list > li > div > .right > .options > span").hide();
    }

    if (!$(e.target).hasClass('side_navigation_footer') && $(e.target).parents('.side_navigation_footer').length == 0) {
        $(".main .side_navigation > .bottom.has_child").removeClass('show');
    }
});

$("body").on('mouseenter', '.main .infotipbtn', function(e) {
    $(this).find(".infotip").show();
});

$("body").on('mouseleave', '.main .infotipbtn', function(e) {
    $(".main .infotip").hide();
});

$("html").on("dragover", function(e) {
    e.preventDefault();
    e.stopPropagation();
});

$("html").on("click", function(event) {
    if ($(event.target).attr('data-bs-toggle') === undefined || $(event.target).parent().hasClass('hide_tooltip_on_click')) {
        $('.tooltip').remove();
    }
});

$("html").on("drop", function(e) {
    e.preventDefault();
    e.stopPropagation();
});


$('.refresh_page').on('click', function() {

    var embed_url = system_variable('embed_url');

    if (embed_url.length > 0) {
        window.location.replace(embed_url);
    } else {
        location.reload(true);
    }
});



$("body").on('focus', '.copy_to_clipboard', function(e) {
    var $this = $(this);
    $this.select();

    $this.keydown(function(event) {

        if (event.keyCode !== 17 && event.keyCode !== 67 && event.keyCode !== 91 && event.keyCode !== 67) {
            event.preventDefault();
        }

    });

    document.execCommand('copy');
});

jQuery(document).ready(function($) {

    if (window.history && window.history.pushState) {
        if ($(window).width() < 800) {
            $(window).on('popstate', function() {
                var hashLocation = location.hash;
                var load_blank_onexit = false;
                var hashSplit = hashLocation.split("#!/");
                var hashName = hashSplit[1];
                if (hashName !== '') {
                    var hash = window.location.hash;
                    if (hash === '') {
                        window.history.pushState('forward', null, './#');

                        if (load_blank_onexit && $('.main .aside').hasClass('visible')) {
                            window.open('about:blank', "_self");
                        } else {

                            var go_to_back_trigger = true;

                            if (!$('.main .middle > .video_preview').hasClass('d-none')) {
                                $('.main .middle > .video_preview').removeClass('fixed_draggable_layout');
                                $('.main .middle > .video_preview').addClass('d-none');
                                $('.main .middle > .video_preview > div').html('');
                                go_to_back_trigger = false;
                            }

                            if ($('.viewer-container.viewer-backdrop').length > 0) {
                                viewer.destroy();
                                go_to_back_trigger = false;
                            }

                            if (go_to_back_trigger) {
                                open_column('first', true);
                            }
                        }
                    }
                }
            });

            window.history.pushState('forward', null, './#');
        }
    }

});


$(window).on('load', function() {
    $('.preloader').fadeOut();
    $('body').removeClass('overflow-hidden');
    $('.site_sound_notification').addClass('d-none');

    var left_panel_content_on_page_load = $.trim($('.content_on_page_load > .left_panel_content_on_page_load').text());


    if ($('.main .side_navigation .force_trigger_onload').length > 0) {
        $('.main .side_navigation .force_trigger_onload').trigger('click');
    } else if (left_panel_content_on_page_load !== '') {
        left_panel_content_on_page_load = '.load_'+left_panel_content_on_page_load;
        $('.main .side_navigation '+left_panel_content_on_page_load).trigger('click');
    } else if ($('.main .side_navigation .load_groups').length > 0) {
        $('.main .side_navigation .load_groups').trigger('click');
    } else {
        $('.main .aside > .head > .icons > i.load_groups').trigger('click');
    }

    if ($(window).width() > 770.98) {
        var main_panel_content_on_page_load = $.trim($('.content_on_page_load > .main_panel_content_on_page_load').text());

        if (main_panel_content_on_page_load === 'statistics') {
            $('.main .side_navigation .load_statistics').trigger('click');
        }
    }


    if ($(window).width() > 1210) {
        if ($('.main .side_navigation').length > 0) {
            if (system_variable('show_side_navigation_on_load') === 'yes') {
                toggle_side_navigation();
            }
        }

    }


    var load_on_refresh = WebStorage('get', 'load_on_refresh');

    if (load_on_refresh !== undefined && load_on_refresh !== null) {
        load_on_refresh = JSON.parse(load_on_refresh);
    }

    if (load_on_refresh !== null && load_on_refresh.attributes !== undefined) {

        WebStorage('remove', 'load_on_refresh');

        var load_on_refresh_element = '<span ';

        $.each(load_on_refresh.attributes, function(attrkey, attrval) {
            load_on_refresh_element = load_on_refresh_element+attrkey+'="'+attrval+'" ';

        });

        load_on_refresh_element = load_on_refresh_element+'>on_refresh</span>';

        $('.load_on_refresh').html(load_on_refresh_element);
        $('.load_on_refresh > span').trigger('click');
    } else if ($('.on_site_load > span').length > 0) {

        if ($('.on_site_load > span').hasClass('load_profile_on_page_load')) {
            if ($(window).width() > 1210) {
                $('.on_site_load > span').trigger('click');
            }
        } else {
            $('.on_site_load > span').trigger('click');
        }

    }

    $('.main .aside > .storage_files_upload_status').addClass('d-none');
    $('.main').fadeIn();

    $.getScript(baseurl+"assets/js/combined_js_chat_page_after_load.js");

    $('.lazy').Lazy();

});


function is_touch_device() {
    return (
        "ontouchstart" in window ||
        navigator.MaxTouchPoints > 0 ||
        navigator.msMaxTouchPoints > 0
    );
}

function isJSON (data) {
    var IS_JSON = true;
    try
    {
        var json = $.parseJSON(data);
    }
    catch(err) {
        IS_JSON = false;
    }
    return IS_JSON;
}

function language_string(string_constant) {
    var string_value = '';

    if (string_constant !== undefined) {
        string_value = $('.language_strings > .string_'+string_constant).text();
    }

    return string_value;
}

function system_variable(variable, update_value) {

    if (update_value === undefined) {
        var result = '';

        if (variable !== undefined) {
            result = $('.system_variables > .variable_'+variable).text();
        }

        return result;
    } else {
        $('.system_variables > .variable_'+variable).text(update_value);
    }
}

function change_browser_title(title, set_timeout = 0) {
    if (title !== undefined) {
        title = $.trim(title);
        if (title.length > 0) {

            document.title = decode_specialchars(title);

            if (meta_title_timeout !== null) {
                clearTimeout(meta_title_timeout);
            }

            if (set_timeout == 0) {
                system_variable('current_title', title)
            } else {
                meta_title_timeout = setTimeout(function() {
                    meta_title_timeout = null;

                    var reset_title = system_variable('current_title');

                    if (reset_title.length < 0) {
                        reset_title = default_meta_title;
                    }

                    change_browser_title(reset_title);

                }, set_timeout);
            }
        }
    }
}

function timestamp_convertor(s) {
    var h = Math.floor(s/3600);
    var tms = "";
    s -= h*3600;
    var m = Math.floor(s/60);
    s -= m*60;
    s = Math.floor(s);
    if (h != 0) {
        tms = h+":"+(m < 10 ? '0'+m: m)+":"+(s < 10 ? '0'+s: s);
    } else {
        tms = (m < 10 ? '0'+m: m)+":"+(s < 10 ? '0'+s: s);
    }
    if (tms == 'NaN:NaN:NaN') {
        tms = "00:00";
    }
    return tms;
}

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    } else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function isLocalStorageAvailable() {
    var test = 'test';
    try {
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch(e) {
        return false;
    }
}

function WebStorage(todo, name, value) {

    if (isLocalStorageAvailable() && typeof(Storage) !== "undefined") {
        if (todo == 'get') {
            value = localStorage.getItem(name);
            if (value) {
                return value;
            } else {
                return null;
            }
        } else if (todo == 'set') {
            localStorage.setItem(name, value);
        } else if (todo == 'remove') {
            localStorage.removeItem(name);
        } else if (todo == 'clear') {
            localStorage.clear();
        }
    } else {
        console.log('No Web Storage Support');
        return null;
    }
}

function RandomString (len) {
    var rdmString = "";
    for (; rdmString.length < len; rdmString += Math.random().toString(36).substr(2));
    return  rdmString.substr(0, len);
}

function abbreviateNumber(value) {
    var newValue = value;
    if (value >= 1000) {
        var suffixes = ["", "k", "m", "b", "t"];
        var suffixNum = Math.floor((""+value).length/3);
        var shortValue = '';
        for (var precision = 2; precision >= 1; precision--) {
            shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000, suffixNum)): value).toPrecision(precision));
            var dotLessShortValue = (shortValue + '').replace(/[^a-zA-Z 0-9]+/g, '');
            if (dotLessShortValue.length <= 2) {
                break;
            }
        }
        if (shortValue % 1 != 0)  shortValue = shortValue.toFixed(1);
        newValue = shortValue+suffixes[suffixNum];
    }
    return newValue;
}

$("body").on('click', '.open_link', function(e) {

    var web_address = '';

    if ($(this).attr('link') !== undefined) {
        web_address = $(this).attr('link');
    }

    if ($(this).attr('autosync') !== undefined) {
        if ($('.main .chatbox > .info_box > .open_link').is(":visible")) {
            if ($('.main .chatbox > .info_box > .open_link').attr('link') !== undefined) {
                web_address = $('.main .chatbox > .info_box > .open_link').attr('link');
            }
        }
    }

    if (web_address.length > 0) {

        if ($(this).attr('target') !== undefined) {
            window.open(web_address, $(this).attr('target')).focus();
        } else {
            window.location = web_address;
        }
    }

});


function on_image_load(image) {
    image.parentElement.classList.add('image_loaded');
}

$("body").on('click', '.go_to_previous_page', function(e) {

    if ($(window).width() < 780) {

        if (audio_message_preview !== undefined && audio_message_preview !== null) {
            audio_message_preview.pause();
        }

        if (video_preview !== undefined && video_preview !== null) {
            video_preview.pause();
        }
    }

    open_column('first', true);
});

function open_column(column, loadPrevious) {

    var animate = true;

    if ($(window).width() <= 991 && $(window).width() >= 770.98) {
        loadPrevious = false;
    }

    if (loadPrevious !== undefined && loadPrevious) {
        animate = false;
        if ($('.page_column.previous').length > 0) {

            var previous_column = $('.page_column.previous').attr('column');

            if ($('.page_column.previous').hasClass('d-none')) {
                previous_column = 'first';
            } else if (previous_column === 'third') {
                previous_column = 'first';
            }

            if (previous_column !== $('.page_column.visible').attr('column')) {
                column = previous_column;
            }
        }
    }

    var current_column = $('.page_column.visible');

    $('.page_column').removeClass('previous');
    $('.page_column').removeClass('animate__animated '+mobile_page_transition+' animate__faster');

    if (current_column.length === 0) {
        current_column = $('.page_column[column="first"]');
        $('.page_column[column="first"]').removeClass('d-none');
        $('.page_column[column="first"]').addClass('visible');
    }

    if (column !== undefined) {

        if ($(window).width() <= 991 && $(window).width() >= 770.98) {

            if (column === 'fourth') {
                $('.page_column[column="third"]').addClass('d-none');
                $('.page_column[column="first"]').addClass('d-none');
                $('.page_column[column="fourth"]').removeClass('d-none');
            } else if (column === 'first') {
                $('.page_column[column="third"]').addClass('d-none');
                $('.page_column[column="fourth"]').addClass('d-none');
                $('.page_column[column="first"]').removeClass('d-none');
            } else if (column === 'third') {
                $('.page_column[column="fourth"]').addClass('d-none');
                $('.page_column[column="first"]').addClass('d-none');
                $('.page_column[column="third"]').removeClass('d-none');
            }
        }

        if ($('.page_column.visible').attr('column') != column && animate) {
            $('.page_column[column="'+column+'"]').addClass('animate__animated '+mobile_page_transition+' animate__faster');
        }

        if (current_column.attr('column') === 'third' || current_column.attr('column') === 'fourth') {
            current_column = $('.page_column[column="first"]');
        }

        current_column.addClass('previous');
        $('.page_column').removeClass('visible');
        $('.page_column[column="'+column+'"]').addClass('visible').removeClass('previous');
    }

}

$("body").on('click', '.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid > div', function(e) {
    var flexItems = $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid > div');
    var itemCount = flexItems.length;

    if (!$(e.target).hasClass('get_info')) {
        if (itemCount >= 2) {
            if ($(this).hasClass('full_view')) {
                $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').removeClass('full_view_container');
                $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid > div').removeClass('full_view');
            } else {
                $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid > div').removeClass('full_view');
                $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').addClass('full_view_container');
                $(this).addClass('full_view');
            }
        } else {
            if ($(this).hasClass('full_view')) {
                $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').removeClass('full_view_container');
                $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid > div').removeClass('full_view');
            }
        }
    }
});

function open_module(moduleClass, parentClass, keepitOpen) {

    if (parentClass === undefined) {
        parentClass = 'body';
    }

    if (keepitOpen === undefined) {
        keepitOpen = false;
    }
    if ($(parentClass).find(moduleClass).hasClass('hidden')) {
        $(parentClass).find('.module').addClass('hidden');
        $(parentClass).find(moduleClass).removeClass('hidden');
    } else if (!keepitOpen) {
        $(parentClass).find('.module').addClass('hidden');
    }

}

function close_module(moduleClass, parentClass) {

    if (parentClass === undefined) {
        parentClass = 'body';
    }

    $(parentClass).find('.module').addClass('hidden');

}

function loader_content($type = 'list') {
    var content = '';
    if ($type == 'list') {
        for (let i = 0; i < 14; i++) {
            content = content+'<li><div><span class="left">';
            content = content+'<span class="img"></span>';
            content = content+'</span><span class="center">';
            content = content+'<span class="title"></span>';
            content = content+'<span class="subtitle"></span>';
            content = content+'</span><span class="right"></span>';
            content = content+'</div></li>';
        }
    }
    return content;
}



$('body').on('click', '.openlink', function(e) {
    var url = $(this).attr("url");
    var pattern = /^((http|https|ftp):\/\/)/;
    if (!pattern.test(url)) {
        url = baseurl+url;
    }
    if ($(this).attr('newtab') == undefined) {
        window.location = url;
    } else {
        window.open(url, '_blank');
    }
    return false;
});

function randomColor(lum) {
    var randomColor = Math.floor(Math.random()*16777215).toString(16);
    randomColor = String(randomColor).replace(/[^0-9a-f]/gi, '');
    if (randomColor.length < 6) {
        randomColor = randomColor[0]+randomColor[0]+randomColor[1]+randomColor[1]+randomColor[2]+randomColor[2];
    }
    lum = lum || 0;
    var rgb = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(randomColor.substr(i*2, 2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
        rgb += ("00"+c).substr(c.length);
    }
    return rgb;
}


$("body").on('click', '.toggle_side_navigation', function(e) {
    toggle_side_navigation();
});

function toggle_side_navigation() {

    $('.main .chat_page_container > .side_navigation').removeClass('animate__animated animate__slideInLeft animate__faster animate__slideInRight');

    if ($('.main .chat_page_container').hasClass('show_navigation')) {
        $('.main .chat_page_container').removeClass('show_navigation');
    } else {
        if ($(window).width() < 1200) {
            if ($('body').hasClass('ltr_language')) {
                $('.main .chat_page_container > .side_navigation').addClass('animate__animated animate__slideInLeft animate__faster');
            } else {
                $('.main .chat_page_container > .side_navigation').addClass('animate__animated animate__slideInRight animate__faster');
            }
        }
        $('.main .chat_page_container').addClass('show_navigation');
    }
}


$("body").on('click', '.download_file', function(e) {

    if (!$(this).hasClass('processing') && $(this).attr('download') !== undefined) {
        $(this).addClass('processing');

        var element = $(this);

        var data = {
            process: "download",
            validate: true,
            download: $(this).attr('download')
        };

        data = $.extend(data, $(this).data());

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
                if (data.error != undefined) {
                    alert(decode_specialchars(data.error));
                } else if (data.download_link != undefined) {
                    window.location.href = data.download_link;
                }
            } else {
                console.log('ERROR : ' + data);
            }

            element.removeClass('processing');

        }) .fail(function(qXHR, textStatus, errorThrown) {
            element.removeClass('processing');
            console.log('ERROR : ' + errorThrown);
        });
    }
});


$("body").on('click', '.preview_image', function(e) {

    $('#preview_image').removeAttr('id');

    var index = $(this).parent().parent().index();
    var prev_btn = next_btn = navbar = 0;

    if ($(this).parents('.files').length > 0) {
        $(this).parent().parent().parent().attr('id', 'preview_image');
    } else {
        $(this).attr('id', 'preview_image');
    }

    if ($(this).parent().parent().parent().find('li').length > 1) {
        navbar = 1;
    }

    var image_data = {
        title: 0,
        navbar: navbar,
        toolbar: {
            zoomIn: {
                show: 1,
                size: 'large',
            },
            zoomOut: {
                show: 1,
                size: 'large',
            },
            oneToOne: 0,
            play: 0,
            prev: prev_btn,
            next: next_btn,
            rotateLeft: {
                show: 1,
                size: 'large',
            },
            reset: {
                show: 1,
                size: 'large',
            },
            rotateRight: {
                show: 1,
                size: 'large',
            },
            flipHorizontal: {
                show: 1,
                size: 'large',
            },
            flipVertical: {
                show: 1,
                size: 'large',
            },
        },
        hidden: function () {
            viewer.destroy();
        },
        url(image) {
            return image.getAttribute("original");
        },
    };

    if ($(this).attr('load_image') === undefined) {
        var viewerjs = viewer = new Viewer(document.getElementById('preview_image'), image_data);
    } else {

        image_data['url'] = 'src';

        var load_image = new Image();
        load_image.src = $(this).attr('load_image');
        var viewerjs = viewer = new Viewer(load_image, image_data);
    }


    viewer.view(index)
    viewer.show();
});


$("body").on('click', '.ask_confirmation', function(e) {

    var column = 'first';

    if ($(this).attr('column') === undefined || $(this).attr('column') === 'first') {
        $('.main .aside > .site_records > .records').addClass('blur');
        $('.main .aside > .site_records > .records > .list > li').removeClass('selected');
        $('.main .aside > .site_records > .tools').addClass('d-none');
    } else {
        column = $(this).attr('column');
    }

    var confirm_box = $('.main .page_column[column="'+column+'"] .confirm_box');

    var submit_button = '<span class="api_request">'+$(this).attr('submit_button')+'</span>';

    confirm_box.find('.content > .btn.submit').html(submit_button);

    confirm_box.find('.content > .btn.cancel > span').replace_text($(this).attr('cancel_button'));

    confirm_box.find('.content > .text').replace_text($(this).attr('confirmation'));

    $(this).parents('li').addClass('selected');

    $.each($(this).data(), function (name, value) {
        name = 'data-'+name;
        confirm_box.find('.content > .btn.submit > span').attr('column', column);
        confirm_box.find('.content > .btn.submit > span').attr(name, value);
    });

    if ($(this).attr('multi_select') !== undefined) {
        confirm_box.find('.content > .btn.submit > span').attr('multi_select', $(this).attr('multi_select'));
    }

    if (column === 'second') {
        confirm_box.find('.content > .btn.submit > span').attr('hide_element', '.middle .confirm_box');
    }

    confirm_box.find('.error').hide();
    confirm_box.removeClass('d-none');
});


$("body").on('click', '.main .side_navigation .menu_items > li.has_child,.main .side_navigation > .bottom.has_child', function(event) {
    if (!$(event.target).parent().parent().hasClass('child_menu')) {
        if ($(this).hasClass("show")) {
            $(this).removeClass("show")
        } else {
            $(this).addClass("show")
        }
    }
});

$("body").on('click', '.main .confirm_box > .content > .btn.cancel', function(e) {

    var column = 'first';

    if ($(this).attr('column') === undefined || $(this).attr('column') === 'first') {
        $('.main .aside > .site_records > .records').removeClass('blur');
        $('.main .aside > .site_records > .records > .list > li').removeClass('selected');
        $('.main .aside > .site_records > .tools').removeClass('d-none');
        $('.main .aside > .site_records > .records > .loader').hide();
    } else {
        column = $(this).attr('column');
    }

    var confirm_box = $('.main .page_column[column="'+column+'"] .confirm_box');

    confirm_box.find('.error').hide();
    confirm_box.addClass('d-none');

});



function typing_indicator(todo = 'log') {

    if (todo === undefined || todo === 'log') {
        if (!$('.main .chatbox').hasClass('logged_user_typing_status')) {

            $('.main .chatbox').addClass('logged_user_typing_status');

            var post_data = {
                update: 'typing_status',
            };

            if ($('.main .chatbox').attr('group_id') !== undefined) {
                post_data['group_id'] = $('.main .chatbox').attr('group_id');
            } else if ($('.main .chatbox').attr('user_id') !== undefined) {
                post_data['user_id'] = $('.main .chatbox').attr('user_id');
            }

            if ($('.main .chatbox > .header > .switch_user > .user_id > input').length > 0) {
                var send_as_user_id = $('.main .chatbox > .header > .switch_user > .user_id > input').val();

                if (send_as_user_id.length > 0 && send_as_user_id !== '0') {
                    post_data['send_as_user_id'] = send_as_user_id;
                }
            }

            if (user_csrf_token !== null) {
                post_data["csrf_token"] = user_csrf_token;
            }

            if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
                post_data["login_session_id"] = user_login_session_id;
                post_data["access_code"] = user_access_code;
                post_data["session_time_stamp"] = user_session_time_stamp;
            }

            user_typing_log_request = $.ajax({
                type: 'POST',
                url: api_request_url,
                data: post_data,
                async: true,
                beforeSend: function() {
                    if (user_typing_log_request !== null) {
                        user_typing_log_request.abort();
                        user_typing_log_request = null;
                    }
                },
                success: function(data) {}
            }).done(function(data) {
                $('.main .chatbox').addClass('logged_user_typing_status');
            }).fail(function(qXHR, textStatus, errorThrown) {
                $('.main .chatbox').removeClass('logged_user_typing_status');
            });

            if ($('.main .chatbox').hasClass('logged_user_typing_status')) {
                if (user_typing_log_timeout !== null) {
                    clearTimeout(user_typing_log_timeout);
                }

                user_typing_log_timeout = setTimeout(function() {
                    $('.main .chatbox').removeClass('logged_user_typing_status');
                    user_typing_log_timeout = null;
                }, 10000);

            }
        }
    } else if (todo === 'reset') {

        if (user_typing_log_timeout !== null) {
            clearTimeout(user_typing_log_timeout);
            user_typing_log_timeout = null;
        }

        whos_typing(null)

        $('.main .chatbox').removeClass('logged_user_typing_status');
    }
}


function whos_typing(user_data) {

    if (user_data !== undefined) {

        if (users_typing_timeout !== null) {
            clearTimeout(users_typing_timeout);
            users_typing_timeout = null;
        }

        if (user_data === null || user_data === '') {
            $('.main .chatbox > .header > .heading > .whos_typing').attr('last_logged_user_id', 0);
            $('.main .chatbox > .header > .heading > .whos_typing > ul').html('');
        } else {
            var users_typing = '';

            $.each(user_data, function(key, user) {
                users_typing += '<li>'+user+' '+language_string('is_typing')+'</li>';
            });

            $('.main .chatbox > .header > .heading > .whos_typing > ul').html(users_typing);
        }
    }

    if ($('.main .chatbox > .header > .heading > .whos_typing > ul').length > 0) {
        if ($('.main .chatbox > .header > .heading > .whos_typing > ul > li.active').length === 0) {
            $('.main .chatbox > .header > .heading > .whos_typing > ul > li:first-child').addClass('active');
        } else {

            var $active = $('.main .chatbox > .header > .heading > .whos_typing > ul > li.active');

            if ($active.next().length > 0) {
                var $next = $active.next();
            } else {
                var $next = $('.main .chatbox > .header > .heading > .whos_typing > ul > li:first-child');
            }

            $next.addClass('active');

            if ($('.main .chatbox > .header > .heading > .whos_typing > ul > li').length > 1) {
                $active.removeClass('active');
            }

        }

        if (users_typing_timeout !== null) {
            clearTimeout(users_typing_timeout);
        }

        if ($('.main .chatbox > .header > .heading > .whos_typing > ul > li').length > 1) {
            users_typing_timeout = setTimeout(function() {
                whos_typing();
                users_typing_timeout = null;
            }, 2000);
        }
    }
}