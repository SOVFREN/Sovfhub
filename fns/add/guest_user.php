<?php

include 'fns/filters/load.php';

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->invalid_value;
$result['error_key'] = 'invalid_value';
$result['error_variables'] = ['nickname'];
$noerror = true;
$strict_mode = true;


if (Registry::load('settings')->non_latin_usernames === 'enable') {
    $strict_mode = false;
}

if (!isset($data['nickname'])) {
    $noerror = false;
} else {
    $data['username'] = sanitize_username($data['nickname'], $strict_mode);
    if (empty(trim($data['username']))) {
        $noerror = false;
    }
}

if (Registry::load('settings')->guest_login !== 'enable') {
    $result['error_message'] = Registry::load('strings')->went_wrong;
    $result['error_key'] = 'something_went_wrong';
    $noerror = false;
}

if (!Registry::load('current_user')->logged_in) {

    if (isset(Registry::load('settings')->ip_intelligence) && Registry::load('settings')->ip_intelligence !== 'disable') {
        if (Registry::load('settings')->ip_intel_validate_on_login === 'yes') {
            include 'fns/ip_intelligence/load.php';
            $ip_intelligence = ip_intelligence();

            if (!$ip_intelligence['success']) {
                $result['error_message'] = Registry::load('strings')->ip_blacklisted;
                $result['error_key'] = 'ip_blacklisted';
                $noerror = false;
            }
        }
    }

    if ($noerror && isset($data['nickname'])) {
        if (!isset($data['terms_agreement']) || $data['terms_agreement'] !== 'agreed') {
            $result['error_variables'] = ['terms_agreement'];
            $result['error_message'] = Registry::load('strings')->requires_consent;
            $result['error_key'] = 'terms_agreement';
            $noerror = false;
        }
    }

    if (isset(Registry::load('settings')->captcha) && Registry::load('settings')->captcha !== 'disable') {
        include 'fns/captcha/load.php';
    }

    if (isset(Registry::load('settings')->captcha) && Registry::load('settings')->captcha === 'google_recaptcha_v2') {
        if (!isset($data['g-recaptcha-response']) || empty(trim($data['g-recaptcha-response']))) {
            $result['error_message'] = Registry::load('strings')->invalid_captcha;
            $result['error_variables'][] = 'captcha';
            $noerror = false;
        } else if (!validate_captcha('google_recaptcha_v2', $data['g-recaptcha-response'])) {
            $result['error_message'] = Registry::load('strings')->invalid_captcha;
            $result['error_variables'][] = 'captcha';
            $noerror = false;
        }
    } else if (isset(Registry::load('settings')->captcha) && Registry::load('settings')->captcha === 'hcaptcha') {
        if (!isset($data['h-captcha-response']) || empty(trim($data['h-captcha-response']))) {
            $result['error_message'] = Registry::load('strings')->invalid_captcha;
            $result['error_variables'][] = 'captcha';
            $noerror = false;
        } else if (!validate_captcha('hcaptcha', $data['h-captcha-response'])) {
            $result['error_message'] = Registry::load('strings')->invalid_captcha;
            $result['error_variables'][] = 'captcha';
            $noerror = false;
        }
    } elseif (isset(Registry::load('settings')->captcha) && Registry::load('settings')->captcha === 'cloudflare_turnstile') {
        if (!isset($data['cf-turnstile-response']) || empty(trim($data['cf-turnstile-response']))) {
            $result['error_message'] = Registry::load('strings')->invalid_captcha;
            $result['error_variables'][] = 'captcha';
            $noerror = false;
        } elseif (!validate_captcha('cloudflare_turnstile', $data['cf-turnstile-response'])) {
            $result['error_message'] = Registry::load('strings')->invalid_captcha;
            $result['error_variables'][] = 'captcha';
            $noerror = false;
        }
    }
}

if ($noerror && isset($data['nickname'])) {
    $data['nickname'] = sanitize_nickname($data['nickname']);
    if (!empty($data['nickname'])) {
        $nickname_length = mb_strlen($data['nickname']);

        if (!empty(Registry::load('settings')->minimum_guest_nickname_length)) {
            if ($nickname_length < Registry::load('settings')->minimum_guest_nickname_length) {
                $data['nickname'] = null;
                $result['error_message'] = Registry::load('strings')->requires_minimum_guest_nickname_length;
                $result['error_message'] .= ' ['.Registry::load('settings')->minimum_guest_nickname_length.']';
                $result['error_key'] = 'requires_minimum_nickname_length';
                $result['error_variables'][] = 'nickname';
                $noerror = false;
            }
        }
        if (!empty(Registry::load('settings')->maximum_guest_nickname_length)) {
            if ($nickname_length > Registry::load('settings')->maximum_guest_nickname_length) {
                $data['nickname'] = null;
                $result['error_message'] = Registry::load('strings')->exceeds_guest_nickname_length;
                $result['error_message'] .= ' ['.Registry::load('settings')->maximum_guest_nickname_length.']';
                $result['error_key'] = 'exceeds_nickname_length';
                $result['error_variables'][] = 'nickname';
                $noerror = false;
            }
        }
    } else {
        $noerror = false;
    }
}

if ($noerror) {
    $additional_data = $data;

    $indexesToUnset = array('add', 'username', 'nickname', 'terms_agreement', 'phone_number', 'update', 'remove', 'redirect','email_address');

    foreach ($indexesToUnset as $index) {
        if (isset($additional_data[$index])) {
            unset($additional_data[$index]);
        }
    }

    $guest_user = [
        'add' => 'site_users',
        'full_name' => $data['nickname'],
        'username' => $data['username'],
        'password' => random_string(['length' => 6]),
        'signup_page' => true,
        'return' => true
    ];

    if (username_exists($data['username'])) {
        $guest_user['username'] = $data['username'].'_'.random_string(['length' => 5]);
    }

    $guest_user['email_address'] = 'user_'.strtotime("now").'@'.random_string(['length' => 5]).'.guestuser';

    if (isset($data['redirect'])) {
        $guest_user['redirect'] = $data['redirect'];
    }


    if (!empty($additional_data)) {
        $guest_user = array_merge($guest_user, $additional_data);
    }

    $result = add($guest_user, ['force_request' => true, 'exclude_filters_function' => true, 'guest_user' => true]);
}

?>