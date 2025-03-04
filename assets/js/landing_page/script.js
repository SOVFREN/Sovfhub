var slideshow_interval = null;
let pricing_currentIndex = 0;
var slideshow_timeout = 4000;
var baseurl = site_url = $.trim($('base').attr('href'));
var api_request_url = site_url+'api_request/';
var user_csrf_token = null;
var user_login_session_id = WebStorage('get', 'login_session_id');
var user_access_code = WebStorage('get', 'access_code');
var user_session_time_stamp = WebStorage('get', 'session_time_stamp');
var load_groups_request = null;

if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
    $('body').addClass('d-none');
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

$('.load_groups').on('click', function() {
    if (!$(this).hasClass('processing')) {

        $(this).addClass('processing');

        var this_element = $(this);

        var group_category_id = null;
        if ($(this).attr('category_id') !== undefined) {
            group_category_id = $(this).attr('category_id');
        }


        var data = {
            'landing_page_groups': true,
            'group_category_id': group_category_id
        };

        load_groups_request = $.ajax({
            type: 'POST',
            url: api_request_url,
            data: data,
            async: true,
            beforeSend: function() {
                if (load_groups_request != null) {
                    load_groups_request.abort();
                    load_groups_request = null;
                }
            },
            success: function(data) {}
        }).done(function(data) {
            $('.landing_page .landing_page_groups').html(data);
            this_element.removeClass('processing');
            $('.landing_page .groups_categories_list > div').removeClass('active');
            this_element.addClass('active');
        }).fail(function(qXHR, textStatus, errorThrown) {
            this_element.removeClass('processing');
            if (qXHR.statusText !== 'abort' && qXHR.statusText !== 'canceled') {
                console.log('ERROR : ' + errorThrown);
            }
        });
    }
});


$('.refresh_page').on('click', function() {
    location.reload(true);
});

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

function slideshow() {
    var active_slide = $('.hero_section .slideshow > ul > li.active').not(".last-active");

    if (active_slide.length === 0) {
        $('.hero_section .slideshow > ul > li.active').removeClass('last-active active');
        active_slide = $('.hero_section .slideshow > ul > li:last-child');
        active_slide.addClass('active');
    }

    var next_slide = active_slide.next();

    if (next_slide.length === 0) {
        next_slide = $('.hero_section .slideshow > ul > li:first-child');
    }

    active_slide.addClass('last-active');
    active_slide.removeClass('active last-active');

    next_slide.css({
        opacity: 0.0
    })
    .addClass('active')
    .animate({
        opacity: 1.0,
    }, 1000, function() {});

    if (slideshow_interval != null) {
        clearTimeout(slideshow_interval);
    }
    slideshow_interval = setTimeout("slideshow()", slideshow_timeout);
}


$(window).on('load', function() {
    if ($('.hero_section .slideshow').length > 0) {
        if ($('.hero_section .slideshow > ul > li').length > 1) {
            slideshow();
        } else {
            $('.hero_section .slideshow > ul > li:first-child').addClass('active');
        }
    }
});

$(document).ready(function() {

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        var user_login_register = site_url+'?login_session_id='+user_login_session_id+'&access_code='+user_access_code;
        user_login_register = user_login_register+'&session_time_stamp='+user_session_time_stamp;
        window.location.href = user_login_register;
    }

    if ($('body').hasClass('right_click_disabled')) {
        document.addEventListener("contextmenu", (event) => {
            event.preventDefault();
        });
    }

    $('body').on('contextmenu', 'img', function(e) {
        return false;
    });

    if (isLockdown.isLockdownEnabled()) {
        var lockdown_stylesheet = $('<link>', {
            rel: 'stylesheet',
            type: 'text/css',
            href: baseurl+'assets/css/landing_page/lockdown_stylesheet.css'
        });

        $('head').append(lockdown_stylesheet);
    }
});


$(".landing_page").on('click', function(e) {
    if (!$(e.target).hasClass('dropdown_button') && $(e.target).parents('.dropdown_button').length == 0) {
        $(".dropdown_list").addClass('d-none');
    }
});


$("body").on('mouseenter', '.dropdown_button', function(e) {
    if ($(window).width() > 767.98) {
        $(this).find(".dropdown_list").removeClass('d-none');
    }
});

$("body").on('click', '.dropdown_button', function(e) {
    if ($(window).width() < 767.98) {
        $(this).find(".dropdown_list").removeClass('d-none');
    }
});

function membership_pricing_slider(index) {
    var pricingTables = $('.landing_page .available_membership_packages .available_packages .pricing-table-container > .pricing-table');
    var pricingTableContainer = $('.landing_page .available_membership_packages .available_packages .pricing-table-container');

    var translateX = -index * (pricingTables.outerWidth() + 10);
    pricingTableContainer.css('transform', `translateX(${translateX}px)`);
}

$('.landing_page .available_membership_packages .available_packages>div>.header>div>.right>.previous_pricing').click(function() {
    if (pricing_currentIndex > 0) {
        pricing_currentIndex--;
        membership_pricing_slider(pricing_currentIndex);
    }
});

$('.landing_page .available_membership_packages .available_packages>div>.header>div>.right> .next_pricing').click(function() {

    var pricingTables = $('.landing_page .available_membership_packages .available_packages .pricing-table-container > .pricing-table');

    if (pricing_currentIndex < pricingTables.length - 1) {
        pricing_currentIndex++;
        membership_pricing_slider(pricing_currentIndex);
    }
});


$("body").on('mouseleave', '.dropdown_button', function(e) {
    if ($(window).width() > 767.98) {
        $(".dropdown_list").addClass('d-none');
    }
});


$("body").on('click', '.frequently_asked_questions .questions > .item', function(e) {
    if (!$(this).hasClass('open')) {
        $('.frequently_asked_questions .questions > .item').removeClass('open');
        $(this).addClass('open');
    } else {
        $('.frequently_asked_questions .questions > .item').removeClass('open');
    }
});