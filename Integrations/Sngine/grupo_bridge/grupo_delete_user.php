<?php

require ABSPATH . "grupo_bridge/grupo_config.php";
require ABSPATH . "grupo_bridge/curl_function.php";

$delete_user = $db->query(sprintf("SELECT user_email FROM users WHERE user_id = %s", secure($user_id, 'int'))) or _error('SQL_ERROR_THROWEN');
$delete_user = $delete_user->fetch_assoc();

if (isset($delete_user['user_email'])) {

    $post_fields = [
        "api_secret_key" => $grupo_api_secret_key,
        "remove" => "site_users",
        "user" => $delete_user['user_email'],
    ];

    $api_request_url = rtrim($grupo_web_address, "/") . "/" . "api_request/";
    $curl_response = grupo_curl_Request($api_request_url, $post_fields, false);

}
?>