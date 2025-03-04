$(window).on("load", function() {
    setTimeout(function () {
        let fakeAd = document.createElement("div");
        fakeAd.className =
        "textads banner-ads banner_ads ad-unit ad-zone ad-space adsbox adsdetectadvert"

        fakeAd.style.height = "1px"

        document.body.appendChild(fakeAd)

        let x_width = fakeAd.offsetHeight;

        if (x_width) {
            $('.adsdetectadvert').hide();
        } else {

            if ($('.cookie_consent_modal').length > 0) {
                $('.cookie_consent_modal').modal('hide');
            }

            $('.main_window').addClass('ad_block_detected');
            $('.ad_block_detector_popup_wrap').show();
            $('#ad_block_detector_popup').modal('show');
        }
    }, 1000);
});