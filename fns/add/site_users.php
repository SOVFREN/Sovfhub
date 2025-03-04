<?php

if (!isset($private_data['exclude_filters_function'])) {
    include 'fns/filters/load.php';
}

if (!isset($private_data['exclude_files_function'])) {
    include 'fns/files/load.php';
}

include_once('fns/filters/profanity.php');
include_once('fns/cloud_storage/load.php');
use Snipe\BanBuilder\CensorWords;

$noerror = true;
$disabled = 0;
$send_otp = false;
$strict_mode = true;
$email_login_link = false;
$required_fields = ['full_name', 'username', 'email_address', 'password'];
$validate_custom_fields = true;
$create_user = $created_by_admin = $require_email_verification = false;
$send_welcome_email = false;
$notify_admins_pending_approval = false;
$currentMonthYear = date('mY');
$update_image_data = array();

if (!$force_request) {
    $required_fields[] = 'confirm_password';
}


if (!Registry::load('current_user')->logged_in && Registry::load('settings')->send_welcome_email === 'enable') {
    $send_welcome_email = true;
}

if ($force_request || role(['permissions' => ['site_users' => 'create_user']])) {
    $create_user = true;
    $created_by_admin = true;
    $validate_custom_fields = false;
    $send_welcome_email = false;
}

if (!$force_request) {
    if (!Registry::load('current_user')->logged_in && Registry::load('settings')->user_registration === 'enable') {
        $create_user = true;
    } elseif (!Registry::load('current_user')->logged_in && Registry::load('settings')->user_registration !== 'enable') {
        $result = array();
        $result['success'] = false;
        $result['error_message'] = Registry::load('strings')->went_wrong;
        $result['error_key'] = 'something_went_wrong';
        $result['error_variables'] = [];
    }
}

if (isset($private_data['guest_user']) && $private_data['guest_user']) {
    $validate_custom_fields = true;
    $send_welcome_email = false;
}

if ($create_user) {
    $result = array();
    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];


    $columns = $where = null;
    $columns = ['custom_fields.field_id', 'custom_fields.string_constant(field_name)', 'custom_fields.field_type', 'custom_fields.required'];
    $where['AND'] = ['custom_fields.field_category' => 'profile', 'custom_fields.disabled' => 0];

    if (!$force_request) {
        if (isset($data['signup_page'])) {
            $where['AND']['custom_fields.show_on_signup'] = 1;
        }
    } else if (isset($private_data['guest_user']) && $private_data['guest_user']) {
        $where['AND']['custom_fields.show_on_guest_login'] = 1;
    }

    $where["ORDER"] = ["custom_fields.field_id" => "ASC"];
    $custom_fields = DB::connect()->select('custom_fields', $columns, $where);

    if (Registry::load('settings')->non_latin_usernames === 'enable') {
        $strict_mode = false;
    }

    if (!Registry::load('current_user')->logged_in && isset($data['signup_page']) || $created_by_admin) {
        if (Registry::load('settings')->hide_email_address_field_in_registration_page === 'yes') {
            if (!isset($data['email_address']) || empty($data['email_address'])) {
                $data['email_address'] = 'user_'.strtotime("now").'@'.random_string(['length' => 10]).'.user';
            }
        }

        if (Registry::load('settings')->hide_name_field_in_registration_page === 'yes') {
            if (!isset($data['full_name']) || empty($data['full_name'])) {
                if (isset($data['username']) && !empty($data['username'])) {
                    $data['full_name'] = $data['username'];
                } else {
                    $data['full_name'] = 'user_'.strtotime("now");
                }
            }
        }

        if (Registry::load('settings')->hide_username_field_in_registration_page === 'yes') {
            if (!isset($data['username']) || empty($data['username'])) {
                $data['username'] = 'user_'.strtotime("now").'_'.random_string(['length' => 5]);
            }
        }
    }

    if (isset($data['full_name'])) {
        $data['full_name'] = trim($data['full_name']);
        $data['full_name'] = strip_tags($data['full_name']);
        $data['full_name'] = preg_replace('|\s+|', ' ', $data['full_name']);
        $data['full_name'] = htmlspecialchars($data['full_name'], ENT_QUOTES, 'UTF-8');
    }

    if (isset($data['full_name'])) {
        if (Registry::load('settings')->profanity_filter_full_name !== 'disable') {
            $safe_mode = true;

            if (Registry::load('settings')->profanity_filter_full_name === 'strict_mode') {
                $safe_mode = false;
            }

            $censor = new CensorWords();
            $censor_name = $censor->censorString($data['full_name'], $safe_mode);
            if (isset($censor_name['matched']) && !empty($censor_name['matched'])) {
                $data['full_name'] = null;
                $result['error_message'] = Registry::load('strings')->name_censored_word_detected;
                $result['error_key'] = 'name_censored_word_detected';
            }
        }
    }

    if (isset($data['username'])) {
        $data['username'] = sanitize_username($data['username'], $strict_mode);
    }

    if (isset($data['username'])) {
        if (Registry::load('settings')->profanity_filter_username !== 'disable') {
            $safe_mode = true;

            if (Registry::load('settings')->profanity_filter_username === 'strict_mode') {
                $safe_mode = false;
            }

            $censor = new CensorWords();
            $censor_name = $censor->censorString($data['username'], $safe_mode);
            if (isset($censor_name['matched']) && !empty($censor_name['matched'])) {
                $data['username'] = null;
                $result['error_message'] = Registry::load('strings')->username_censored_word_detected;
                $result['error_key'] = 'username_censored_word_detected';
            }
        }
    }

    if (isset($data['email_address']) && !filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
        $data['email_address'] = null;
        $result['error_message'] = Registry::load('strings')->invalid_email_address;
        $result['error_key'] = 'invalid_email_address';
    }

    if (!Registry::load('current_user')->logged_in && !$force_request) {
        if (Registry::load('settings')->hide_phone_number_field_in_registration_page !== 'yes') {
            $required_fields[] = 'phone_number';

            if (isset($data['phone_number'])) {
                $data['phone_number'] = sanitize_phone_number($data['phone_number']);
                if (empty($data['phone_number'])) {
                    $result['error_message'] = Registry::load('strings')->invalid_phone_number;
                    $result['error_key'] = 'invalid_phone_number';
                }
            }
        }
    }

    if (isset($data['phone_number']) && !empty($data['phone_number']) && $created_by_admin) {
        $required_fields[] = 'phone_number';
        $data['phone_number'] = sanitize_phone_number($data['phone_number']);
        if (empty($data['phone_number'])) {
            $result['error_message'] = Registry::load('strings')->invalid_phone_number;
            $result['error_key'] = 'invalid_phone_number';
        }
    }

    if ($validate_custom_fields) {
        foreach ($custom_fields as $custom_field) {
            if ((int)$custom_field['required'] === 1) {
                $required_fields[] = $custom_field['field_name'];
                $custom_field_name = $custom_field['field_name'];

                if (isset($data[$custom_field_name]) && !empty($data[$custom_field_name])) {
                    if ($custom_field['field_type'] === 'number') {
                        $data[$custom_field_name] = filter_var($data[$custom_field_name], FILTER_SANITIZE_NUMBER_INT);
                        if (empty($data[$custom_field_name])) {
                            $data[$custom_field_name] = '';
                        }
                    } elseif ($custom_field['field_type'] === 'link') {
                        $data[$custom_field_name] = filter_var($data[$custom_field_name], FILTER_SANITIZE_URL);
                        if (empty($data[$custom_field_name]) || !filter_var($data[$custom_field_name], FILTER_VALIDATE_URL)) {
                            $data[$custom_field_name] = '';
                        }
                    }
                }
            }
        }
    }

    foreach ($required_fields as $required_field) {
        if (!isset($data[$required_field]) || empty(trim($data[$required_field]))) {
            $result['error_variables'][] = [$required_field];
            $noerror = false;
        }
    }

    if (!$force_request) {

        if (!Registry::load('current_user')->logged_in && Registry::load('settings')->enable_photo_upload_on_signup === 'required') {
            if (!isset($_FILES['custom_avatar']['name']) || empty($_FILES['custom_avatar']['name']) || !isImage($_FILES['custom_avatar']['tmp_name'])) {
                $result['error_message'] = Registry::load('strings')->missing_profile_image;
                $result['error_key'] = 'missing_profile_image';
                $result['error_variables'][] = 'custom_avatar';
                $noerror = false;
            }
        }


        if (isset($data['full_name']) && !empty($data['full_name'])) {

            $full_name_length = mb_strlen($data['full_name']);

            if (!empty(Registry::load('settings')->minimum_full_name_length)) {
                if ($full_name_length < Registry::load('settings')->minimum_full_name_length) {
                    $data['full_name'] = null;
                    $result['error_message'] = Registry::load('strings')->requires_minimum_full_name_length;
                    $result['error_message'] .= ' ['.Registry::load('settings')->minimum_full_name_length.']';
                    $result['error_key'] = 'requires_minimum_full_name_length';
                    $result['error_variables'][] = 'full_name';
                    $noerror = false;
                }
            }
            if (!empty(Registry::load('settings')->maximum_full_name_length)) {
                if ($full_name_length > Registry::load('settings')->maximum_full_name_length) {
                    $data['full_name'] = null;
                    $result['error_message'] = Registry::load('strings')->exceeds_full_name_length;
                    $result['error_message'] .= ' ['.Registry::load('settings')->maximum_full_name_length.']';
                    $result['error_key'] = 'exceeds_full_name_length';
                    $result['error_variables'][] = 'full_name';
                    $noerror = false;
                }
            }
        }

        if (isset($data['username']) && !empty($data['username'])) {
            $user_name_length = mb_strlen($data['username']);
            if (!empty(Registry::load('settings')->minimum_username_length)) {
                if ($user_name_length < Registry::load('settings')->minimum_username_length) {
                    $data['username'] = null;
                    $result['error_message'] = Registry::load('strings')->requires_minimum_username_length;
                    $result['error_message'] .= ' ['.Registry::load('settings')->minimum_username_length.']';
                    $result['error_key'] = 'requires_minimum_username_length';
                    $result['error_variables'][] = 'username';
                    $noerror = false;
                }
            }
            if (!empty(Registry::load('settings')->maximum_username_length)) {
                if ($user_name_length > Registry::load('settings')->maximum_username_length) {
                    $data['username'] = null;
                    $result['error_message'] = Registry::load('strings')->exceeds_username_length;
                    $result['error_message'] .= ' ['.Registry::load('settings')->maximum_username_length.']';
                    $result['error_key'] = 'exceeds_username_length';
                    $result['error_variables'][] = 'username';
                    $noerror = false;
                }
            }
        }
    }

    if (!$force_request) {

        if (isset(Registry::load('settings')->ip_intelligence) && Registry::load('settings')->ip_intelligence !== 'disable') {
            if (Registry::load('settings')->ip_intel_validate_on_register === 'yes') {
                include 'fns/ip_intelligence/load.php';
                $ip_intelligence = ip_intelligence();

                if (!$ip_intelligence['success']) {
                    $result['error_message'] = Registry::load('strings')->ip_blacklisted;
                    $result['error_key'] = 'ip_blacklisted';
                    $noerror = false;
                }
            }
        }

        if (isset($data['password']) && !empty($data['password'])) {
            if (!isset($data['confirm_password']) || isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
                $result['error_variables'] = ['password', 'confirm_password'];
                $result['error_message'] = Registry::load('strings')->password_doesnt_match;
                $result['error_key'] = 'password_doesnt_match';
                $noerror = false;
            }
        }

        if (!Registry::load('current_user')->logged_in) {
            $required_fields[] = 'terms_agreement';
            if (!isset($data['terms_agreement']) || $data['terms_agreement'] !== 'agreed') {
                $result['error_variables'] = ['terms_agreement'];
                $result['error_message'] = Registry::load('strings')->requires_consent;
                $result['error_key'] = 'terms_agreement';
                $noerror = false;
            }

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
    }

    if (isset($data['email_address']) && !empty($data['email_address'])) {
        $data['email_address'] = htmlspecialchars(trim($data['email_address']), ENT_QUOTES, 'UTF-8');
        $email_exists = DB::connect()->select('site_users', 'site_users.user_id', ['site_users.email_address' => $data['email_address']]);

        if (isset($email_exists[0])) {
            $result['error_variables'] = ['email_address'];
            $result['error_message'] = Registry::load('strings')->email_exists;
            $result['error_key'] = 'email_exists';
            $noerror = false;
        }

        if (Registry::load('settings')->email_validator === 'enable' || Registry::load('settings')->email_validator === 'strict_mode') {

            if (!isset($private_data['guest_user']) || !$private_data['guest_user']) {
                $email_validator = email_validator($data['email_address']);

                if (!$email_validator["success"]) {
                    $result['error_variables'] = ['email_address'];
                    $result['error_key'] = 'email_validation_failed';
                    $noerror = false;

                    if ($email_validator["reason"] === "blacklisted") {
                        $result['error_message'] = Registry::load('strings')->email_domain_not_allowed;
                        $result['error_key'] = 'email_domain_blacklisted';
                    } else if ($email_validator["reason"] === "not_whitelisted") {
                        $result['error_message'] = Registry::load('strings')->email_domain_not_allowed;
                        $result['error_key'] = 'email_domain_not_allowed';
                    }

                }
            }
        }
    }

    if (isset($data['username']) && !empty($data['username'])) {
        if (username_exists($data['username'])) {
            $result['error_variables'] = ['username'];
            $result['error_message'] = Registry::load('strings')->username_exists;
            $result['error_key'] = 'username_exists';
            $noerror = false;
        }
    }

    if (isset($data['phone_number']) && !empty($data['phone_number'])) {
        if (phone_number_exists($data['phone_number'])) {
            $result['error_variables'] = ['phone_number'];
            $result['error_message'] = Registry::load('strings')->phone_number_exists;
            $result['error_key'] = 'phone_number_exists';
            $noerror = false;
        }
    }


    if ($noerror) {

        if ($created_by_admin && isset($data['disabled']) && $data['disabled'] === 'yes') {
            $disabled = 1;
        }

        $site_role = 1;

        if (isset($private_data['guest_user']) && $private_data['guest_user']) {
            if (isset(Registry::load('site_role_attributes')->guest_users)) {
                $site_role = Registry::load('site_role_attributes')->guest_users;
            }
        } elseif (!$force_request && !$created_by_admin && Registry::load('settings')->user_email_verification === 'enable') {
            $require_email_verification = true;
            if (isset(Registry::load('site_role_attributes')->unverified_users)) {
                $site_role = Registry::load('site_role_attributes')->unverified_users;
            }
        } else {
            if (isset(Registry::load('site_role_attributes')->default_site_role)) {
                $site_role = Registry::load('site_role_attributes')->default_site_role;
            }
        }

        if ($created_by_admin && isset($data['site_role']) && !empty($data['site_role'])) {
            $check_site_role_condition = ["site_roles.site_role_id" => $data['site_role']];

            if (!$force_request && Registry::load('current_user')->site_role_attribute !== 'administrators') {
                $check_site_role_condition['site_roles.role_hierarchy[<]'] = Registry::load('current_user')->role_hierarchy;
            }

            $check_site_role = DB::connect()->select('site_roles', ['site_roles.site_role_id'], $check_site_role_condition);
            if (isset($check_site_role[0])) {
                $site_role = $data['site_role'];
            }
        }

        if ($created_by_admin && isset($data['site_role_attribute']) && !empty($data['site_role_attribute'])) {
            $check_site_role = DB::connect()->select('site_roles', ['site_roles.site_role_id'], ["site_roles.site_role_attribute" => $data['site_role_attribute']]);
            if (isset($check_site_role[0])) {
                $site_role = $check_site_role[0]['site_role_id'];
            }
        }


        if ($created_by_admin && isset($data['email_login_link']) && $data['email_login_link'] === 'yes') {
            $email_login_link = true;
        }


        $verification_code = random_string(['length' => 10]);
        $approved = 1;

        if (!$force_request && !$created_by_admin && Registry::load('settings')->new_user_approval === 'enable') {
            $approved = 0;
            $send_welcome_email = false;
        }

        if ($force_request) {
            if (isset($data['requires_user_approval'])) {
                if ($data['requires_user_approval']) {
                    $approved = 0;
                } else {
                    $approved = 1;
                }
            }
        }

        if ((int)$approved === 0) {
            $notify_admins_pending_approval = true;
        }

        $insert_data = [
            "display_name" => $data['full_name'],
            "username" => $data['username'],
            "email_address" => $data['email_address'],
            "password" => password_hash($data['password'], PASSWORD_BCRYPT),
            "encrypt_type" => 'php_password_hash',
            "salt" => '',
            "site_role_id" => $site_role,
            "approved" => $approved,
            "previous_site_role_id" => $site_role,
            "verification_code" => $verification_code,
            "created_on" => Registry::load('current_user')->time_stamp,
            "updated_on" => Registry::load('current_user')->time_stamp,
        ];

        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            if (!Registry::load('current_user')->logged_in || $created_by_admin) {
                if (Registry::load('settings')->hide_phone_number_field_in_registration_page !== 'yes' || $created_by_admin) {
                    $insert_data["phone_number"] = $data['phone_number'];
                    $one_time_pin = random_int(100000, 999999);
                    $insert_data["one_time_pin"] = $one_time_pin;
                    $insert_data["otp_generated_on"] = Registry::load('current_user')->time_stamp;

                    if (Registry::load('settings')->phone_number_verification === 'enable' && !$created_by_admin) {
                        $insert_data["phone_verified"] = 0;
                        $send_otp = true;
                    }
                }
            }
        }

        if (!$created_by_admin && Registry::load('settings')->user_email_verification === 'enable' && $require_email_verification) {
            $insert_data["unverified_email_address"] = $data['email_address'];
        }

        if (isset($private_data['social_login_provider_id'])) {
            $private_data['social_login_provider_id'] = filter_var($private_data['social_login_provider_id'], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($private_data['social_login_provider_id'])) {
                $insert_data["social_login_provider_id"] = $private_data['social_login_provider_id'];
            }
        }

        if (isset($private_data['stay_online']) && $private_data['stay_online']) {
            $insert_data["stay_online"] = 1;
        }


        DB::connect()->insert("site_users", $insert_data);

        if (!DB::connect()->error) {
            $user_id = DB::connect()->id();

            $disable_private_messages = 0;

            if ($created_by_admin && isset($data['disable_private_messages']) && $data['disable_private_messages'] === 'yes') {
                $disable_private_messages = 1;
            }

            $insert_data = [
                "user_id" => $user_id,
                "time_zone" => '',
                "disable_private_messages" => $disable_private_messages,
                "updated_on" => Registry::load('current_user')->time_stamp,
            ];

            if ($created_by_admin && isset($data['deactivate']) && $data['deactivate'] === 'yes') {
                $insert_data["deactivated"] = 1;
            } elseif (isset($data['deactivate']) && $data['deactivate'] === 'no') {
                $insert_data["deactivated"] = 0;
            }

            $check_array = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

            if (isset($data['timezone']) && $data['timezone'] === 'Default') {
                $insert_data["time_zone"] = 'default';
            } elseif (isset($data['timezone']) && in_array($data['timezone'], $check_array)) {
                $insert_data["time_zone"] = $data['timezone'];
            }

            $check_array = glob('assets/files/alerts/*');

            if ($created_by_admin && isset($data['notification']) && in_array($data['notification'], $check_array)) {
                $insert_data["notification_sound"] = $data['notification'];
            }

            DB::connect()->insert("site_users_settings", $insert_data);

            if ($created_by_admin && isset($_FILES['custom_background']['name']) && !empty($_FILES['custom_background']['name'])) {
                if (isImage($_FILES['custom_background']['tmp_name'])) {

                    $update_image_data['profile_bg_image'] = null;

                    $extension = pathinfo($_FILES['custom_background']['name'])['extension'];
                    $filename = $user_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;
                    $folder_path = 'assets/files/site_users/backgrounds/'.$currentMonthYear.'/';

                    if (!file_exists($folder_path)) {
                        mkdir($folder_path, 0755, true);
                    }

                    if (files('upload', ['upload' => 'custom_background', 'folder' => 'site_users/backgrounds/'.$currentMonthYear, 'saveas' => $filename])['result']) {
                        files('resize_img', ['resize' => 'site_users/backgrounds/'.$currentMonthYear.'/'.$filename, 'width' => 1920, 'height' => 1080, 'crop' => false]);
                        $update_image_data['profile_bg_image'] = $folder_path.$filename;

                        if (Registry::load('settings')->cloud_storage !== 'disable') {
                            cloud_storage_module(['upload_file' => $update_image_data['profile_bg_image'], 'delete' => true]);
                        }
                    }
                }
            }

            if ($created_by_admin && isset($_FILES['cover_pic']['name']) && !empty($_FILES['cover_pic']['name'])) {
                if (isImage($_FILES['cover_pic']['tmp_name'])) {

                    $update_image_data['profile_cover_pic'] = 'assets/files/site_users/cover_pics/default.png';

                    $extension = pathinfo($_FILES['cover_pic']['name'])['extension'];
                    $filename = $user_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

                    $folder_path = 'assets/files/site_users/cover_pics/'.$currentMonthYear.'/';

                    if (!file_exists($folder_path)) {
                        mkdir($folder_path, 0755, true);
                    }


                    if (files('upload', ['upload' => 'cover_pic', 'folder' => 'site_users/cover_pics/'.$currentMonthYear, 'saveas' => $filename])['result']) {
                        $resize_image = true;
                        if (isset(Registry::load('settings')->image_moderation) && Registry::load('settings')->image_moderation !== 'disable') {
                            include_once('fns/image_moderation/load.php');

                            $image_location = 'assets/files/site_users/cover_pics/'.$currentMonthYear.'/'.$filename;
                            $image_moderation = moderate_image_content($image_location);

                            if (!$image_moderation['success']) {
                                if (file_exists($image_location)) {
                                    unlink($image_location);
                                }
                                $resize_image = false;
                            }
                        }

                        if ($resize_image) {
                            files('resize_img', ['resize' => 'site_users/cover_pics/'.$currentMonthYear.'/'.$filename, 'width' => 400, 'height' => 400, 'crop' => true]);

                            $update_image_data['profile_cover_pic'] = 'assets/files/site_users/cover_pics/'.$currentMonthYear.'/'.$filename;

                            if (Registry::load('settings')->cloud_storage !== 'disable') {
                                cloud_storage_module(['upload_file' => $update_image_data['profile_cover_pic'], 'delete' => true]);
                            }
                        }
                    }
                }
            }

            $upload_custom_avatar = false;

            if ($created_by_admin) {
                $upload_custom_avatar = true;
            } else if (!Registry::load('current_user')->logged_in && Registry::load('settings')->enable_photo_upload_on_signup !== 'no') {
                $upload_custom_avatar = true;
            }

            if ($upload_custom_avatar && isset($_FILES['custom_avatar']['name']) && !empty($_FILES['custom_avatar']['name'])) {
                if (isImage($_FILES['custom_avatar']['tmp_name'])) {

                    $update_image_data['profile_picture'] = 'assets/files/site_users/profile_pics/default.png';

                    $extension = pathinfo($_FILES['custom_avatar']['name'])['extension'];
                    $filename = $user_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

                    $folder_path = 'assets/files/site_users/profile_pics/'.$currentMonthYear.'/';

                    if (!file_exists($folder_path)) {
                        mkdir($folder_path, 0755, true);
                    }

                    if (files('upload', ['upload' => 'custom_avatar', 'folder' => 'site_users/profile_pics/'.$currentMonthYear, 'saveas' => $filename])['result']) {

                        $resize_image = true;
                        if (isset(Registry::load('settings')->image_moderation) && Registry::load('settings')->image_moderation !== 'disable') {
                            include_once('fns/image_moderation/load.php');

                            $image_location = 'assets/files/site_users/profile_pics/'.$currentMonthYear.'/'.$filename;
                            $image_moderation = moderate_image_content($image_location);

                            if (!$image_moderation['success']) {
                                if (file_exists($image_location)) {
                                    unlink($image_location);
                                }
                                $resize_image = false;
                            }
                        }

                        if ($resize_image) {
                            $update_image_data['profile_picture'] = 'assets/files/site_users/profile_pics/'.$currentMonthYear.'/'.$filename;
                            files('resize_img', ['resize' => 'site_users/profile_pics/'.$currentMonthYear.'/'.$filename, 'width' => 200, 'height' => 200, 'crop' => true]);

                            if (Registry::load('settings')->cloud_storage !== 'disable') {
                                cloud_storage_module(['upload_file' => $update_image_data['profile_picture'], 'delete' => true]);
                            }
                        }
                    }
                }
            } elseif ($created_by_admin && isset($data['avatar'])) {
                $data['avatar'] = 'assets/files/avatars/'.sanitize_filename($data['avatar']);

                if (file_exists($data['avatar'])) {
                    $update_image_data['profile_picture'] = 'assets/files/site_users/profile_pics/default.png';

                    $filename = 'assets/files/site_users/profile_pics/'.$currentMonthYear.'/';

                    if (!file_exists($filename)) {
                        mkdir($filename, 0755, true);
                    }

                    $filename .= $user_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.png';
                    $update_image_data['profile_picture'] = $filename;

                    files('copy', ['from' => $data['avatar'], 'to' => $filename, 'real_path' => true]);

                    if (Registry::load('settings')->cloud_storage !== 'disable') {
                        cloud_storage_module(['upload_file' => $filename, 'delete' => true]);
                    }
                }
            } elseif ($created_by_admin && isset($data['avatarURL'])) {

                $update_image_data['profile_picture'] = 'assets/files/site_users/profile_pics/default.png';
                $data['avatarURL'] = filter_var($data['avatarURL'], FILTER_SANITIZE_URL);

                if (!empty($data['avatarURL'])) {
                    $avatar_file_name = $user_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.png';


                    $folder_path = 'assets/files/site_users/profile_pics/'.$currentMonthYear.'/';
                    $avatar_file = $folder_path.$avatar_file_name;

                    if (!file_exists($folder_path)) {
                        mkdir($folder_path, 0755, true);
                    }

                    $curl_request = curl_init($data['avatarURL']);
                    $save_avatar = fopen($avatar_file, 'wb');
                    curl_setopt($curl_request, CURLOPT_FILE, $save_avatar);
                    curl_setopt($curl_request, CURLOPT_HEADER, 0);
                    curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl_request, CURLOPT_ENCODING, '');
                    curl_exec($curl_request);
                    curl_close($curl_request);
                    fclose($save_avatar);

                    if (file_exists($avatar_file)) {
                        $avatar_content_type = mime_content_type($avatar_file);
                        if (strpos($avatar_content_type, 'image/') !== false) {
                            files('resize_img', ['resize' => 'site_users/profile_pics/'.$currentMonthYear.'/'.$avatar_file_name, 'width' => 200, 'height' => 200, 'crop' => true]);

                            $update_image_data['profile_picture'] = $avatar_file;

                            if (Registry::load('settings')->cloud_storage !== 'disable') {
                                cloud_storage_module(['upload_file' => $avatar_file, 'delete' => true]);
                            }

                        } else {
                            unlink($avatar_file);
                        }
                    }
                }

            }

            if (!empty($update_image_data)) {
                DB::connect()->update("site_users", $update_image_data, ["user_id" => $user_id]);
            }

            foreach ($custom_fields as $custom_field) {
                $field_name = $custom_field['field_name'];
                $insert = false;

                if (isset($data[$field_name])) {
                    if ($custom_field['field_type'] === 'date') {
                        if (validate_date($data[$field_name], 'Y-m-d')) {
                            $insert = true;
                        }
                    } elseif ($custom_field['field_type'] === 'link') {
                        $data[$field_name] = filter_var($data[$field_name], FILTER_SANITIZE_URL);
                        if (!empty($data[$field_name]) && filter_var($data[$field_name], FILTER_VALIDATE_URL)) {
                            $insert = true;
                        }
                    } elseif ($custom_field['field_type'] === 'number') {
                        $data[$field_name] = filter_var($data[$field_name], FILTER_SANITIZE_NUMBER_INT);
                        if (!empty($data[$field_name])) {
                            $insert = true;
                        }
                    } elseif ($custom_field['field_type'] === 'dropdown') {
                        if (!empty($data[$field_name])) {
                            $dropdownoptions = $field_name.'_options';
                            if (isset(Registry::load('strings')->$dropdownoptions)) {
                                $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
                                $find_index = $data[$field_name];
                                if (isset($field_options->$find_index)) {
                                    $insert = true;
                                }
                            }
                        }
                    } else {
                        $data[$field_name] = htmlspecialchars(trim($data[$field_name]), ENT_QUOTES, 'UTF-8');
                        $insert = true;
                    }

                    if ($insert) {
                        $insert_data = ['field_value' => $data[$field_name], 'updated_on' => Registry::load('current_user')->time_stamp];
                        $insert_data["field_id"] = $custom_field['field_id'];
                        $insert_data["user_id"] = $user_id;
                        DB::connect()->insert("custom_fields_values", $insert_data);
                    }
                }
            }

            if ($send_otp && isset($data['phone_number']) && isset($one_time_pin)) {
                include 'fns/sms_gateway/load.php';
                $otp_message = Registry::load('strings')->registration_otp_message.' '.$one_time_pin;
                $sms_data = ['message' => $otp_message, 'phone_number' => $data['phone_number']];
                $sms_response = send_sms($sms_data);
            }

            if ($require_email_verification) {
                include_once('fns/mailer/load.php');

                $send_welcome_email = false;

                $verification_link = Registry::load('config')->site_url.'entry/verify_email_address/'.$user_id.'/'.$verification_code;

                $mail = array();
                $mail['email_addresses'] = $data['email_address'];
                $mail['category'] = 'verification';
                $mail['user_id'] = $user_id;
                $mail['parameters'] = ['link' => $verification_link];
                $mail['send_now'] = true;
                mailer('compose', $mail);
            } elseif ($email_login_link) {
                $send_welcome_email = false;
                $current_timestamp = Registry::load('current_user')->time_stamp;
                $access_token = random_string(['length' => 10]);
                $update = ['access_token' => $access_token, 'token_generated_on' => $current_timestamp];
                $where = ['site_users.user_id' => $user_id];
                DB::connect()->update('site_users', $update, $where);

                include_once('fns/mailer/load.php');

                $login_link = Registry::load('config')->site_url.'entry/access_token/'.$user_id.'/'.$access_token;

                $mail = array();
                $mail['email_addresses'] = $data['email_address'];
                $mail['category'] = 'login_link';
                $mail['user_id'] = $user_id;
                $mail['parameters'] = ['link' => $login_link];
                $mail['send_now'] = true;
                mailer('compose', $mail);
            }

            if ($send_welcome_email) {

                include_once('fns/mailer/load.php');

                $mail = array();
                $mail['email_addresses'] = $data['email_address'];
                $mail['category'] = 'welcome_user';
                $mail['user_id'] = $user_id;
                $mail['parameters'] = ['link' => Registry::load('config')->site_url];
                $mail['send_now'] = true;
                mailer('compose', $mail);
            }



            if ($notify_admins_pending_approval) {
                if (isset(Registry::load('settings')->send_email_notification->on_new_user_pending_approval)) {
                    include_once('fns/mailer/load.php');

                    $site_admins_join = ['[>]site_roles' => ["site_users.site_role_id" => "site_role_id"]];
                    $site_admins = DB::connect()->select('site_users', $site_admins_join,
                        ['site_users.email_address'],
                        ['site_roles.site_role_attribute' => 'administrators', 'LIMIT' => 10]);

                    if (count($site_admins) > 0) {
                        $site_admins = array_column($site_admins, 'email_address');
                        $user_details = '<br/><br/>';
                        $user_details .= Registry::load('strings')->username.' : '.$data['username'].'<br/>';
                        $user_details .= Registry::load('strings')->email_address.' : '.$data['email_address'];
                        $mail = array();
                        $mail['email_addresses'] = $site_admins;
                        $mail['category'] = 'user_pending_approval';
                        $mail['user_id'] = $user_id;
                        $mail['parameters'] = ['link' => Registry::load('config')->site_url, 'append_content' => $user_details];
                        $mail['send_now'] = true;
                        mailer('compose', $mail);
                    }
                }
            }


            $default_group_role_id = DB::connect()->select('group_roles', ['group_roles.group_role_id'], ['group_roles.group_role_attribute' => 'default_group_role', 'LIMIT' => 1]);

            if (isset($default_group_role_id[0])) {
                $default_group_role_id = $default_group_role_id[0]['group_role_id'];
                $auto_join_groups = DB::connect()->select('groups', ['groups.group_id'], ['groups.auto_join_group' => 1]);
                $join_groups = $group_join_sys_message = array();

                $system_message = [
                    'message' => 'joined_group',
                    'user_id' => $user_id
                ];

                $system_message = json_encode($system_message);

                foreach ($auto_join_groups as $auto_join_group) {
                    $join_group_id = $auto_join_group['group_id'];
                    $last_read_message_id = 0;

                    $last_group_message_id = DB::connect()->select(
                        'group_messages',
                        ['group_messages.group_message_id'],
                        [
                            'group_messages.group_id' => $join_group_id,
                            "ORDER" => ["group_messages.group_message_id" => "DESC"],
                            'LIMIT' => 1
                        ]
                    );

                    if (isset($last_group_message_id[0])) {
                        $last_read_message_id = $last_group_message_id[0]['group_message_id'];
                    }


                    $join_groups[] = [
                        'group_id' => $join_group_id,
                        'user_id' => $user_id,
                        'last_read_message_id' => $last_read_message_id,
                        'group_role_id' => $default_group_role_id,
                        "previous_group_role_id" => $default_group_role_id,
                        "joined_on" => Registry::load('current_user')->time_stamp,
                        "updated_on" => Registry::load('current_user')->time_stamp,
                    ];

                    $group_join_sys_message[] = [
                        "system_message" => 1,
                        "original_message" => 'system_message',
                        "filtered_message" => $system_message,
                        "group_id" => $join_group_id,
                        "user_id" => $user_id,
                        "created_on" => Registry::load('current_user')->time_stamp,
                        "updated_on" => Registry::load('current_user')->time_stamp,
                    ];

                    $total_members = DB::connect()->count("group_members", ["group_id" => $join_group_id]);
                    $total_members = (int)$total_members+1;
                    DB::connect()->update("groups", ["total_members" => $total_members], ["group_id" => $join_group_id]);
                }

                if (!empty($join_groups)) {
                    DB::connect()->insert('group_members', $join_groups);

                    $add_system_message = false;

                    if ($add_system_message) {
                        if (isset(Registry::load('settings')->system_messages_groups->on_join_group_chat)) {
                            DB::connect()->insert('group_messages', $group_join_sys_message);
                        }
                    }
                }
            }


            $result = array();
            $result['success'] = true;

            if (!Registry::load('current_user')->logged_in || isset($private_data['auto_login']) && $private_data['auto_login']) {
                if (isset($data['signup_page']) || isset($private_data['auto_login']) && $private_data['auto_login']) {
                    $login_session = [
                        'add' => 'login_session',
                        'user' => $data['username'],
                        'return' => true
                    ];

                    if (isset($data['redirect'])) {
                        $login_session['redirect'] = $data['redirect'];
                    }
                    $result = add($login_session, ['force_request' => true]);
                    $result['reset_form'] = true;
                } else {
                    $result['todo'] = 'refresh';
                }
            } else {
                if ((int)$user_id === (int)Registry::load('current_user')->id) {
                    $result['todo'] = 'refresh';
                } else {
                    $result['todo'] = 'reload';
                    $result['reload'] = 'site_users';
                }
            }
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }
    }
}