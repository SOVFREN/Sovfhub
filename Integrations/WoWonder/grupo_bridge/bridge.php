<?php

$exit_URL = "https://" . full_url($_SERVER);
$exit_URL = str_replace("login_to_chatroom.php", "", $exit_URL);

if (isset($wo["loggedin"]) && $wo["loggedin"] == true) {
    $email_address = $wo["user"]["email"];
    $create_account = "yes";
    $full_name = $wo["user"]["name"];
    $username = $wo["user"]["username"];
    $password = $wo["user"]["password"];
    $avatar = $wo["user"]["avatar"];
    $site_role_id = "";

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

    if (Wo_IsAdmin() === true) {
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

} else {
    header("Location: $exit_URL");
    exit();
}