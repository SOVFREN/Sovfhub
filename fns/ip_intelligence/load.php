<?php

function ip_intelligence($ip_address = null, $cookie_check = true) {
    $result = array();
    $result['success'] = true;

    if (empty($ip_address)) {
        $ip_address = Registry::load('current_user')->ip_address;
    }

    if ($ip_address !== '127.0.0.1') {

        if (!empty(Registry::load('settings')->ip_intelligence) && Registry::load('settings')->ip_intelligence !== 'disable') {
            if ($cookie_check && isset($_COOKIE["ip_intel_ban"]) && $_COOKIE["ip_intel_ban"] === $ip_address) {
                $result['success'] = false;
            } else {
                $ip_intel_service = Registry::load('settings')->ip_intelligence;

                if (isset($ip_intel_service) && !empty($ip_intel_service)) {
                    $load_fn_file = 'fns/ip_intelligence/'.$ip_intel_service.'.php';
                    if (file_exists($load_fn_file)) {
                        include($load_fn_file);
                    }
                }
            }
        }
    }

    if ($result['success']) {
        $cookie_time = time() - 3600;
        add_cookie('ip_intel_ban', '', $cookie_time);
    } else {
        $cookie_time = time() + (86400);
        add_cookie('ip_intel_ban', $ip_address, $cookie_time);
    }
    return $result;
}