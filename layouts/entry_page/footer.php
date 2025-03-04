<?php
include 'layouts/entry_page/custom_page.php';
include 'layouts/entry_page/cookie_consent.php';

if (Registry::load('settings')->adblock_detector === 'enable') {
    include 'layouts/chat_page/ad_block_detector.php';
}

if ($slug === 'guest_login' || $slug === 'signup' || $slug === 'login' || $slug === 'forgot_password') {
    $first_load = $slug;
} else {
    if (isset(Registry::load('settings')->on_load_guest_login_window) && Registry::load('settings')->on_load_guest_login_window === 'enable') {
        $first_load = 'guest_login';
    }
}
?>

<?php if (isset($first_load)) {
    ?>
    <span class="first_load d-none"><?php echo $first_load; ?></span>
    <?php
} ?>
<span class="alert_message d-none" type="<?php echo $alert_type; ?>">
    <?php if (!empty($alert_message)) {
        echo $alert_message;
    } ?>
</span>
</body>
<script src="<?php echo Registry::load('config')->site_url.'assets/js/combined_js_entry_page.js'.$cache_timestamp; ?>"></script>
<?php

if (Registry::load('settings')->adblock_detector === 'enable') {
?>
<script src="<?php echo Registry::load('config')->site_url.'assets/js/common/ad_block_detector.js'.$cache_timestamp; ?>"></script>
<?php
}

include 'assets/headers_footers/entry_page/footer.php';
include 'layouts/entry_page/google_analytics.php';

if (isset(DB::connect()->pdo)) {
    DB::connect()->pdo = null;
}

DB::closeConnection();
?>
</html>