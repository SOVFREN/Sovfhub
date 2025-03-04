<?php
$service_workers = array();


if (Registry::load('current_user')->logged_in) {
    if (Registry::load('settings')->push_notifications === 'onesignal') {
        $service_workers['onesignal_sw'] = Registry::load('config')->site_url."assets/service_worker/OneSignalSDKWorker.js";
    } else if (Registry::load('settings')->push_notifications === 'webpushr') {
        $service_workers['webpushr_sw'] = Registry::load('config')->site_url."assets/service_worker/webpushr-sw.js";
    } else if (Registry::load('settings')->push_notifications === 'firebase') {
        $service_workers['firebase_sw'] = Registry::load('config')->site_url."assets/service_worker/firebase-messaging-sw.js";
    }

}


if (Registry::load('settings')->progressive_web_application === 'enable' || Registry::load('settings')->push_notifications !== 'disable') {
    $service_workers['pwa_sw'] = Registry::load('config')->site_url."pwa-sw.js";
}

if (!empty($service_workers)) {
    ?>
    <script type="module">

        <?php foreach ($service_workers as $sw_index => $service_worker) {
            ?>

            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register("<?php echo $service_worker; ?>")
                .then(function(registration) {

                    <?php if ($sw_index === 'firebase_sw') {
                        ?>
                        window.firebase_sw_reg = registration;
                        <?php
                    } ?>

                    console.log('Service worker registered successfully:', registration);

                })
                .catch(function(error) {
                    console.log('Service worker registration failed:', error);
                });
            } else {
                console.log('Service workers are not supported.');
            }

            <?php
        } ?>
    </script>

    <?php
}