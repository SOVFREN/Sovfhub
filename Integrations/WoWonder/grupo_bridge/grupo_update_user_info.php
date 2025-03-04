<?php
require_once "grupo_bridge/grupo_config.php";
require_once "grupo_bridge/curl_function.php";


$update_user = null;
if (isset($Userdata["email"])) {
    $update_user = $Userdata["email"];
}
if (!empty($update_user)) {
    $update_request = false;
    $post_fields = [
        "api_secret_key" => $grupo_api_secret_key,
        "update" => "site_users",
        "user" => $update_user,
    ];

    if (isset($Update_data["first_name"])) {
        $post_fields["full_name"] = $Update_data["first_name"];
        $update_request = true;
    }

    if (isset($userdata2["avatar"])) {
        $post_fields["avatarURL"] = $userdata2["avatar"];
        $update_request = true;
    }

    if (isset($Update_data["username"])) {
        if ($Userdata["username"] !== $Update_data["username"]) {
            $post_fields["username"] = $Update_data["username"];
            $update_request = true;
        }
    }

    if (isset($Update_data["email"])) {
        if ($Userdata["email"] !== $Update_data["email"]) {
            $post_fields["email_address"] = $Update_data["email"];
            $update_request = true;
        }
    }



    if ($update_request) {
        $api_request_url = rtrim($grupo_web_address, "/") . "/" . "api_request/";
        $curl_response = grupo_curl_Request($api_request_url, $post_fields, false);
    }
    
}
?>