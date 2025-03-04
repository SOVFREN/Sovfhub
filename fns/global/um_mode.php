<?php

if (isset(Registry::load('settings')->under_maintenance_mode) && Registry::load('settings')->under_maintenance_mode === 'enable') {

    $uc_mode = true;
    $um_access = null;

    if (isset($_GET['um_access']) && !empty($_GET['um_access'])) {
        $um_access = trim($_GET['um_access']);
        $cookie_time = time() + (86400 * 60);
        add_cookie('um_access', $um_access, $cookie_time);
    } else if (isset($_COOKIE['um_access']) && !empty($_COOKIE['um_access'])) {
        $um_access = trim($_COOKIE['um_access']);
    }

    if (Registry::load('settings')->maintenance_mode_access_code === $um_access) {
        $uc_mode = false;
    }

    if ($uc_mode) {

        if (isset($api_request_page) && $api_request_page) {
            $data = ['reload_page' => true];
            echo json_encode($data);
            exit;
        }

        include 'layouts/under_maintenance/layout.php';
        exit;
    }
}
?>