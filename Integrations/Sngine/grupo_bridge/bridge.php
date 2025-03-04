<?php

user_access();

if (isset($user->_logged_in) && $user->_logged_in) {
    $email_address = $user->_data["user_email"];
    $create_account = "yes";
    $full_name = $user->_data["user_firstname"];
    $username = $user->_data["user_name"];
    $password = $user->_data["user_password"];
    $avatar = $user->_data["user_picture"];
    $site_role_id = "";

    if (empty($full_name)) {
        $full_name = $user->_data["user_name"];
    }

    $post_fields = [
        "api_secret_key" => $grupo_api_secret_key,
        "add" => "login_session",
        "create_account" => $create_account,
        "email_address" => $email_address,
        "full_name" => $full_name,
        "username" => $username,
        "password" => $password,
        "avatarURL" => $avatar,
        "site_role" => $site_role_id,
    ];

    if ($user->_is_admin) {
        $post_fields["site_role_attribute"] = "administrators";
    }

    $api_request_url = rtrim($grupo_web_address, "/") . "/" . "api_request/";
    $curl_response = grupo_curl_Request($api_request_url, $post_fields);

    if (!empty($curl_response)) {
        if ($curl_response['success']) {
            $auto_login_url = $curl_response['auto_login_url'];
            header("Location: $auto_login_url");
            die();
        } else {            
            echo $curl_response['error_message'];
        }
    }
}
