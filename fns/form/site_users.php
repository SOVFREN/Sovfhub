<?php

$form = array();
$user_id = 0;
$todo = 'update';
$edit_users = false;

if (role(['permissions' => ['site_users' => 'edit_users']])) {
    $edit_users = true;
}

if (role(['permissions' => ['site_users' => ['create_user', 'edit_users'], 'profile' => 'edit_profile'], 'condition' => 'OR'])) {


    if (isset($load['user_id']) && $edit_users) {
        $load["user_id"] = filter_var($load["user_id"], FILTER_SANITIZE_NUMBER_INT);
        if (!empty($load['user_id'])) {
            $todo = 'update';
            $user_id = $load["user_id"];
        } else {
            $user_id = Registry::load('current_user')->id;
        }
    } else if (role(['permissions' => ['site_users' => 'create_user']])) {
        $todo = 'add';
    } else if (role(['permissions' => ['profile' => 'edit_profile']])) {
        $user_id = Registry::load('current_user')->id;
        $todo = 'update';
    }

    $check_site_role_condition = ['site_roles.disabled' => 0];

    if (Registry::load('current_user')->site_role_attribute !== 'administrators') {
        $check_site_role_condition['site_roles.role_hierarchy[<]'] = Registry::load('current_user')->role_hierarchy;
    }

    $site_roles = DB::connect()->select('site_roles', ['site_roles.site_role_id', 'site_roles.string_constant'], $check_site_role_condition);
    $site_roles = array_column($site_roles, 'string_constant', 'site_role_id');
    array_walk($site_roles, function(&$value, $key) {
        $value = Registry::load('strings')->$value;
    });


    $columns = $where = null;
    $columns = [
        'custom_fields.string_constant(field_name)', 'custom_fields.field_type', 'custom_fields.required',
        'custom_fields.editable_only_once', 'custom_fields_values.field_value'
    ];
    $join["[>]custom_fields_values"] = ["custom_fields.field_id" => "field_id", "AND" => ["user_id" => $user_id]];
    $where['AND'] = ['custom_fields.field_category' => 'profile', 'custom_fields.disabled' => 0];
    $where["ORDER"] = ["custom_fields.field_id" => "ASC"];
    $custom_fields = DB::connect()->select('custom_fields', $join, $columns, $where);


    $tones = array();
    $sound_notifications = glob('assets/files/sound_notifications/*');
    foreach ($sound_notifications as $sound_notification) {
        $sound_title = str_replace('-', ' ', $sound_notification);
        $tones[$sound_notification] = ucwords(basename($sound_title, '.mp3'));
    }

    if (!empty($user_id)) {
        $form['loaded'] = new stdClass();
        $form['loaded']->title = Registry::load('strings')->edit_profile;
        $form['loaded']->button = Registry::load('strings')->update;
    } else {
        $form['loaded'] = new stdClass();
        $form['loaded']->title = Registry::load('strings')->create_user;
        $form['loaded']->button = Registry::load('strings')->create;
    }

    $form['fields'] = new stdClass();

    $form['fields']->$todo = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "site_users"
    ];

    if (empty($user_id) || role(['permissions' => ['profile' => 'change_full_name']])) {
        $form['fields']->full_name = [
            "title" => Registry::load('strings')->full_name, "tag" => 'input', "type" => 'text',
            "class" => 'field', "placeholder" => Registry::load('strings')->full_name,
        ];
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'change_username']])) {
        $form['fields']->username = [
            "title" => Registry::load('strings')->username, "tag" => 'input', "type" => 'text',
            "class" => 'field', "placeholder" => Registry::load('strings')->username,
        ];
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'change_email_address']])) {
        $form['fields']->email_address = [
            "title" => Registry::load('strings')->email_address, "tag" => 'input', "type" => 'email', "class" => 'field',
            "placeholder" => Registry::load('strings')->email_address,
        ];

        if (!empty($user_id)) {
            $form['fields']->unverified_email_address = [
                "title" => Registry::load('strings')->unverified_email_address, "tag" => 'input', "type" => 'text',
                "class" => 'field d-none', "placeholder" => Registry::load('strings')->unverified_email_address,
                "attributes" => ["disabled" => "disabled"]
            ];
        }
    }

    $form['fields']->phone_number = [
        "title" => Registry::load('strings')->phone_number, "tag" => 'input', "type" => 'text',
        "class" => 'field', "placeholder" => Registry::load('strings')->phone_number,
    ];

    if (!empty($user_id) && $edit_users) {
        $form['fields']->approve_phone_number = [
            "title" => Registry::load('strings')->approve_phone_number, "tag" => 'select', "class" => 'field d-none'
        ];
        $form['fields']->approve_phone_number['options'] = [
            "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
        ];
    }

    if (empty($user_id)) {
        $form['fields']->email_login_link = [
            "title" => Registry::load('strings')->email_login_link, "tag" => 'select', "class" => 'field'
        ];
        $form['fields']->email_login_link['options'] = [
            "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
        ];
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'change_password']])) {

        $form['fields']->password = [
            "title" => Registry::load('strings')->password, "tag" => 'input', "type" => 'password', "class" => 'field',
            "placeholder" => Registry::load('strings')->password, "attributes" => ["autocomplete" => "new-password"]
        ];

        $form['fields']->confirm_password = [
            "title" => Registry::load('strings')->confirm_password, "tag" => 'input', "type" => 'password', "class" => 'field',
            "placeholder" => Registry::load('strings')->confirm_password,
        ];
    }

    if (role(['permissions' => ['site_users' => ['create_user', 'edit_users']], 'condition' => 'OR'])) {
        if (!empty($site_roles)) {
            $form['fields']->site_role = [
                "title" => Registry::load('strings')->site_role, "tag" => 'select', "class" => 'field'
            ];

            $form['fields']->site_role['options'] = $site_roles;
        }
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'change_avatar']])) {

        $avatars = array();
        $directory = 'assets/files/avatars';
        $images = glob($directory . "/*.png");

        foreach ($images as $image) {
            $key = basename($image);
            $avatars[$key] = Registry::load('config')->site_url.$image;
        }

        $form['fields']->avatar = [
            "title" => Registry::load('strings')->choose_avatar, "tag" => 'image_list', "class" => 'field', "options" => $avatars
        ];
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'upload_custom_avatar']])) {
        $form['fields']->custom_avatar = [
            "title" => Registry::load('strings')->custom_avatar, "tag" => 'input', "type" => 'file', "class" => 'field filebrowse',
            "accept" => 'image/png,image/x-png,image/gif,image/jpeg,image/webp'
        ];
    }

    foreach ($custom_fields as $custom_field) {
        $field_name = $custom_field['field_name'];

        if ($custom_field['field_type'] === 'short_text' || $custom_field['field_type'] === 'link') {

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'input', "type" => 'text', "class" => 'field',
                "placeholder" => Registry::load('strings')->$field_name,
            ];

        } else if ($custom_field['field_type'] === 'long_text') {

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'textarea', "class" => 'field',
                "placeholder" => Registry::load('strings')->$field_name,
            ];
            $form['fields']->$field_name["attributes"] = ["rows" => 6];

        } else if ($custom_field['field_type'] === 'date') {

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'input', "type" => 'date', "class" => 'field',
                "placeholder" => Registry::load('strings')->$field_name,
            ];

        } else if ($custom_field['field_type'] === 'number') {

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'input', "type" => 'number', "class" => 'field',
                "placeholder" => Registry::load('strings')->$field_name,
            ];

        } else if ($custom_field['field_type'] === 'dropdown') {

            $field_options = array();
            $dropdownoptions = $field_name.'_options';

            if (isset(Registry::load('strings')->$dropdownoptions)) {
                $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
            }

            $form['fields']->$field_name = [
                "title" => Registry::load('strings')->$field_name, "tag" => 'select', "class" => 'field',
                "options" => $field_options,
            ];

        }


        if ((int)$custom_field['required'] === 1) {
            $form['fields']->$field_name['required'] = true;
        }

        if (!empty($user_id)) {
            if (isset($custom_field['field_value']) && !empty($custom_field['field_value'])) {
                $form['fields']->$field_name['value'] = $custom_field['field_value'];
                if (!empty($custom_field['editable_only_once']) && !$edit_users) {
                    $form['fields']->$field_name['attributes']['disabled'] = 'disabled';
                }
            }
        }
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'set_timezone']])) {
        $form['fields']->timezone = [
            "title" => Registry::load('strings')->timezone, "tag" => 'select', "class" => 'field', "optionkey" => "optionvalue"
        ];
        $form['fields']->timezone['options'] = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $form['fields']->timezone['options']['default'] = Registry::load('strings')->system_default;
    }

    $form['fields']->notification_tone = [
        "title" => Registry::load('strings')->notification_tone, "tag" => 'select', "class" => 'field audiopreview'
    ];
    $form['fields']->notification_tone['attributes']['class'] = 'preview_audio_file';
    $form['fields']->notification_tone['attributes']['audio_location'] = Registry::load('config')->site_url;
    $form['fields']->notification_tone['options'] = $tones;

    if (Registry::load('settings')->chat_page_boxed_layout === 'enable') {
        if (empty($user_id) || role(['permissions' => ['profile' => 'set_custom_background']])) {
            $form['fields']->custom_background = [
                "title" => Registry::load('strings')->custom_background, "tag" => 'input', "type" => 'file', "class" => 'field filebrowse',
                "accept" => 'image/png,image/x-png,image/gif,image/jpeg,image/webp'
            ];

            if (!empty($user_id)) {
                $form['fields']->remove_custom_bg = [
                    "title" => Registry::load('strings')->remove_custom_bg, "tag" => 'select', "class" => 'field d-none'
                ];
                $form['fields']->remove_custom_bg['options'] = [
                    "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
                ];
            }
        }
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'set_cover_pic']])) {
        $form['fields']->cover_pic = [
            "title" => Registry::load('strings')->cover_pic, "tag" => 'input', "type" => 'file', "class" => 'field filebrowse',
            "accept" => 'image/png,image/x-png,image/gif,image/jpeg,image/webp'
        ];

        if (!empty($user_id)) {
            $form['fields']->remove_cover_pic = [
                "title" => Registry::load('strings')->remove_cover_pic, "tag" => 'select', "class" => 'field d-none'
            ];
            $form['fields']->remove_cover_pic['options'] = [
                "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
            ];
        }
    }

    if (empty($user_id) || role(['permissions' => ['profile' => 'disable_private_messages']])) {
        $form['fields']->disable_private_messages = [
            "title" => Registry::load('strings')->disable_private_messages, "tag" => 'select', "class" => 'field'
        ];
        $form['fields']->disable_private_messages['options'] = [
            "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
        ];
    }


    if (!empty($user_id) && role(['permissions' => ['profile' => 'deactivate_account']])) {
        $form['fields']->deactivate = [
            "title" => Registry::load('strings')->deactivate_account, "tag" => 'select', "class" => 'field'
        ];
        $form['fields']->deactivate['options'] = [
            "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
        ];
    }


    if (!empty($user_id) && role(['permissions' => ['profile' => 'delete_account']])) {
        $form['fields']->delete_account = [
            "title" => Registry::load('strings')->delete_account, "tag" => 'select', "class" => 'field'
        ];
        $form['fields']->delete_account['options'] = [
            "yes" => Registry::load('strings')->yes, "no" => Registry::load('strings')->no,
        ];
    }



    if (!empty($user_id)) {
        $disable_private_messages = 'no';
        $columns = $where = $join = null;

        $columns = [
            'site_users.display_name', 'site_users.username', 'site_users.email_address', 'site_users.site_role_id',
            'site_users_settings.time_zone', 'site_users_settings.notification_tone', 'site_users_settings.disable_private_messages',
            'site_users.unverified_email_address', 'site_users.phone_number', 'site_users.phone_verified', 'site_users.profile_bg_image',
            'site_users.profile_cover_pic'
        ];

        $join["[>]site_users_settings"] = ["site_users.user_id" => "user_id"];
        $where['site_users.user_id'] = $user_id;

        $user = DB::connect()->select('site_users', $join, $columns, $where);

        if (isset($user[0])) {
            $user = $user[0];
        } else {
            return false;
        }

        if ((int)$user['disable_private_messages'] === 1) {
            $disable_private_messages = 'yes';
        }

        if (isset($user['time_zone']) && $user['time_zone'] === 'default') {
            $user['time_zone'] = 'Default';
        }

        $form['fields']->user_id = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $user_id
        ];

        $form['fields']->full_name['value'] = $user['display_name'];
        $form['fields']->username['value'] = $user['username'];
        $form['fields']->email_address['value'] = $user['email_address'];

        if (isset($form['fields']->site_role)) {
            $form['fields']->site_role['value'] = $user['site_role_id'];
        }

        $form['fields']->timezone['value'] = $user['time_zone'];
        $form['fields']->notification_tone['value'] = $user['notification_tone'];
        $form['fields']->disable_private_messages['value'] = $disable_private_messages;

        if (role(['permissions' => ['profile' => 'change_password']])) {
            $form['fields']->password["title"] = Registry::load('strings')->new_password;
        }

        if (isset($user['unverified_email_address']) && !empty($user['unverified_email_address'])) {
            if (isset($form['fields']->unverified_email_address)) {
                $form['fields']->unverified_email_address["class"] = 'field';
                $form['fields']->unverified_email_address["value"] = $user['unverified_email_address'];
            }
        }

        if (isset($form['fields']->phone_number)) {
            $form['fields']->phone_number["class"] = 'field d-none';
        }



        if (isset($user['profile_bg_image']) && !empty($user['profile_bg_image'])) {
            if (basename($user['profile_bg_image']) !== 'default.png') {
                $form['fields']->remove_custom_bg["class"] = 'field';
            }
        }

        if (isset($user['profile_cover_pic']) && !empty($user['profile_cover_pic'])) {
            if (basename($user['profile_cover_pic']) !== 'default.png') {
                $form['fields']->remove_cover_pic["class"] = 'field';
            }
        }

        if (isset($user['phone_number']) && !empty($user['phone_number']) || $edit_users) {
            if (isset($form['fields']->phone_number)) {

                if (empty($user['phone_number'])) {
                    $user['phone_number'] = '';
                }

                $form['fields']->phone_number["class"] = 'field';
                $form['fields']->phone_number["value"] = $user['phone_number'];

                if (!$edit_users) {
                    $form['fields']->phone_number["attributes"]["disabled"] = 'disabled';
                } else {
                    if (empty($user['phone_verified'])) {
                        $form['fields']->approve_phone_number["class"] = 'field';
                    }
                }
            }
        }
    }
}
?>