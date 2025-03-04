<?php
$user_id = $one_time_pin = 0;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$noerror = true;

if (isset($data['user_id'])) {
    $user_id = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);
}

if (isset($data['one_time_pin'])) {
    $one_time_pin = filter_var($data["one_time_pin"], FILTER_SANITIZE_NUMBER_INT);
}

if (!empty($user_id)) {

    $columns = $join = $where = null;

    $columns = [
        'site_users.user_id', 'site_users.one_time_pin', 'site_users.phone_number',
        'site_users.phone_verified', 'site_users.otp_generated_on',
    ];

    $where["site_users.user_id"] = $user_id;
    $where["LIMIT"] = 1;

    $site_user = DB::connect()->select('site_users', $columns, $where);

    if (isset($site_user[0])) {

        $site_user = $site_user[0];

        $result['error_message'] = Registry::load('strings')->invalid_otp_code;
        $result['error_key'] = 'invalid_otp_code';

        if (!Registry::load('current_user')->logged_in) {
            if (isset(Registry::load('settings')->captcha) && Registry::load('settings')->captcha !== 'disable') {
                include 'fns/captcha/load.php';
            }

            if (isset(Registry::load('settings')->captcha) && Registry::load('settings')->captcha === 'google_recaptcha_v2') {
                if (!isset($data['g-recaptcha-response']) || empty(trim($data['g-recaptcha-response']))) {
                    $result['error_message'] = Registry::load('strings')->invalid_captcha;
                    $result['error_variables'][] = 'captcha';
                    $noerror = false;
                } elseif (!validate_captcha('google_recaptcha_v2', $data['g-recaptcha-response'])) {
                    $result['error_message'] = Registry::load('strings')->invalid_captcha;
                    $result['error_variables'][] = 'captcha';
                    $noerror = false;
                }
            } elseif (isset(Registry::load('settings')->captcha) && Registry::load('settings')->captcha === 'hcaptcha') {
                if (!isset($data['h-captcha-response']) || empty(trim($data['h-captcha-response']))) {
                    $result['error_message'] = Registry::load('strings')->invalid_captcha;
                    $result['error_variables'][] = 'captcha';
                    $noerror = false;
                } elseif (!validate_captcha('hcaptcha', $data['h-captcha-response'])) {
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

        if ($noerror && !empty($one_time_pin) && !empty($site_user['one_time_pin']) && (int)$site_user['one_time_pin'] === (int)$one_time_pin) {

            $one_time_pin = random_int(100000, 999999);
            $update_data = ['updated_on' => Registry::load('current_user')->time_stamp];
            $update_data["phone_verified"] = 1;
            $update_data["one_time_pin"] = $one_time_pin;

            DB::connect()->update("site_users", $update_data, ["user_id" => $user_id]);

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['alert'] = Registry::load('strings')->phone_number_verified;
        } else {


            if (isset($site_user['otp_generated_on']) && !empty($site_user['otp_generated_on'])) {
                $to_time = strtotime($site_user['otp_generated_on']);
                $from_time = strtotime("now");
                $time_difference = round(abs($to_time - $from_time), 2);

                if ($time_difference > 120) {
                    if (isset($site_user['phone_number']) && !empty($site_user['phone_number'])) {
                        $one_time_pin = random_int(100000, 999999);
                        $update_data = ['otp_generated_on' => Registry::load('current_user')->time_stamp];
                        $update_data["one_time_pin"] = $one_time_pin;
                        DB::connect()->update("site_users", $update_data, ["user_id" => $user_id]);

                        include 'fns/sms_gateway/load.php';
                        $otp_message = Registry::load('strings')->registration_otp_message.' '.$one_time_pin;
                        $sms_data = ['message' => $otp_message, 'phone_number' => $site_user['phone_number']];
                        $sms_response = send_sms($sms_data);
                    }
                }
            }
        }
    }
}