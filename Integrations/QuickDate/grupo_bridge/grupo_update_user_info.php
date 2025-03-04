<?php
require_once "grupo_bridge/grupo_config.php";
require_once "grupo_bridge/curl_function.php";


$update_user = null;
if (isset(self::ActiveUser()->email)) {
    $update_user = self::ActiveUser()->email;
}

if (!empty($update_user)) {
    $update_request = false;
    $post_fields = [
        "api_secret_key" => $grupo_api_secret_key,
        "update" => "site_users",
        "user" => $update_user,
    ];

    if (isset($user["first_name"]) && !empty($user["first_name"])) {
        $post_fields["full_name"] = $user["first_name"];
        $update_request = true;
    }
    
    if (isset(self::ActiveUser()->avater->full)) {
        $post_fields["avatarURL"] = self::ActiveUser()->avater->full;
        $update_request = true;
    }

    if (isset($user["username"])) {
        if (self::ActiveUser()->username !== $user["username"]) {
            $post_fields["username"] = $user["username"];
            $update_request = true;
        }
    }

    if (isset($user["email"])) {
        if (self::ActiveUser()->email !== $user["email"]) {
            $post_fields["email_address"] = $user["email"];
            $update_request = true;
        }
    }

    if ($update_request) {
        $api_request_url = rtrim($grupo_web_address, "/") . "/" . "api_request/";
        $curl_response = grupo_curl_Request($api_request_url, $post_fields, false);
    }
    
}
?>