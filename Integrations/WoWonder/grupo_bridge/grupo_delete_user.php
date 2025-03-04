<?php

require_once "grupo_bridge/grupo_config.php";
require_once "grupo_bridge/curl_function.php";

$delete_user = null;

if (isset($wo['user']["email"])) {
    $delete_user = $wo['user']["email"];
}

if (!empty($delete_user)) {

    $post_fields = [
        "api_secret_key" => $grupo_api_secret_key,
        "remove" => "site_users",
        "user" => $delete_user,
    ];

    $api_request_url = rtrim($grupo_web_address, "/") . "/" . "api_request/";
    $curl_response = grupo_curl_Request($api_request_url, $post_fields, false);

}
?>