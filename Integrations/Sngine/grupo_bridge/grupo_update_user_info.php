<?php

require ABSPATH . "grupo_bridge/grupo_config.php";
require ABSPATH . "grupo_bridge/curl_function.php";

$update_user = null;
if (isset($user->_data["user_email"])) {
    $update_user = $user->_data["user_email"];
} elseif (isset($this->_data["user_email"])) {
    $update_user = $this->_data["user_email"];
}

if (!empty($update_user)) {
    $update_request = false;
    $post_fields = [
        "api_secret_key" => $grupo_api_secret_key,
        "update" => "site_users",
        "user" => $update_user,
    ];

    if (isset($args["firstname"])) {
        $post_fields["full_name"] = $args["firstname"];
        $update_request = true;
    }

    if (isset($full_picture)) {
        $post_fields["avatarURL"] =
            $system["system_uploads"] . "/" . $full_picture;
        $update_request = true;
    }

    if (isset($args["username"])) {
        if ($this->_data["user_name"] !== $args["username"]) {
            $post_fields["username"] = $args["username"];
            $update_request = true;
        }
    }

    if (isset($args["email"])) {
        if ($this->_data["user_email"] !== $args["email"]) {
            $post_fields["email_address"] = $args["email"];
            $update_request = true;
        }
    }

    if ($update_request) {
        $api_request_url = rtrim($grupo_web_address, "/") . "/" . "api_request/";
        $curl_response = grupo_curl_Request($api_request_url, $post_fields, false);
    }
}
?>
