<?php

include 'fns/filters/load.php';
include 'fns/files/load.php';

include_once('fns/filters/profanity.php');
include_once('fns/cloud_storage/load.php');
use Snipe\BanBuilder\CensorWords;


if ($force_request || role(['permissions' => ['site_users' => 'edit_users', 'profile' => 'edit_profile'], 'condition' => 'OR'])) {
    $noerror = true;
    $disabled = 0;
    $strict_mode = true;
    $required_fields = [];
    $user_id = Registry::load('current_user')->id;
    $validate_custom_fields = true;
    $require_email_verification = true;
    $updated_email_address = false;
    $currentMonthYear = date('mY');
    $update_image_data = array();
    $own_profile = false;

    $result = array();
    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    if ($force_request || role(['permissions' => ['site_users' => 'edit_users']])) {
        $require_email_verification = false;

        if (isset($data['user_id'])) {
            $data["user_id"] = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($data['user_id'])) {
                $user_id = $data["user_id"];
            }
        }
    }

    if ((int)$user_id === (int)Registry::load('current_user')->id) {
        $own_profile = true;
    }

    if (!empty($user_id)) {
        if (isset($data['delete_account']) && $data['delete_account'] === 'yes') {
            if (role(['permissions' => ['profile' => 'delete_account']])) {
                include('fns/remove/load.php');
                remove(['remove' => 'site_users', 'user_id' => $user_id, 'return' => true], ['force_request' => true]);
                $result = array();
                $result['success'] = true;
                $result['todo'] = 'refresh';
                return;
            }
        }
    }

    if ($force_request) {
        if (isset($data['user'])) {
            $columns = $join = $where = null;

            $columns = ['site_users.user_id'];
            $where["OR"] = ["site_users.username" => $data['user'], "site_users.email_address" => $data['user']];
            $where["LIMIT"] = 1;

            $site_user = DB::connect()->select('site_users', $columns, $where);

            if (isset($site_user[0])) {
                $user_id = $site_user[0]['user_id'];
            } else {
                $user_id = 0;
            }
        }
    }

    if ($force_request || role(['permissions' => ['profile' => 'change_full_name']])) {
        $required_fields[] = 'full_name';
    }

    if ($force_request || role(['permissions' => ['profile' => 'change_username']])) {
        $required_fields[] = 'username';
    }

    if ($force_request || role(['permissions' => ['profile' => 'change_email_address']])) {
        $required_fields[] = 'email_address';
    }

    $columns = $where = null;
    $columns = [
        'custom_fields.field_id', 'custom_fields.string_constant(field_name)', 'custom_fields.field_type',
        'custom_fields.required', 'custom_fields_values.field_value', 'custom_fields.editable_only_once'
    ];
    $join["[>]custom_fields_values"] = ["custom_fields.field_id" => "field_id", "AND" => ["user_id" => $user_id]];
    $where['AND'] = ['custom_fields.field_category' => 'profile', 'custom_fields.disabled' => 0];
    $where["ORDER"] = ["custom_fields.field_id" => "ASC"];
    $custom_fields = DB::connect()->select('custom_fields', $join, $columns, $where);

    $columns = $where = $join = null;
    $columns = [
        'site_users.display_name', 'site_users.username', 'site_users.email_address', 'site_users.site_role_id',
        'site_users_settings.time_zone', 'site_users_settings.notification_tone', 'site_users_settings.disable_private_messages',
        'site_users.phone_number', 'site_users.profile_picture', 'site_users.profile_cover_pic', 'site_users.profile_bg_image',
        'site_roles.role_hierarchy'
    ];

    $join["[>]site_users_settings"] = ["site_users.user_id" => "user_id"];
    $join["[>]site_roles"] = ["site_users.site_role_id" => "site_role_id"];
    $where['site_users.user_id'] = $user_id;

    $user = DB::connect()->select('site_users', $join, $columns, $where);

    if (isset($user[0])) {
        $user = $user[0];
    } else {
        $result = array();
        $result['success'] = false;
        $result['error_message'] = Registry::load('strings')->account_not_found;
        $result['error_key'] = 'account_not_found';
        $result['error_variables'] = [];
        return;
    }

    if (Registry::load('settings')->non_latin_usernames === 'enable') {
        $strict_mode = false;
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


    if (isset($data['email_address']) && !filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
        $data['email_address'] = null;
    }

    if ($force_request || role(['permissions' => ['site_users' => 'edit_users']])) {
        $validate_custom_fields = false;
    }

    if ($validate_custom_fields) {
        foreach ($custom_fields as $custom_field) {
            if ((int)$custom_field['required'] === 1) {
                if (!empty($custom_field['editable_only_once']) && isset($custom_field['field_value'])) {
                    continue;
                } else {
                    $required_fields[] = $custom_field['field_name'];
                }
            }
        }
    }

    if (!$force_request) {
        foreach ($required_fields as $required_field) {
            if (!isset($data[$required_field]) || empty($data[$required_field])) {
                $result['error_variables'][] = [$required_field];
                $noerror = false;
            }
        }
    }

    if (!$force_request) {

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

    if (!$force_request && isset($data['password']) && !empty($data['password'])) {
        if (!isset($data['confirm_password']) || isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
            $result['error_variables'] = ['password', 'confirm_password'];
            $result['error_message'] = Registry::load('strings')->password_doesnt_match;
            $result['error_key'] = 'password_doesnt_match';
            $noerror = false;
        }
    }

    if (isset($data['email_address']) && !empty($data['email_address'])) {
        if ($force_request || role(['permissions' => ['profile' => 'change_email_address']])) {
            $data['email_address'] = htmlspecialchars(trim($data['email_address']), ENT_QUOTES, 'UTF-8');
            $email_exists = DB::connect()->select('site_users', 'site_users.user_id', ['AND' => ['site_users.email_address' => $data['email_address']], 'site_users.user_id[!]' => $user_id]);

            if (isset($email_exists[0])) {
                $result['error_variables'] = ['email_address'];
                $result['error_message'] = Registry::load('strings')->email_exists;
                $result['error_key'] = 'email_exists';
                $noerror = false;
            }
        }

        if (Registry::load('settings')->email_validator === 'enable' || Registry::load('settings')->email_validator === 'strict_mode') {
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

    if (isset($data['username']) && !empty($data['username']) && $data['username'] !== $user['username']) {
        if ($force_request || role(['permissions' => ['profile' => 'change_username']])) {
            if (username_exists($data['username'])) {
                $result['error_variables'] = ['username'];
                $result['error_message'] = Registry::load('strings')->username_exists;
                $result['error_key'] = 'username_exists';
                $noerror = false;
            }
        }
    }

    if (!$force_request && Registry::load('current_user')->site_role_attribute !== 'administrators') {
        if (!$own_profile && (int)$user['role_hierarchy'] >= (int)Registry::load('current_user')->role_hierarchy) {
            $result['error_message'] = Registry::load('strings')->permission_denied;
            $result['error_key'] = 'permission_denied';
            return;
        }
    }

    if (role(['permissions' => ['site_users' => 'edit_users']]) && isset($data['phone_number']) && !empty($data['phone_number'])) {
        $data['phone_number'] = sanitize_phone_number($data['phone_number']);
        if (empty($data['phone_number'])) {
            $result['error_variables'] = ['phone_number'];
            $result['error_message'] = Registry::load('strings')->invalid_phone_number;
            $result['error_key'] = 'invalid_phone_number';
            $noerror = false;
        } else if (!empty($data['phone_number']) && $data['phone_number'] !== $user['phone_number']) {
            if (phone_number_exists($data['phone_number'])) {
                $result['error_variables'] = ['phone_number'];
                $result['error_message'] = Registry::load('strings')->phone_number_exists;
                $result['error_key'] = 'phone_number_exists';
                $noerror = false;
            }
        }
    }
    
    if ($noerror && !empty($user_id)) {
        $site_role = $user['site_role_id'];

        if (isset($data['site_role']) && !empty($data['site_role'])) {
            if ($force_request || role(['permissions' => ['site_users' => 'edit_users']])) {
                $check_site_role_condition = ["site_roles.site_role_id" => $data['site_role']];
                
                if (!$force_request && Registry::load('current_user')->site_role_attribute !== 'administrators') {
                    $check_site_role_condition['site_roles.role_hierarchy[<]'] = Registry::load('current_user')->site_role;
                }

                $check_site_role = DB::connect()->select('site_roles', ['site_roles.site_role_id'], $check_site_role_condition);
                if (isset($check_site_role[0])) {
                    $site_role = $data['site_role'];
                }
            }
        }

        if ($force_request && isset($data['site_role_attribute']) && !empty($data['site_role_attribute'])) {
            $check_site_role = DB::connect()->select('site_roles', ['site_roles.site_role_id'], ["site_roles.site_role_attribute" => $data['site_role_attribute']]);
            if (isset($check_site_role[0])) {
                $site_role = $check_site_role[0]['site_role_id'];
            }
        }

        $update_data = [
            "site_role_id" => $site_role,
            "updated_on" => Registry::load('current_user')->time_stamp,
        ];

        if (isset($data['disabled']) && $data['disabled'] === 'yes') {
            $disabled = 1;
        }


        if (isset($data['full_name']) && !empty($data['full_name'])) {
            if ($force_request || role(['permissions' => ['profile' => 'change_full_name']])) {
                if (isset($data['full_name']) && !empty($data['full_name'])) {
                    $update_data["display_name"] = $data['full_name'];
                }
            }
        }


        if (isset($data['username']) && !empty($data['username'])) {
            if ($force_request || role(['permissions' => ['profile' => 'change_username']])) {
                if ($data['username'] !== $user['username']) {
                    $update_data["username"] = $data['username'];
                }
            }
        }

        if (isset($data['email_address']) && !empty($data['email_address'])) {
            if ($force_request || role(['permissions' => ['profile' => 'change_email_address']])) {
                if ($data['email_address'] !== $user['email_address']) {
                    if (!$force_request && Registry::load('settings')->user_email_verification === 'enable' && $require_email_verification) {
                        $updated_email_address = true;
                        $update_data["unverified_email_address"] = $data['email_address'];

                        $verification_code = random_string(['length' => 10]);
                        $update_data["verification_code"] = $verification_code;
                    } else {
                        $update_data["unverified_email_address"] = null;
                        $update_data["email_address"] = $data['email_address'];
                    }
                }
            }
        }

        if ($force_request || role(['permissions' => ['profile' => 'change_password']])) {
            if (isset($data['password']) && !empty($data['password'])) {
                $update_data["encrypt_type"] = 'php_password_hash';
                $update_data["salt"] = '';
                $update_data["password"] = password_hash($data['password'], PASSWORD_BCRYPT);

                $destroy_other_login_sessions = ["AND" => ["user_id" => $user_id, "status" => '1', "login_session_id[!]" => Registry::load('current_user')->login_session_id]];
                DB::connect()->update("login_sessions", ['status' => 2], $destroy_other_login_sessions);
            }
        }

        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            if ($force_request || role(['permissions' => ['site_users' => 'edit_users']])) {
                $update_data["phone_number"] = $data['phone_number'];
            }
        }

        if (isset($data['approve_phone_number']) && $data['approve_phone_number'] === 'yes') {
            if ($force_request || role(['permissions' => ['site_users' => 'edit_users']])) {
                $update_data["phone_verified"] = 1;
            }
        }

        DB::connect()->update("site_users", $update_data, ["user_id" => $user_id]);

        if (!DB::connect()->error) {
            $update_data = ["updated_on" => Registry::load('current_user')->time_stamp];

            if ($force_request || role(['permissions' => ['profile' => 'disable_private_messages']])) {
                $disable_private_messages = 0;

                if (isset($data['disable_private_messages']) && $data['disable_private_messages'] === 'yes') {
                    $disable_private_messages = 1;
                }

                $update_data["disable_private_messages"] = $disable_private_messages;
            }

            if ($force_request || role(['permissions' => ['profile' => 'deactivate_account']])) {
                if (isset($data['deactivate']) && $data['deactivate'] === 'yes') {
                    if ((int)$user_id === (int)Registry::load('current_user')->id) {
                        include('fns/remove/load.php');
                        remove(['remove' => 'login_session', 'return' => true], ['remove_all_user_sessions' => true]);
                    }

                    $update_data["deactivated"] = 1;
                } elseif (isset($data['deactivate']) && $data['deactivate'] === 'no') {
                    $update_data["deactivated"] = 0;
                }
            }

            $check_array = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

            if ($force_request || role(['permissions' => ['profile' => 'set_timezone']])) {
                if (isset($data['timezone']) && $data['timezone'] === 'Default' || isset($data['timezone']) && empty($data['timezone'])) {
                    $update_data["time_zone"] = 'default';
                } elseif (isset($data['timezone']) && in_array($data['timezone'], $check_array)) {
                    $update_data["time_zone"] = $data['timezone'];
                }
            }

            $check_array = glob('assets/files/sound_notifications/*');

            if (!isset($data['notification_tone'])) {
                $data['notification_tone'] = '';
            }

            if (empty($data['notification_tone']) || in_array($data['notification_tone'], $check_array)) {
                $update_data["notification_tone"] = $data['notification_tone'];
            }

            DB::connect()->update("site_users_settings", $update_data, ["user_id" => $user_id]);


            if ($force_request || role(['permissions' => ['profile' => 'set_custom_background']])) {
                if (isset($data['remove_custom_bg']) && $data['remove_custom_bg'] === 'yes') {
                    $update_image_data['profile_bg_image'] = null;

                    if (!empty($user['profile_bg_image'])) {

                        $delete_image = false;

                        if (!empty($user['profile_bg_image']) && basename($user['profile_bg_image']) !== 'default.png') {
                            $delete_image = true;
                        }

                        if ($delete_image && file_exists($user['profile_bg_image'])) {
                            unlink($user['profile_bg_image']);
                        }

                        if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                            cloud_storage_module(['delete_file' => $user['profile_bg_image']]);
                        }
                    }
                } elseif (isset($_FILES['custom_background']['name']) && !empty($_FILES['custom_background']['name'])) {
                    if (isImage($_FILES['custom_background']['tmp_name'])) {

                        $update_image_data['profile_bg_image'] = null;

                        if (!empty($user['profile_bg_image'])) {

                            $delete_image = false;

                            if (!empty($user['profile_bg_image']) && basename($user['profile_bg_image']) !== 'default.png') {
                                $delete_image = true;
                            }

                            if ($delete_image && file_exists($user['profile_bg_image'])) {
                                unlink($user['profile_bg_image']);
                            }

                            if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                                cloud_storage_module(['delete_file' => $user['profile_bg_image']]);
                            }
                        }

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
            }

            if ($force_request || role(['permissions' => ['profile' => 'set_cover_pic']])) {
                if (isset($data['remove_cover_pic']) && $data['remove_cover_pic'] === 'yes') {

                    $update_image_data['profile_cover_pic'] = 'assets/files/site_users/cover_pics/default.png';

                    if (!empty($user['profile_cover_pic'])) {

                        $delete_image = false;

                        if (!empty($user['profile_cover_pic']) && basename($user['profile_cover_pic']) !== 'default.png') {
                            $delete_image = true;
                        }

                        if ($delete_image && file_exists($user['profile_cover_pic'])) {
                            unlink($user['profile_cover_pic']);
                        }

                        if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                            cloud_storage_module(['delete_file' => $user['profile_cover_pic']]);
                        }
                    }
                } elseif (isset($_FILES['cover_pic']['name']) && !empty($_FILES['cover_pic']['name'])) {
                    if (isImage($_FILES['cover_pic']['tmp_name'])) {

                        $update_image_data['profile_cover_pic'] = 'assets/files/site_users/cover_pics/default.png';
                        $delete_image = false;

                        if (!empty($user['profile_cover_pic']) && basename($user['profile_cover_pic']) !== 'default.png') {
                            $delete_image = true;
                        }

                        if ($delete_image && file_exists($user['profile_cover_pic'])) {
                            unlink($user['profile_cover_pic']);
                        }

                        if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                            cloud_storage_module(['delete_file' => $user['profile_cover_pic']]);
                        }

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
            }

            if (isset($_FILES['custom_avatar']['name']) && !empty($_FILES['custom_avatar']['name']) && role(['permissions' => ['profile' => 'upload_custom_avatar']])) {
                if (isImage($_FILES['custom_avatar']['tmp_name'])) {
                    $extension = pathinfo($_FILES['custom_avatar']['name'])['extension'];
                    $update_image_data['profile_picture'] = 'assets/files/site_users/profile_pics/default.png';
                    $filename = $user_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

                    $delete_image = false;

                    if (!empty($user['profile_picture']) && basename($user['profile_picture']) !== 'default.png') {
                        $delete_image = true;
                    }

                    if ($delete_image && file_exists($user['profile_picture'])) {
                        unlink($user['profile_picture']);
                    }

                    if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                        cloud_storage_module(['delete_file' => $user['profile_picture']]);
                    }

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
            } elseif (isset($data['avatar']) && !empty($data['avatar']) && role(['permissions' => ['profile' => 'change_avatar']])) {
                $data['avatar'] = 'assets/files/avatars/'.sanitize_filename($data['avatar']);

                if (file_exists($data['avatar'])) {
                    $update_image_data['profile_picture'] = 'assets/files/site_users/profile_pics/default.png';
                    $filename = 'assets/files/site_users/profile_pics/'.$currentMonthYear.'/';

                    if (!file_exists($filename)) {
                        mkdir($filename, 0755, true);
                    }

                    $filename .= $user_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.png';
                    $update_image_data['profile_picture'] = $filename;

                    $delete_image = false;

                    if (!empty($user['profile_picture']) && basename($user['profile_picture']) !== 'default.png') {
                        $delete_image = true;
                    }

                    if ($delete_image && file_exists($user['profile_picture'])) {
                        unlink($user['profile_picture']);
                    }

                    if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                        cloud_storage_module(['delete_file' => $user['profile_picture']]);
                    }

                    files('copy', ['from' => $data['avatar'], 'to' => $filename, 'real_path' => true]);

                    if (Registry::load('settings')->cloud_storage !== 'disable') {
                        cloud_storage_module(['upload_file' => $filename, 'delete' => true]);
                    }
                }
            } elseif ($force_request && isset($data['avatarURL'])) {

                $update_image_data['profile_picture'] = 'assets/files/site_users/profile_pics/default.png';

                $delete_image = false;

                if (!empty($user['profile_picture']) && basename($user['profile_picture']) !== 'default.png') {
                    $delete_image = true;
                }

                if ($delete_image && file_exists($user['profile_picture'])) {
                    unlink($user['profile_picture']);
                }

                if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                    cloud_storage_module(['delete_file' => $user['profile_picture']]);
                }

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
                $update = false;

                if (isset($data[$field_name])) {
                    if ($custom_field['field_type'] === 'date') {
                        if (validate_date($data[$field_name], 'Y-m-d')) {
                            $update = true;
                        }
                    } elseif ($custom_field['field_type'] === 'link') {
                        $data[$field_name] = filter_var($data[$field_name], FILTER_SANITIZE_URL);
                        if (!empty($data[$field_name]) && filter_var($data[$field_name], FILTER_VALIDATE_URL)) {
                            $update = true;
                        }
                    } elseif ($custom_field['field_type'] === 'number') {
                        $data[$field_name] = filter_var($data[$field_name], FILTER_SANITIZE_NUMBER_INT);
                        if (!empty($data[$field_name])) {
                            $update = true;
                        }
                    } elseif ($custom_field['field_type'] === 'dropdown') {
                        if (!empty($data[$field_name])) {
                            $dropdownoptions = $field_name.'_options';
                            if (isset(Registry::load('strings')->$dropdownoptions)) {
                                $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
                                $find_index = $data[$field_name];
                                if (isset($field_options->$find_index)) {
                                    $update = true;
                                }
                            }
                        }
                    } else {
                        $data[$field_name] = htmlspecialchars(trim($data[$field_name]), ENT_QUOTES, 'UTF-8');
                        $update = true;
                    }

                    if ($update) {
                        if (isset($custom_field['field_value'])) {
                            if (empty($custom_field['editable_only_once']) || empty($custom_field['field_value']) || role(['permissions' => ['site_users' => 'edit_users']])) {
                                $update_data = ['field_value' => $data[$field_name], 'updated_on' => Registry::load('current_user')->time_stamp];
                                $where = ['AND' => ["field_id" => $custom_field['field_id'], "user_id" => $user_id]];
                                DB::connect()->update("custom_fields_values", $update_data, $where);
                            }
                        } else {
                            $insert_data = ['field_value' => $data[$field_name], 'updated_on' => Registry::load('current_user')->time_stamp];
                            $insert_data["field_id"] = $custom_field['field_id'];
                            $insert_data["user_id"] = $user_id;
                            DB::connect()->insert("custom_fields_values", $insert_data);
                        }
                    }
                }
            }


            if ($require_email_verification && isset($verification_code)) {
                include('fns/mailer/load.php');

                $verification_link = Registry::load('config')->site_url.'entry/verify_email_address/'.$user_id.'/'.$verification_code;

                $mail = array();
                $mail['email_addresses'] = $data['email_address'];
                $mail['category'] = 'verification';
                $mail['user_id'] = $user_id;
                $mail['parameters'] = ['link' => $verification_link];
                $mail['send_now'] = true;
                mailer('compose', $mail);
            }

            $result = array();
            $result['success'] = true;

            if ((int)$user_id === (int)Registry::load('current_user')->id) {
                $result['todo'] = 'refresh';

                if ($updated_email_address) {
                    $result['alert_message'] = Registry::load('strings')->confirm_email_address;
                }
            } else {
                $result['todo'] = 'reload';
                $result['reload'] = 'site_users';
            }
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }
    }
}