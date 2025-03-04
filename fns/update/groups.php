<?php

include 'fns/filters/load.php';
include 'fns/files/load.php';

include_once('fns/cloud_storage/load.php');

$noerror = true;
$group_id = 0;
$required_fields = ['group_name'];
$update_image_data = array();
$currentMonthYear = date('mY');

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$result['error_variables'] = [];

$super_privileges = false;

if ($force_request || role(['permissions' => ['groups' => 'super_privileges']])) {
    $super_privileges = true;
}

if (isset($data['group_id'])) {
    $data["group_id"] = filter_var($data["group_id"], FILTER_SANITIZE_NUMBER_INT);
    if (!empty($data['group_id'])) {
        $group_id = $data["group_id"];
    }
}

if ($force_request) {
    if (isset($data['group'])) {
        $columns = $join = $where = null;

        $columns = ['groups.group_id'];
        $where["OR"] = ["groups.group_id" => $data['group'], "groups.slug" => $data['group']];
        $where["LIMIT"] = 1;

        $find_group = DB::connect()->select('groups', $columns, $where);

        if (isset($find_group[0])) {
            $group_id = $find_group[0]['group_id'];
        } else {
            $result = array();
            $result['success'] = false;
            $result['error_message'] = 'Group Not Found';
            $result['error_key'] = 'group_not_found';
            $result['error_variables'] = [];
            return;
        }
    }
}

if (!empty($group_id)) {
    $columns = $where = $join = null;
    $columns = [
        'groups.group_id', 'groups.name', 'groups.slug', 'groups.description', 'groups.suspended',
        'groups.secret_group', 'groups.password', 'groups.unleavable', 'groups.who_all_can_send_messages',
        'groups.pin_group', 'groups.auto_join_group', 'groups.created_by', 'group_members.group_role_id',
        'groups.secret_code', 'groups.group_picture', 'groups.group_cover_pic', 'groups.group_bg_image',
        'groups.enable_video_chat', 'groups.enable_audio_chat'
    ];

    $join["[>]group_members"] = ["groups.group_id" => "group_id", "AND" => ["user_id" => Registry::load('current_user')->id]];

    $where['groups.group_id'] = $group_id;

    $group = DB::connect()->select('groups', $join, $columns, $where);

    if (isset($group[0])) {
        $group = $group[0];
    } else {
        return false;
    }

    if (!$super_privileges && isset($group['suspended']) && !empty($group['suspended'])) {
        return false;
    }

    if ($super_privileges || isset($group['group_role_id']) && !empty($group['group_role_id'])) {
        if ($super_privileges || role(['permissions' => ['group' => 'edit_group'], 'group_role_id' => $group['group_role_id']])) {
            $result['success'] = false;
            $result['error_message'] = Registry::load('strings')->invalid_value;
            $result['error_key'] = 'invalid_value';
            $result['error_variables'] = [];

            $columns = $where = $join = null;

            $columns = [
                'custom_fields.field_id', 'custom_fields.string_constant(field_name)', 'custom_fields.field_type',
                'custom_fields.required', 'custom_fields_values.field_value', 'custom_fields.editable_only_once'
            ];

            $join["[>]custom_fields_values"] = [
                "custom_fields.field_id" => "field_id",
                "AND" => ["group_id" => $group_id]
            ];

            $where['AND'] = ['custom_fields.field_category' => 'group', 'custom_fields.disabled' => 0];
            $where["ORDER"] = ["custom_fields.field_id" => "ASC"];

            $custom_fields = DB::connect()->select('custom_fields', $join, $columns, $where);

            if (isset($data['slug'])) {
                $data['slug'] = sanitize_slug($data['slug']);
            }

            foreach ($custom_fields as $custom_field) {
                if ((int)$custom_field['required'] === 1) {
                    if (!empty($custom_field['editable_only_once']) && isset($custom_field['field_value'])) {
                        continue;
                    } else {
                        $required_fields[] = $custom_field['field_name'];
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

            if ($force_request || role(['permissions' => ['groups' => 'create_protected_group']])) {
                if (isset($data['password_protect']) && $data['password_protect'] === 'yes') {
                    if (isset($data['password']) && !empty($data['password'])) {
                        if (!isset($data['confirm_password']) || isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
                            $result['error_variables'] = ['password', 'confirm_password'];
                            $result['error_message'] = Registry::load('strings')->password_doesnt_match;
                            $result['error_key'] = 'password_doesnt_match';
                            $noerror = false;
                        }
                    }
                }
            }

            if ($force_request || role(['permissions' => ['groups' => 'set_group_slug']])) {
                if (isset($data['slug']) && !empty($data['slug']) && $data['slug'] !== $group['slug']) {
                    if (slug_exists($data['slug'])) {
                        $result['error_variables'] = ['slug'];
                        $result['error_message'] = Registry::load('strings')->slug_already_exists;
                        $result['error_key'] = 'slug_already_exists';
                        $noerror = false;
                    }
                }
            }


            if ($noerror && !empty($group_id)) {
                $update_data = [
                    "updated_on" => Registry::load('current_user')->time_stamp,
                ];

                if ($force_request || role(['permissions' => ['groups' => 'set_auto_join_groups']])) {
                    if (isset($data['auto_join_group'])) {
                        if ($data['auto_join_group'] === 'yes') {
                            $update_value = 1;
                        } else {
                            $update_value = 0;
                        }

                        if ($update_value !== $group['auto_join_group']) {
                            $update_data["auto_join_group"] = $update_value;
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'create_secret_group']])) {
                    if (isset($data['secret_group'])) {
                        if ($data['secret_group'] === 'yes') {
                            $update_value = 1;
                        } else {
                            $update_value = 0;
                        }

                        if ($update_value !== $group['secret_group']) {
                            $update_data["secret_group"] = $update_value;
                        }
                    }
                }

                if (!isset($group["secret_code"]) || empty($group["secret_code"])) {
                    $update_data["secret_code"] = random_string(['length' => 8]);
                }

                if ($force_request || role(['permissions' => ['groups' => 'create_unleavable_group']])) {
                    if (isset($data['unleavable'])) {
                        if ($data['unleavable'] === 'yes') {
                            $update_value = 1;
                        } else {
                            $update_value = 0;
                        }

                        if ($update_value !== $group['unleavable']) {
                            $update_data["unleavable"] = $update_value;
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'pin_groups']])) {
                    if (isset($data['pin_group'])) {
                        if ($data['pin_group'] === 'yes') {
                            $update_value = 1;

                            if (isset($data['group_order'])) {
                                $data["group_order"] = filter_var($data["group_order"], FILTER_SANITIZE_NUMBER_INT);
                                if (!empty($data['group_order'])) {
                                    $update_value = $data['group_order'];
                                }
                            }
                        } else {
                            $update_value = 0;
                        }

                        if ((int)$update_value !== (int)$group['pin_group']) {
                            $update_data["pin_group"] = $update_value;
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'create_video_chat_groups']])) {
                    if (isset($data['video_chat'])) {
                        if ($data['video_chat'] === 'enable') {
                            $update_value = 1;
                        } else {
                            $update_value = 0;
                        }

                        if ((int)$update_value !== (int)$group['enable_video_chat']) {
                            $update_data["enable_video_chat"] = $update_value;
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'create_audio_chat_groups']])) {
                    if (isset($data['audio_chat'])) {
                        if ($data['audio_chat'] === 'enable') {
                            $update_value = 1;
                        } else {
                            $update_value = 0;
                        }

                        if ((int)$update_value !== (int)$group['enable_audio_chat']) {
                            $update_data["enable_audio_chat"] = $update_value;
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'set_participant_settings']])) {
                    if (isset($data['who_all_can_send_messages'])) {
                        $data['who_all_can_send_messages'] = array_filter($data['who_all_can_send_messages'], 'is_numeric');
                        $update_data["who_all_can_send_messages"] = json_encode($data['who_all_can_send_messages']);
                    } else {
                        if (!$force_request) {
                            $update_data["who_all_can_send_messages"] = '';
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'set_default_group_role_within_group']])) {
                    if (isset($data['default_group_role']) && !empty($data['default_group_role'])) {
                        $update_data["default_group_role"] = $data['default_group_role'];
                    } else if (isset($data['default_group_role']) && empty($data['default_group_role'])) {
                        $update_data["default_group_role"] = null;
                    }
                }

                if (isset($data['group_name'])) {
                    $data['group_name'] = htmlspecialchars(trim($data['group_name']), ENT_QUOTES, 'UTF-8');
                    if (!empty($data['group_name'])) {
                        if ($data['group_name'] !== $group['name']) {
                            $update_data["name"] = $data['group_name'];
                        }
                    }
                }

                if (isset($data['description'])) {
                    $data['description'] = htmlspecialchars(trim($data['description']), ENT_QUOTES, 'UTF-8');
                    if ($data['description'] !== $group['description']) {
                        $update_data["description"] = $data['description'];
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'add_meta_tags']])) {
                    if (isset($data['meta_title']) && !empty($data['meta_title'])) {
                        $data['meta_title'] = htmlspecialchars(trim($data['meta_title']), ENT_QUOTES, 'UTF-8');
                        if (!empty($data['meta_title'])) {
                            $update_data["meta_title"] = $data['meta_title'];
                        }
                    }


                    if (isset($data['meta_description']) && !empty($data['meta_description'])) {
                        $data['meta_description'] = htmlspecialchars(trim($data['meta_description']), ENT_QUOTES, 'UTF-8');
                        if (!empty($data['meta_description'])) {
                            $update_data["meta_description"] = $data['meta_description'];
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'set_group_slug']])) {
                    if (isset($data['slug']) && !empty($data['slug'])) {
                        if ($data['slug'] !== $group['slug']) {
                            $update_data["slug"] = $data['slug'];
                        }
                    }
                }


                if ($force_request || role(['permissions' => ['groups' => 'set_group_category']])) {
                    if (isset($data['group_category_id']) && !empty($data['group_category_id'])) {

                        $data["group_category_id"] = filter_var($data["group_category_id"], FILTER_SANITIZE_NUMBER_INT);

                        if (!empty($data['group_category_id'])) {
                            $update_data["group_category_id"] = $data['group_category_id'];
                        }
                    }
                }

                if ($force_request || role(['permissions' => ['groups' => 'create_protected_group']])) {
                    if (isset($data['password_protect']) && $data['password_protect'] === 'no' && !empty($group['password'])) {
                        $update_data["password"] = null;
                    } elseif (isset($data['password']) && !empty($data['password'])) {
                        $update_data["password"] = password_hash($data['password'], PASSWORD_BCRYPT);
                    }
                }


                if (empty($group['created_by']) && !empty(Registry::load('current_user')->id)) {
                    $update_data["created_by"] = Registry::load('current_user')->id;
                }

                DB::connect()->update("groups", $update_data, ["group_id" => $group_id]);

                if (!DB::connect()->error) {
                    if (!$force_request && isset(Registry::load('settings')->system_messages_groups->on_updating_group_info)) {
                        $system_message = [
                            'message' => 'updated_group_info'
                        ];

                        $system_message = json_encode($system_message);
                        DB::connect()->insert("group_messages", [
                            "system_message" => 1,
                            "original_message" => 'system_message',
                            "filtered_message" => $system_message,
                            "group_id" => $group_id,
                            "user_id" => Registry::load('current_user')->id,
                            "created_on" => Registry::load('current_user')->time_stamp,
                            "updated_on" => Registry::load('current_user')->time_stamp,
                        ]);

                        DB::connect()->update("groups", ["updated_on" => Registry::load('current_user')->time_stamp], ['group_id' => $group_id]);
                    }

                    if ($force_request || role(['permissions' => ['groups' => 'set_custom_background']])) {
                        if (isset($data['remove_custom_bg']) && $data['remove_custom_bg'] === 'yes') {

                            $update_image_data['group_bg_image'] = null;

                            if (!empty($group['group_bg_image'])) {

                                $delete_image = false;

                                if (!empty($group['group_bg_image']) && basename($group['group_bg_image']) !== 'default.png') {
                                    $delete_image = true;
                                }

                                if ($delete_image && file_exists($group['group_bg_image'])) {
                                    unlink($group['group_bg_image']);
                                }

                                if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                                    cloud_storage_module(['delete_file' => $group['group_bg_image']]);
                                }
                            }
                        } elseif (isset($_FILES['custom_background']['name']) && !empty($_FILES['custom_background']['name'])) {
                            if (isImage($_FILES['custom_background']['tmp_name'])) {

                                $update_image_data['group_bg_image'] = null;

                                if (!empty($group['group_bg_image'])) {

                                    $delete_image = false;

                                    if (!empty($group['group_bg_image']) && basename($group['group_bg_image']) !== 'default.png') {
                                        $delete_image = true;
                                    }

                                    if ($delete_image && file_exists($group['group_bg_image'])) {
                                        unlink($group['group_bg_image']);
                                    }

                                    if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                                        cloud_storage_module(['delete_file' => $group['group_bg_image']]);
                                    }
                                }

                                $extension = pathinfo($_FILES['custom_background']['name'])['extension'];
                                $filename = $group_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

                                $folder_path = 'assets/files/groups/backgrounds/'.$currentMonthYear.'/';

                                if (!file_exists($folder_path)) {
                                    mkdir($folder_path, 0755, true);
                                }

                                if (files('upload', ['upload' => 'custom_background', 'folder' => 'groups/backgrounds/'.$currentMonthYear, 'saveas' => $filename])['result']) {
                                    files('resize_img', ['resize' => 'groups/backgrounds/'.$currentMonthYear.'/'.$filename, 'width' => 1000, 'height' => 1000, 'crop' => false]);
                                    $update_image_data['group_bg_image'] = $folder_path.$filename;

                                    if (Registry::load('settings')->cloud_storage !== 'disable') {
                                        cloud_storage_module(['upload_file' => $update_image_data['group_bg_image'], 'delete' => true]);
                                    }
                                }
                            }
                        }
                    }

                    if ($force_request || role(['permissions' => ['groups' => 'set_cover_pic']])) {
                        if (isset($data['remove_cover_pic']) && $data['remove_cover_pic'] === 'yes') {
                            $update_image_data['group_cover_pic'] = 'assets/files/groups/cover_pics/default.png';

                            if (!empty($group['group_cover_pic'])) {

                                $delete_image = false;

                                if (!empty($group['group_cover_pic']) && basename($group['group_cover_pic']) !== 'default.png') {
                                    $delete_image = true;
                                }

                                if ($delete_image && file_exists($group['group_cover_pic'])) {
                                    unlink($group['group_cover_pic']);
                                }

                                if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                                    cloud_storage_module(['delete_file' => $group['group_cover_pic']]);
                                }
                            }
                        } elseif (isset($_FILES['cover_pic']['name']) && !empty($_FILES['cover_pic']['name'])) {
                            if (isImage($_FILES['cover_pic']['tmp_name'])) {
                                $update_image_data['group_cover_pic'] = 'assets/files/groups/cover_pics/default.png';

                                if (!empty($group['group_cover_pic'])) {

                                    $delete_image = false;

                                    if (!empty($group['group_cover_pic']) && basename($group['group_cover_pic']) !== 'default.png') {
                                        $delete_image = true;
                                    }

                                    if ($delete_image && file_exists($group['group_cover_pic'])) {
                                        unlink($group['group_cover_pic']);
                                    }

                                    if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                                        cloud_storage_module(['delete_file' => $group['group_cover_pic']]);
                                    }
                                }

                                $extension = pathinfo($_FILES['cover_pic']['name'])['extension'];
                                $filename = $group_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

                                $folder_path = 'assets/files/groups/cover_pics/'.$currentMonthYear.'/';

                                if (!file_exists($folder_path)) {
                                    mkdir($folder_path, 0755, true);
                                }

                                if (files('upload', ['upload' => 'cover_pic', 'folder' => 'groups/cover_pics/'.$currentMonthYear, 'saveas' => $filename])['result']) {
                                    $resize_image = true;
                                    if (isset(Registry::load('settings')->image_moderation) && Registry::load('settings')->image_moderation !== 'disable') {
                                        include_once('fns/image_moderation/load.php');

                                        $image_location = 'assets/files/groups/cover_pics/'.$currentMonthYear.'/'.$filename;
                                        $image_moderation = moderate_image_content($image_location);

                                        if (!$image_moderation['success']) {
                                            if (file_exists($image_location)) {
                                                unlink($image_location);
                                            }
                                            $resize_image = false;
                                        }
                                    }

                                    if ($resize_image) {
                                        files('resize_img', ['resize' => 'groups/cover_pics/'.$currentMonthYear.'/'.$filename, 'width' => 400, 'height' => 400, 'crop' => true]);

                                        $update_image_data['group_cover_pic'] = 'assets/files/groups/cover_pics/'.$currentMonthYear.'/'.$filename;

                                        if (Registry::load('settings')->cloud_storage !== 'disable') {
                                            cloud_storage_module(['upload_file' => $update_image_data['group_cover_pic'], 'delete' => true]);
                                        }
                                    }
                                }
                            }
                        }
                    }


                    if (isset($_FILES['group_icon']['name']) && !empty($_FILES['group_icon']['name'])) {
                        if (isImage($_FILES['group_icon']['tmp_name'])) {
                            $extension = pathinfo($_FILES['group_icon']['name'])['extension'];
                            $update_image_data['group_picture'] = 'assets/files/groups/icons/default.png';
                            $filename = $group_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

                            $delete_image = false;

                            if (!empty($group['group_picture']) && basename($group['group_picture']) !== 'default.png') {
                                $delete_image = true;
                            }

                            if ($delete_image && file_exists($group['group_picture'])) {
                                unlink($group['group_picture']);
                            }

                            if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                                cloud_storage_module(['delete_file' => $group['group_picture']]);
                            }

                            $folder_path = 'assets/files/groups/icons/'.$currentMonthYear.'/';

                            if (!file_exists($folder_path)) {
                                mkdir($folder_path, 0755, true);
                            }

                            if (files('upload', ['upload' => 'group_icon', 'folder' => 'groups/icons/'.$currentMonthYear, 'saveas' => $filename])['result']) {
                                $resize_image = true;

                                if (isset(Registry::load('settings')->image_moderation) && Registry::load('settings')->image_moderation !== 'disable') {
                                    include_once('fns/image_moderation/load.php');

                                    $image_location = 'assets/files/groups/icons/'.$currentMonthYear.'/'.$filename;
                                    $image_moderation = moderate_image_content($image_location);

                                    if (!$image_moderation['success']) {
                                        if (file_exists($image_location)) {
                                            unlink($image_location);
                                        }
                                        $resize_image = false;
                                    }
                                }

                                if ($resize_image) {

                                    $update_image_data['group_picture'] = 'assets/files/groups/icons/'.$currentMonthYear.'/'.$filename;
                                    files('resize_img', ['resize' => 'groups/icons/'.$currentMonthYear.'/'.$filename, 'width' => 200, 'height' => 200, 'crop' => true]);

                                    if (Registry::load('settings')->cloud_storage !== 'disable') {
                                        cloud_storage_module(['upload_file' => $update_image_data['group_picture'], 'delete' => true]);
                                    }
                                }
                            }
                        }
                    } elseif ($force_request && isset($data['group_icon_url'])) {

                        $update_image_data['group_picture'] = 'assets/files/groups/icons/default.png';

                        $delete_image = false;

                        if (!empty($group['group_picture']) && basename($group['group_picture']) !== 'default.png') {
                            $delete_image = true;
                        }

                        if ($delete_image && file_exists($group['group_picture'])) {
                            unlink($group['group_picture']);
                        }

                        if ($delete_image && Registry::load('settings')->cloud_storage !== 'disable') {
                            cloud_storage_module(['delete_file' => $group['group_picture']]);
                        }

                        $data['group_icon_url'] = filter_var($data['group_icon_url'], FILTER_SANITIZE_URL);

                        if (!empty($data['group_icon_url'])) {
                            $icon_file_name = $group_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.png';


                            $folder_path = 'assets/files/groups/icons/'.$currentMonthYear.'/';
                            $icon_file = $folder_path.$icon_file_name;

                            if (!file_exists($folder_path)) {
                                mkdir($folder_path, 0755, true);
                            }

                            $curl_request = curl_init($data['group_icon_url']);
                            $save_icon = fopen($icon_file, 'wb');
                            curl_setopt($curl_request, CURLOPT_FILE, $save_icon);
                            curl_setopt($curl_request, CURLOPT_HEADER, 0);
                            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 1);
                            curl_setopt($curl_request, CURLOPT_ENCODING, '');
                            curl_exec($curl_request);
                            curl_close($curl_request);
                            fclose($save_icon);

                            if (file_exists($icon_file)) {
                                $icon_content_type = mime_content_type($icon_file);
                                if (strpos($icon_content_type, 'image/') !== false) {
                                    files('resize_img', ['resize' => 'groups/icons/'.$currentMonthYear.'/'.$icon_file_name, 'width' => 200, 'height' => 200, 'crop' => true]);

                                    $update_image_data['group_picture'] = $icon_file;

                                    if (Registry::load('settings')->cloud_storage !== 'disable') {
                                        cloud_storage_module(['upload_file' => $icon_file, 'delete' => true]);
                                    }

                                } else {
                                    unlink($icon_file);
                                }
                            }
                        }

                    }

                    if (!empty($update_image_data)) {
                        DB::connect()->update("groups", $update_image_data, ["group_id" => $group_id]);
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
                                    if ($super_privileges || empty($custom_field['editable_only_once']) || empty($custom_field['field_value'])) {
                                        $update_data = ['field_value' => $data[$field_name], 'updated_on' => Registry::load('current_user')->time_stamp];
                                        $where = ['AND' => ["field_id" => $custom_field['field_id'], "group_id" => $group_id]];
                                        DB::connect()->update("custom_fields_values", $update_data, $where);
                                    }
                                } else {
                                    $insert_data = ['field_value' => $data[$field_name], 'updated_on' => Registry::load('current_user')->time_stamp];
                                    $insert_data["field_id"] = $custom_field['field_id'];
                                    $insert_data["group_id"] = $group_id;
                                    DB::connect()->insert("custom_fields_values", $insert_data);
                                }
                            }
                        }
                    }

                    $result = array();
                    $result['success'] = true;

                    if (!$api_request) {
                        $result['todo'] = 'load_conversation';
                        $result['identifier_type'] = 'group_id';
                        $result['identifier'] = $group_id;
                        $result['reload_aside'] = true;
                    }
                } else {
                    $result['error_message'] = Registry::load('strings')->went_wrong;
                    $result['error_key'] = 'something_went_wrong';
                }
            }
        }
    }
}