<?php

if ($force_request || Registry::load('current_user')->logged_in) {
    $session_id = $session_time_stamp = $access_code = null;
    $remove_all_user_sessions = false;
    $user_id = Registry::load('current_user')->id;

    if (isset($private_data['remove_all_user_sessions']) && $private_data['remove_all_user_sessions']) {
        $remove_all_user_sessions = true;
    }

    if (isset($_COOKIE["login_session_id"]) && isset($_COOKIE["session_time_stamp"]) && isset($_COOKIE["access_code"])) {
        $session_id = $_COOKIE["login_session_id"];
        $session_time_stamp = $_COOKIE["session_time_stamp"];
        $access_code = $_COOKIE["access_code"];
    } else if (isset(Registry::load('config')->samesite_cookies_current) && strtolower(Registry::load('config')->samesite_cookies_current) === 'none') {
        if (isset($_REQUEST["login_session_id"]) && isset($_REQUEST["session_time_stamp"]) && isset($_REQUEST["access_code"])) {
            $session_id = $_REQUEST["login_session_id"];
            $session_time_stamp = $_REQUEST["session_time_stamp"];
            $access_code = $_REQUEST["access_code"];
        }
    }

    if ($force_request) {
        if (isset($data['user'])) {
            $columns = $join = $where = null;

            $columns = ['site_users.user_id'];
            $where["OR"] = ["site_users.username" => $data['user'], "site_users.email_address" => $data['user']];
            $where["LIMIT"] = 1;

            $site_user = DB::connect()->select('site_users', $columns, $where);

            if (isset($site_user[0])) {
                $user_id = $site_user[0]['user_id'];
                $remove_all_user_sessions = true;
            } else {
                $user_id = 0;
                $result = array();
                $result['success'] = false;
                $result['error_message'] = Registry::load('strings')->account_not_found;
                $result['error_key'] = 'account_not_found';
                $result['error_variables'] = [];
                return;
            }
        }
    }


    $update_status = [
        'online_status' => 0,
        "last_seen_on" => Registry::load('current_user')->time_stamp,
        "updated_on" => Registry::load('current_user')->time_stamp,
    ];
    DB::connect()->update('site_users', $update_status, ['user_id' => $user_id]);

    $update = ['status' => 2];

    if ($remove_all_user_sessions) {
        $where = [
            'login_sessions.user_id' => $user_id,
        ];
    } else {
        $where = [
            'login_sessions.login_session_id' => $session_id,
            'login_sessions.time_stamp' => $session_time_stamp,
            'login_sessions.access_code' => $access_code,
        ];

        $cachefile = 'assets/cache/login_sessions/session_'.$session_id.'.cache';
        if (file_exists($cachefile)) {
            unlink($cachefile);
        }
    }

    DB::connect()->update('login_sessions', $update, $where);

    $cookie_time = time() - 3600;
    add_cookie('session_id', '', $cookie_time);
    add_cookie('access_code', '', $cookie_time);
    add_cookie('session_time_stamp', '', $cookie_time);
}

$result = array();
$result['success'] = true;
$result['todo'] = 'redirect';

if (isset(Registry::load('config')->samesite_cookies_current) && strtolower(Registry::load('config')->samesite_cookies_current) === 'none') {
    $result['remove_login_session'] = true;
}

if (isset(Registry::load('settings')->custom_url_on_logout) && !empty(Registry::load('settings')->custom_url_on_logout)) {
    $result['redirect'] = Registry::load('settings')->custom_url_on_logout;
} elseif (isset($data['redirect'])) {
    $result['redirect'] = htmlspecialchars($data['redirect']);
} else {
    $result['redirect'] = Registry::load('config')->site_url.'entry';
}