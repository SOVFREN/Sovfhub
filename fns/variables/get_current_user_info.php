<?php
$current_user_info = new stdClass;
$current_user_info->id = 0;
$current_user_info->site_role = 1;
$current_user_info->site_role_attribute = null;
$current_user_info->logged_in = false;
$current_user_info->email_address = false;
$current_user_info->color_scheme = 0;
$current_user_info->country_code = 0;
$current_user_info->settings_exists = false;
$current_user_info->csrf_token = null;
$current_user_info->allowed_file_types = '';
$current_user_info->role_hierarchy = 100;
$current_user_banned = false;
$current_time_stamp = get_date();
$geoip_service = true;
$current_user_info->login_from_localstorage = false;
$current_user_info->remove_storage_login_access = false;

if (!empty($site_role_attributes_data) && isset($site_role_attributes_data->unverified_users)) {
    if (!empty($site_role_attributes_data->unverified_users)) {
        $current_user_info->site_role = (int)$site_role_attributes_data->unverified_users;
    }
}

$firewall = new Firewall();
$current_user_ip_address = $firewall->getUserIP();
$current_user_agent = $firewall->getUserAgent();
$validate_login_sql = true;
$login_session_cache = false;
$login_session_cache_store = false;
$login_session_cache_delete = false;

$ip_blacklist = array();
include('assets/cache/ip_blacklist.cache');
$firewall->blockIP($ip_blacklist);

try {
    $firewall->run();
} catch(Exception $e) {

    if (!isset($_REQUEST["api_secret_key"]) || empty($_REQUEST["api_secret_key"])) {
        $current_user_banned = true;
        $_COOKIE["login_session_id"] = $_COOKIE["session_time_stamp"] = $_COOKIE["access_code"] = null;

        if (Registry::load('config')->current_page !== 'banned') {
            header("Location: ".Registry::load('config')->site_url."banned");
            exit;
        }
    }
}

$login_session_id = $session_time_stamp = $access_code = null;

if (isset($_COOKIE["login_session_id"]) && isset($_COOKIE["session_time_stamp"]) && isset($_COOKIE["access_code"])) {
    $login_session_id = $_COOKIE["login_session_id"];
    $session_time_stamp = $_COOKIE["session_time_stamp"];
    $access_code = $_COOKIE["access_code"];
} else if (isset(Registry::load('config')->samesite_cookies_current) && strtolower(Registry::load('config')->samesite_cookies_current) === 'none') {
    if (isset($_REQUEST["login_session_id"]) && isset($_REQUEST["session_time_stamp"]) && isset($_REQUEST["access_code"])) {
        $login_session_id = $_REQUEST["login_session_id"];
        $session_time_stamp = $_REQUEST["session_time_stamp"];
        $access_code = $_REQUEST["access_code"];
        $current_user_info->login_from_localstorage = true;
    }
}

$login_session_id = filter_var($login_session_id, FILTER_SANITIZE_NUMBER_INT);

if ($login_session_cache && !empty($login_session_id) && !empty($session_time_stamp)) {

    $login_session_folder = 'assets/cache/login_sessions/sessions/'.date("mY", $session_time_stamp).'/';

    $cachefile = $login_session_folder.'session_'.$login_session_id.'_'.$access_code.'.cache';
    if (file_exists($cachefile)) {
        $saved_user_info = file_get_contents($cachefile);
        $saved_user_info = json_decode($saved_user_info, true);

        if (isset($saved_user_info[0])) {
            if (isset($saved_user_info[0]['id']) && !empty($saved_user_info[0]['id'])) {
                $validate_login_sql = false;
                $get_current_user_info = $saved_user_info;
            }
        }
    }
}

if ($validate_login_sql) {
    $join = [
        "[>]site_users_settings(settings)" => ["login_sessions.user_id" => "user_id"],
        "[>]site_users" => ["login_sessions.user_id" => "user_id"],
        "[>]site_roles" => ["site_users.site_role_id" => "site_role_id"]
    ];

    $join["[>]custom_fields_values"] = [
        "login_sessions.user_id" => "user_id",
        "AND" => ["field_id" => '6']
    ];

    $columns = [
        'login_sessions.user_id(id)', 'site_users.display_name(name)', 'site_users.email_address', 'settings.time_zone',
        'site_users.username', 'site_users.site_role_id(site_role)', 'settings.language_id', 'settings.offline_mode', 'settings.user_setting_id',
        'settings.notification_tone', 'settings.deactivated', 'settings.color_scheme', 'custom_fields_values.field_value(country_code)',
        'custom_fields_values.field_value_id(country_code_field_value_id)', 'site_users.online_status', 'login_sessions.last_access(last_access)',
        'site_users.geo_latitude', 'site_users.geo_longitude', 'site_users.last_seen_on', 'login_sessions.log_device', 'login_sessions.login_from_user_id',
        'site_roles.site_role_attribute', 'login_sessions.csrf_token', 'login_sessions.csrf_token_generated_on', 'site_users.profile_bg_image',
        'login_sessions.login_session_id', 'site_users.profile_picture', 'site_roles.role_hierarchy', 'login_sessions.access_code',
        'login_sessions.time_stamp',
    ];
    $where = [
        "login_sessions.login_session_id" => $login_session_id,
        "login_sessions.status" => 1,
        "ORDER" => ["login_sessions.login_session_id" => "DESC"],
        'LIMIT' => 1
    ];

    $get_current_user_info = DB::connect()->select('login_sessions', $join, $columns, $where);


    if (is_array($get_current_user_info) && isset($get_current_user_info[0])) {
        $invalid_login_session = true;

        if ($get_current_user_info[0]['access_code'] == $access_code) {
            if ($get_current_user_info[0]['time_stamp'] == $session_time_stamp) {
                $invalid_login_session = false;
            }
        }

        if ($invalid_login_session) {
            $get_current_user_info = array();
        }

    }

    if (isset($get_current_user_info[0]) && $login_session_cache) {
        $login_session_cache_store = true;
    }
}

if ($current_user_info->login_from_localstorage && !isset($get_current_user_info[0])) {
    $current_user_info->remove_storage_login_access = true;
}

if (isset($get_current_user_info[0])) {
    if (isset($get_current_user_info[0]['id']) && !empty($get_current_user_info[0]['id'])) {

        $current_user_info = json_decode(json_encode($get_current_user_info[0]));
        $current_user_info->logged_in = true;

        $time_diff_in_seconds = strtotime($current_time_stamp) - strtotime($current_user_info->last_access);
        $device_log_time_diff_in_seconds = 0;

        if (isset($current_user_info->log_device) && !empty($current_user_info->log_device)) {
            if ($time_diff_in_seconds > 600) {

                $last_log_condition = array();
                $last_log_condition['site_users_device_logs.login_session_id'] = $login_session_id;
                $last_log_condition['site_users_device_logs.ip_address'] = $current_user_ip_address;
                $last_log_condition['ORDER'] = ["site_users_device_logs.access_log_id" => "DESC"];
                $last_log_condition['LIMIT'] = 1;

                $last_device_log = DB::connect()->select('site_users_device_logs', ['created_on'], $last_log_condition);

                if (isset($last_device_log[0])) {
                    $device_log_time_diff_in_seconds = strtotime($current_time_stamp) - strtotime($last_device_log[0]['created_on']);
                }

                if (!isset($last_device_log[0]) || $device_log_time_diff_in_seconds > 3600) {
                    $device_log['login_session_id'] = $login_session_id;
                    $device_log['ip_address'] = $current_user_ip_address;
                    $device_log['user_agent'] = $current_user_agent;
                    $device_log['user_id'] = $current_user_info->id;
                    $device_log['created_on'] = $current_time_stamp;

                    DB::connect()->insert('site_users_device_logs', $device_log);
                }

                $login_session_cache_store = false;
                $login_session_cache_delete = true;

                DB::connect()->update('login_sessions', ['last_access' => $current_time_stamp], ['login_session_id' => $login_session_id]);
            }
        }

        if (isset($current_user_info->geo_latitude) && $current_user_info->geo_latitude == '-75.25097300') {
            $current_user_info->geo_latitude = null;
        }

        if (isset($current_user_info->geo_latitude) && $current_user_info->geo_latitude == '0.00000000') {
            $current_user_info->geo_latitude = null;
        }

        if (isset($current_user_info->geo_longitude) && $current_user_info->geo_longitude == '-0.07138900') {
            $current_user_info->geo_longitude = null;
        }

        if ($time_diff_in_seconds > 300) {
            $login_session_cache_store = false;
            $login_session_cache_delete = true;
            DB::connect()->update('site_users', ['last_seen_on' => $current_time_stamp], ['user_id' => $current_user_info->id]);
        }

        if ($geoip_service) {
            if (empty($current_user_info->time_zone) || empty($current_user_info->country_code)) {

                if (empty($current_user_info->time_zone) || empty($current_user_info->country_code)) {
                    include 'fns/firewall/user_ip_info.php';
                    $user_ip_info = user_ip_info($current_user_ip_address);
                }

                if (empty($current_user_info->time_zone)) {
                    $data = array();
                    $data['user_id'] = $get_current_user_info[0]['id'];

                    if (!isset($user_ip_info['timezone']) || empty($user_ip_info['timezone'])) {
                        $user_ip_info['timezone'] = 'default';
                    }

                    $data['time_zone'] = $user_ip_info['timezone'];
                    $current_user_info->time_zone = $user_ip_info['timezone'];

                    $data['updated_on'] = $current_time_stamp;
                    $login_session_cache_store = false;

                    if (empty($current_user_info->user_setting_id)) {
                        DB::connect()->insert('site_users_settings', $data);
                    } else {
                        DB::connect()->update('site_users_settings', $data, ['user_id' => $data['user_id']]);
                    }
                }

                if (empty($current_user_info->country_code)) {

                    if (!isset($user_ip_info['countryCode']) || empty($user_ip_info['countryCode'])) {
                        $user_ip_info['countryCode'] = 'US';
                    }

                    $data = array();
                    $data['current_user_id'] = $get_current_user_info[0]['id'];
                    $data['custom_field_value'] = $user_ip_info['countryCode'];
                    $data['custom_field_id'] = 6;
                    $data['current_time_stamp'] = $current_time_stamp;
                    $login_session_cache_store = false;

                    if (isset($user_ip_info['countryCode']) && !empty($user_ip_info['countryCode']) && empty($current_user_info->country_code_field_value_id)) {

                        $sql_query = 'INSERT INTO <custom_fields_values> (<user_id>, <field_value>, <field_id>, <updated_on>) ';
                        $sql_query .= 'SELECT * FROM (SELECT :current_user_id AS n_user_id, :custom_field_value as n_field_value, :custom_field_id as n_field_id, :current_time_stamp as n_timestamp) AS country_field WHERE NOT EXISTS ';
                        $sql_query .= '(SELECT <field_value_id> FROM <custom_fields_values> WHERE <user_id> = :current_user_id AND <field_id> = :custom_field_id) LIMIT 1;';

                        DB::connect()->query($sql_query, $data);

                    } else {
                        $data = array();
                        $data['user_id'] = $get_current_user_info[0]['id'];
                        $data['field_value'] = $user_ip_info['countryCode'];
                        $data['field_id'] = 6;
                        $data['updated_on'] = $current_time_stamp;

                        DB::connect()->update('custom_fields_values', $data, ['field_value_id' => $current_user_info->country_code_field_value_id]);
                    }
                }
            }
        }
    }
}



if ($login_session_cache && $current_user_info->logged_in) {

    if (!file_exists('assets/cache/login_sessions/')) {
        mkdir('assets/cache/login_sessions/');
    }

    if (!file_exists('assets/cache/login_sessions/sessions/')) {
        mkdir('assets/cache/login_sessions/sessions/');
    }

    $login_session_folder = 'assets/cache/login_sessions/sessions/'.date("mY", $session_time_stamp).'/';

    if (!file_exists($login_session_folder)) {
        mkdir($login_session_folder);
    }

    $cachefile = $login_session_folder.'session_'.$login_session_id.'_'.$access_code.'.cache';


    if ($login_session_cache_store) {

        if (file_exists($cachefile)) {
            unlink($cachefile);
        }

        $cache = json_encode($get_current_user_info, true);
        $cachefile = fopen($cachefile, "w");
        fwrite($cachefile, $cache);
        fclose($cachefile);

    } else if ($login_session_cache_delete) {
        if (file_exists($cachefile)) {
            unlink($cachefile);
        }
    }
}

$current_user_info->login_session_id = $login_session_id;
$current_user_info->ip_address = $current_user_ip_address;
$current_user_info->user_agent = $current_user_agent;
$current_user_info->banned = $current_user_banned;
$current_user_info->time_stamp = $current_time_stamp;
$current_user_info->allowed_file_types = '';