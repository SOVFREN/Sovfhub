<?php

if (!empty(Registry::load('current_user')->site_role_attribute)) {
    if (Registry::load('current_user')->site_role_attribute === 'guest_users') {
        if (Registry::load('settings')->allow_guest_users_create_accounts === 'yes') {
            include 'fns/filters/load.php';

            $required_fields = ['email_address', 'password', 'confirm_password'];
            $user_id = Registry::load('current_user')->id;

            $columns = $where = $join = null;
            $columns = [
                'custom_fields.field_id', 'custom_fields.string_constant(field_name)', 'custom_fields.field_type',
                'custom_fields.required', 'custom_fields_values.field_value', 'custom_fields.editable_only_once'
            ];
            $join["[>]custom_fields_values"] = ["custom_fields.field_id" => "field_id", "AND" => ["user_id" => $user_id]];
            $where['AND'] = ['custom_fields.field_category' => 'profile', 'custom_fields.disabled' => 0];
            $where['AND']['custom_fields.show_on_signup'] = 1;

            $where["ORDER"] = ["custom_fields.field_id" => "ASC"];
            $custom_fields = DB::connect()->select('custom_fields', $join, $columns, $where);

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


            $noerror = true;

            $result = array();
            $result['success'] = false;
            $result['error_message'] = Registry::load('strings')->invalid_value;
            $result['error_key'] = 'invalid_value';
            $result['error_variables'] = [];

            if (isset($data['email_address']) && !filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
                $data['email_address'] = null;
            }

            foreach ($required_fields as $required_field) {
                if (!isset($data[$required_field]) || empty($data[$required_field])) {
                    $result['error_variables'][] = [$required_field];
                    $noerror = false;
                }
            }

            if (isset($data['email_address']) && !empty($data['email_address'])) {
                $data['email_address'] = htmlspecialchars(trim($data['email_address']), ENT_QUOTES, 'UTF-8');
                $email_exists = DB::connect()->select('site_users', 'site_users.user_id', ['AND' => ['site_users.email_address' => $data['email_address']], 'site_users.user_id[!]' => $user_id]);

                if (isset($email_exists[0])) {
                    $result['error_variables'] = ['email_address'];
                    $result['error_message'] = Registry::load('strings')->email_exists;
                    $result['error_key'] = 'email_exists';
                    $noerror = false;
                } else if (Registry::load('settings')->email_validator === 'enable' || Registry::load('settings')->email_validator === 'strict_mode') {
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

            if (isset($data['password']) && !empty($data['password'])) {
                if (!isset($data['confirm_password']) || isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
                    $result['error_variables'] = ['password', 'confirm_password'];
                    $result['error_message'] = Registry::load('strings')->password_doesnt_match;
                    $result['error_key'] = 'password_doesnt_match';
                    $noerror = false;
                }
            }

            if ($noerror) {

                $result = array();
                $result['success'] = true;
                $result['todo'] = 'refresh';

                $update_data = [
                    "updated_on" => Registry::load('current_user')->time_stamp
                ];

                if (isset($data['password']) && !empty($data['password'])) {
                    $update_data["encrypt_type"] = 'php_password_hash';
                    $update_data["salt"] = '';
                    $update_data["password"] = password_hash($data['password'], PASSWORD_BCRYPT);
                }

                if (Registry::load('settings')->user_email_verification === 'enable') {
                    $update_data["unverified_email_address"] = $data['email_address'];
                    $verification_code = random_string(['length' => 10]);
                    $update_data["verification_code"] = $verification_code;
                } else {
                    $site_role_id = 1;
                   
                    if (isset(Registry::load('site_role_attributes')->default_site_role)) {
                        $site_role_id = Registry::load('site_role_attributes')->default_site_role;
                    }

                    $update_data["unverified_email_address"] = null;
                    $update_data["site_role_id"] = $site_role_id;
                    $update_data["email_address"] = $data['email_address'];
                }

                DB::connect()->update("site_users", $update_data, ["user_id" => $user_id]);

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

                if (Registry::load('settings')->user_email_verification === 'enable') {
                    include('fns/mailer/load.php');

                    $verification_link = Registry::load('config')->site_url.'entry/verify_email_address/'.$user_id.'/'.$verification_code;

                    $mail = array();
                    $mail['email_addresses'] = $data['email_address'];
                    $mail['category'] = 'verification';
                    $mail['user_id'] = $user_id;
                    $mail['parameters'] = ['link' => $verification_link];
                    $mail['send_now'] = true;
                    mailer('compose', $mail);
                    $result['alert_message'] = Registry::load('strings')->confirm_email_address;
                }
            }

        }

    }
}

?>