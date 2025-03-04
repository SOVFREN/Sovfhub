let pricing_currentIndex = 0;
var load_membership_info_request = null;
var selected_membership_package_id = 0;
var site_user_membership_order_request = null;

$('body').on('click', '.load_membership_info', function(e) {
    open_column('second');

    var package_id = 0;

    if ($(this).attr('package_id') !== undefined) {
        package_id = $(this).attr('package_id');
    }

    load_membership_info(package_id);
});

$('.main').on('click', '.membership_info > .contents > .payment_page > div > .package-info > .back-button', function(e) {
    $('.main .middle > .content > .membership_info > .contents > .payment_page').addClass('d-none');
});

$('.main').on('click', '.membership_info .pricing-table-container > .pricing-table > .pricing-body > span.buy_now', function(e) {
    var package_name = $(this).parent().parent().find('.pricing-head > .package_name').text();
    var pricing = $(this).parent().parent().find('.pricing-head > .pricing').text();
    var duration = $(this).parent().parent().find('.pricing-head > .duration').text();
    var selected_info = $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .package-info > .details');
    selected_membership_package_id = $(this).parent().parent().attr('membership_package_id');

    $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .payment-gateways').addClass('d-none');
    $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .place_order').addClass('d-none');

    selected_info.find('.package_name').text(package_name);
    selected_info.find('.pricing > span').text(pricing);
    selected_info.find('.duration').text(duration);

    var package_pricing = pricing.match(/\d+/);

    if (package_pricing !== null) {
        package_pricing = parseInt(package_pricing[0], 10);
    }

    $('.main .middle > .content > .membership_info > .contents > .payment_page').removeClass('d-none');

    if (package_pricing === 0 || package_pricing === null) {
        $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .place_order').removeClass('d-none');
    } else {
        $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .payment-gateways').removeClass('d-none');
    }

    $('.main .middle > .content > .membership_info > .contents').animate({
        scrollTop: $('.main .middle > .content > .membership_info > .contents > .payment_page').offset().top
    }, 1000);
});

function load_membership_info(membership_package_id) {

    $('.main .middle > .content > div').addClass('d-none');
    $('.main .middle > .foot').addClass('d-none');

    $('.main .middle > .group_headers > .header_content').html('');
    $('.main .middle > .group_headers').removeClass('header_content_loaded');
    $('.main .middle > .group_headers').addClass('d-none');

    $('.main .middle > .content > .membership_info > .contents > .preloader-container').removeClass('d-none');
    $('.main .middle > .content > .membership_info').removeClass('d-none');
    $('.main .middle > .content > .membership_info > .contents > .membership-info > .membership-card').html('');
    $('.main .middle > .content > .membership_info > .contents > .membership-info').addClass('d-none');

    $('.main .middle > .content > .membership_info > .contents > .available_packages .pricing-table-container').html('');
    $('.main .middle > .content > .membership_info > .contents > .available_packages').addClass('d-none');

    $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .payment-gateways > ul').html('');
    $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .payment-gateways').addClass('d-none');
    $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .place_order').addClass('d-none');

    $('.main .middle > .content > .membership_info > .contents > .payment_page').addClass('d-none');

    document.title = default_meta_title;
    var membership_package_url = baseurl+'membership_packages/';

    membership_package_id = parseInt(membership_package_id);

    if (membership_package_id !== 0 && membership_package_id !== null) {
        membership_package_url = membership_package_url+membership_package_id+'/';
    }

    history.pushState({}, null, membership_package_url);

    var data = {
        load: 'membership_info',
    };

    if (user_csrf_token !== null) {
        data["csrf_token"] = user_csrf_token;
    }

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        data["login_session_id"] = user_login_session_id;
        data["access_code"] = user_access_code;
        data["session_time_stamp"] = user_session_time_stamp;
    }

    load_membership_info_request = $.ajax({
        type: 'POST',
        url: api_request_url,
        data: data,
        async: true,
        beforeSend: function() {
            if (load_membership_info_request != null) {
                load_membership_info_request.abort();
                load_membership_info_request = null;
            }
        },
        success: function(data) {}
    }).done(function(data) {
        if (isJSON(data)) {
            data = $.parseJSON(data);

            if (data.info_items !== undefined) {

                var info_items = '';

                $.each(data.info_items, function(index, info_item) {
                    info_items += '<div class="info-item">';
                    info_items += '<p>'+info_item.title+'</p>';
                    info_items += '<p>'+info_item.value+'</p>';
                    info_items += '</div>';
                });

                $('.main .middle > .content > .membership_info > .contents > .membership-info > .membership-card').html(info_items);

                $('.main .middle > .content > .membership_info > .contents > .membership-info').removeClass('d-none');
            }

            if (data.packages !== undefined) {

                var packages = '';

                $.each(data.packages, function(index, pricing) {
                    packages += '<div class="pricing-table" membership_package_id="'+pricing.membership_package_id+'">';
                    packages += '<div class="pricing-head">';
                    packages += '<h3 class="package_name">'+pricing.title+'</h3>';
                    packages += '<span class="pricing">'+pricing.pricing+'</span>';
                    packages += '<span class="duration">'+pricing.duration+'</span></span>';
                    packages += '</div>';
                    packages += '<div class="pricing-body">';

                    if (pricing.benefits !== undefined) {
                        packages += '<ul>';

                        $.each(pricing.benefits, function(benefit_index, package_benefit) {
                            packages += '<li>';
                            packages += '<svg class="tick-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">';
                            packages += '<path fill="currentColor" d="M9.293 16.293L5.293 12.293a1 1 0 0 1 1.414-1.414L10 14.586l7.293-7.293a1 1 0 1 1 1.414 1.414l-8 8a1 1 0 0 1-1.414 0z" />';
                            packages += '</svg>';
                            packages += package_benefit;
                            packages += '</li>';
                        });

                        packages += '</ul>';
                    }

                    if (pricing.purchase_button !== undefined) {
                        packages += '<span class="buy_now">'+pricing.purchase_button+'</span>';
                    }

                    packages += '</div>';
                    packages += '</div>';
                });

                $('.main .middle > .content > .membership_info > .contents > .available_packages .pricing-table-container').html(packages);
                $('.main .middle > .content > .membership_info > .contents > .available_packages').removeClass('d-none');

            }

            if (data.payment_gateways !== undefined) {

                var payment_gateways = '';
                var color_scheme = 'light';

                if ($('body').hasClass('dark_mode')) {
                    color_scheme = 'dark';
                }

                $.each(data.payment_gateways, function(index, gateway) {
                    payment_gateways += '<li><span class="create_order" payment_gateway_id="'+index+'"><img src="'+baseurl+'assets/files/payment_gateways/'+color_scheme+'/'+gateway+'.png"/></span></li>';
                });

                $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .payment-gateways > ul').html(payment_gateways);
                $('.main .middle > .content > .membership_info > .contents > .payment_page > div > .payment-gateways').removeClass('d-none');
            }



            $('.main .middle > .content > .membership_info > .contents > .preloader-container').addClass('d-none');
            pricing_currentIndex = 0;
            membership_pricing_slider(pricing_currentIndex);

            if (membership_package_id !== 0 && membership_package_id !== null) {
                $('.pricing-table[membership_package_id="'+membership_package_id+'"]').find('.buy_now').trigger('click');
            }

        } else {
            console.log('ERROR : ' + data);
        }
    }) .fail(function(qXHR, textStatus, errorThrown) {
        if (qXHR.statusText !== 'abort' && qXHR.statusText !== 'canceled') {
            console.log('ERROR : ' + data);
        }
    });




}

$('.main').on('click', '.membership_info > .contents > .payment_page > div > .payment-gateways > ul > li > span.create_order, .membership_place_order', function(e) {
    var data = {
        add: 'site_user_membership_order',
        membership_package_id: selected_membership_package_id,
        payment_gateway_id: $(this).attr('payment_gateway_id')
    };

    if (user_csrf_token !== null) {
        data["csrf_token"] = user_csrf_token;
    }

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        data["login_session_id"] = user_login_session_id;
        data["access_code"] = user_access_code;
        data["session_time_stamp"] = user_session_time_stamp;
    }

    site_user_membership_order_request = $.ajax({
        type: 'POST',
        url: api_request_url,
        data: data,
        async: true,
        beforeSend: function() {
            if (site_user_membership_order_request != null) {
                site_user_membership_order_request.abort();
                site_user_membership_order_request = null;
            }
        },
        success: function(data) {}
    }).done(function(data) {
        if (isJSON(data)) {
            data = $.parseJSON(data);
            if (data.redirect !== undefined) {
                window.location.href = data.redirect;
            } else if (data.alert !== undefined) {
                alert(data.alert);
            }
        } else {
            console.log('ERROR : ' + data);
        }
    }) .fail(function(qXHR, textStatus, errorThrown) {
        if (qXHR.statusText !== 'abort' && qXHR.statusText !== 'canceled') {
            console.log('ERROR : ' + data);
        }
    });
});

function membership_pricing_slider(index) {
    var pricingTables = $('.membership_info > .contents > .available_packages .pricing-table-container > .pricing-table');
    var pricingTableContainer = $('.membership_info > .contents > .available_packages .pricing-table-container');

    var translateX = -index * (pricingTables.outerWidth() + 10);
    pricingTableContainer.css('transform', `translateX(${translateX}px)`);
}

$('.membership_info > .contents > .available_packages > div > .header > div > .right > .previous_pricing').click(function() {
    if (pricing_currentIndex > 0) {
        pricing_currentIndex--;
        membership_pricing_slider(pricing_currentIndex);
    }
});

$('.membership_info > .contents > .available_packages > div > .header > div > .right > .next_pricing').click(function() {

    var pricingTables = $('.membership_info > .contents > .available_packages .pricing-table-container > .pricing-table');

    if (pricing_currentIndex < pricingTables.length - 1) {
        pricing_currentIndex++;
        membership_pricing_slider(pricing_currentIndex);
    }
});