<?php

include 'fns/filters/load.php';
include 'fns/files/load.php';
include_once('fns/cloud_storage/load.php');

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$noerror = true;
$group_create_limit = 500;


if ($force_request || role(['permissions' => ['groups' => 'create_groups']])) {
    $noerror = true;
    $disabled = 0;
    $strict_mode = true;
    $required_fields = ['group_name'];
    $update_image_data = array();
    $currentMonthYear = date('mY');
    $group_category_id = 1;

    $group_create_limit = role(['find' => 'group_create_limit']);

    if (empty($group_create_limit)) {
        $group_create_limit = 500;
    }

    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    if (!$force_request && !empty($group_create_limit)) {
        if (!empty(Registry::load('current_user')->id)) {

            $total_created_groups = DB::connect()->count('groups', ["groups.created_by" => Registry::load('current_user')->id]);

            if ($total_created_groups > $group_create_limit) {
                $result = array();
                $result['success'] = false;
                $result['error_message'] = Registry::load('strings')->exceeded_group_creation_limit;
                $result['error_key'] = 'exceeded_group_creation_limit';

                return false;
            }
        }
    }


    $columns = $where = null;

    $columns = [
        'custom_fields.field_id', 'custom_fields.string_constant(field_name)', 'custom_fields.field_type',
        'custom_fields.required'
    ];

    $where['AND'] = ['custom_fields.field_category' => 'group', 'custom_fields.disabled' => 0];
    $where["ORDER"] = ["custom_fields.field_id" => "ASC"];

    $custom_fields = DB::connect()->select('custom_fields', $columns, $where);


    if (isset($data['slug'])) {
        $data['slug'] = sanitize_slug($data['slug']);
    }

    if (!$force_request) {
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
        if (role(['permissions' => ['groups' => 'create_protected_group']])) {
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
    }

    if ($force_request || role(['permissions' => ['groups' => 'set_group_slug']])) {
        if (isset($data['slug']) && !empty($data['slug'])) {
            if (slug_exists($data['slug'])) {
                $result['error_variables'] = ['slug'];
                $result['error_message'] = Registry::load('strings')->slug_already_exists;
                $result['error_key'] = 'slug_already_exists';
                $noerror = false;
            }
        }
    }


    if ($noerror) {
        $insert_data = [
            "created_on" => Registry::load('current_user')->time_stamp,
            "updated_on" => Registry::load('current_user')->time_stamp,
        ];

        if ($force_request || role(['permissions' => ['groups' => 'set_auto_join_groups']])) {
            if (isset($data['auto_join_group'])) {
                if ($data['auto_join_group'] === 'yes') {
                    $insert_value = 1;
                } else {
                    $insert_value = 0;
                }

                $insert_data["auto_join_group"] = $insert_value;
            }
        }

        $default_group_visibility = role(['find' => 'default_group_visibility']);
        $secret_group = 0;

        if ($default_group_visibility === 'hidden') {
            $secret_group = 1;
        }

        if ($force_request || role(['permissions' => ['groups' => 'create_secret_group']])) {
            if (isset($data['secret_group'])) {
                if ($data['secret_group'] === 'yes') {
                    $secret_group = 1;
                } elseif ($data['secret_group'] === 'no') {
                    $secret_group = 0;
                }
            }
        }

        $insert_data["secret_group"] = $secret_group;
        $insert_data["secret_code"] = random_string(['length' => 8]);

        if ($force_request || role(['permissions' => ['groups' => 'create_unleavable_group']])) {
            if (isset($data['unleavable'])) {
                if ($data['unleavable'] === 'yes') {
                    $insert_value = 1;
                } else {
                    $insert_value = 0;
                }

                $insert_data["unleavable"] = $insert_value;
            }
        }

        if ($force_request || role(['permissions' => ['groups' => 'pin_groups']])) {
            if (isset($data['pin_group'])) {
                if ($data['pin_group'] === 'yes') {
                    $insert_value = 1;

                    if (isset($data['group_order'])) {
                        $data["group_order"] = filter_var($data["group_order"], FILTER_SANITIZE_NUMBER_INT);
                        if (!empty($data['group_order'])) {
                            $insert_value = $data['group_order'];
                        }
                    }

                } else {
                    $insert_value = 0;
                }

                $insert_data["pin_group"] = $insert_value;
            }
        }

        if ($force_request || role(['permissions' => ['groups' => 'create_video_chat_groups']])) {
            if (isset($data['video_chat'])) {
                if ($data['video_chat'] === 'enable') {
                    $insert_value = 1;
                } else {
                    $insert_value = 0;
                }

                $insert_data["enable_video_chat"] = $insert_value;
            }
        }

        if ($force_request || role(['permissions' => ['groups' => 'create_audio_chat_groups']])) {
            if (isset($data['audio_chat'])) {
                if ($data['audio_chat'] === 'enable') {
                    $insert_value = 1;
                } else {
                    $insert_value = 0;
                }

                $insert_data["enable_audio_chat"] = $insert_value;
            }
        }

        if ($force_request || role(['permissions' => ['groups' => 'set_participant_settings']])) {
            if (isset($data['who_all_can_send_messages'])) {
                $data['who_all_can_send_messages'] = array_filter($data['who_all_can_send_messages'], 'is_numeric');
                $insert_data["who_all_can_send_messages"] = json_encode($data['who_all_can_send_messages']);
            } else {
                if ($force_request) {
                    $insert_data["who_all_can_send_messages"] = 'all';
                } else {
                    $insert_data["who_all_can_send_messages"] = '';
                }
            }
        } else {
            $insert_data["who_all_can_send_messages"] = 'all';
        }

        if ($force_request || role(['permissions' => ['groups' => 'set_default_group_role_within_group']])) {
            if (isset($data['default_group_role']) && !empty($data['default_group_role'])) {
                $insert_data["default_group_role"] = $data['default_group_role'];
            }
        }


        $data['group_name'] = htmlspecialchars(trim($data['group_name']), ENT_QUOTES, 'UTF-8');
        $insert_data["name"] = $data['group_name'];

        if (isset($data['description'])) {
            $data['description'] = htmlspecialchars(trim($data['description']), ENT_QUOTES, 'UTF-8');
            $insert_data["description"] = $data['description'];
        }

        if ($force_request || role(['permissions' => ['groups' => 'set_group_slug']])) {
            if (isset($data['slug']) && !empty($data['slug'])) {
                $insert_data["slug"] = $data['slug'];
            }
        }

        $group_category_id = role(['find' => 'default_group_category']);

        if ($force_request || role(['permissions' => ['groups' => 'set_group_category']])) {
            if (isset($data['group_category_id']) && !empty($data['group_category_id'])) {

                $data["group_category_id"] = filter_var($data["group_category_id"], FILTER_SANITIZE_NUMBER_INT);

                if (!empty($data["group_category_id"])) {
                    $group_category_id = $data["group_category_id"];
                }
            }
        }

        if (!empty($group_category_id)) {
            $insert_data["group_category_id"] = $group_category_id;
        }

        if ($force_request || role(['permissions' => ['groups' => 'create_protected_group']])) {
            if (isset($data['password_protect']) && $data['password_protect'] === 'no') {
                $insert_data["password"] = '';
            } elseif (isset($data['password']) && !empty($data['password'])) {
                $insert_data["password"] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
        }

        if ($force_request || role(['permissions' => ['groups' => 'add_meta_tags']])) {
            if (isset($data['meta_title']) && !empty($data['meta_title'])) {
                $data['meta_title'] = htmlspecialchars(trim($data['meta_title']), ENT_QUOTES, 'UTF-8');
                if (!empty($data['meta_title'])) {
                    $insert_data["meta_title"] = $data['meta_title'];
                }
            }


            if (isset($data['meta_description']) && !empty($data['meta_description'])) {
                $data['meta_description'] = htmlspecialchars(trim($data['meta_description']), ENT_QUOTES, 'UTF-8');
                if (!empty($data['meta_description'])) {
                    $insert_data["meta_description"] = $data['meta_description'];
                }
            }
        }

        if (!empty(Registry::load('current_user')->id)) {
            $insert_data["created_by"] = Registry::load('current_user')->id;
        }

        DB::connect()->insert("groups", $insert_data);

        if (!DB::connect()->error) {
            $group_id = DB::connect()->id();

            if ($force_request || role(['permissions' => ['groups' => 'set_custom_background']])) {
                if (isset($_FILES['custom_background']['name']) && !empty($_FILES['custom_background']['name'])) {
                    if (isImage($_FILES['custom_background']['tmp_name'])) {

                        $update_image_data['group_bg_image'] = null;

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
                if (isset($_FILES['cover_pic']['name']) && !empty($_FILES['cover_pic']['name'])) {
                    if (isImage($_FILES['cover_pic']['tmp_name'])) {

                        $update_image_data['group_cover_pic'] = 'assets/files/groups/cover_pics/default.png';

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

                    $update_image_data['group_picture'] = 'assets/files/groups/icons/default.png';

                    $extension = pathinfo($_FILES['group_icon']['name'])['extension'];
                    $filename = $group_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;


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
                        if (isset($custom_field['field_value'])) {
                            $update_data = ['field_value' => $data[$field_name], 'updated_on' => Registry::load('current_user')->time_stamp];
                            $where = ['AND' => ["field_id" => $custom_field['field_id'], "group_id" => $group_id]];
                            DB::connect()->update("custom_fields_values", $update_data, $where);
                        } else {
                            $insert_data = ['field_value' => $data[$field_name], 'updated_on' => Registry::load('current_user')->time_stamp];
                            $insert_data["field_id"] = $custom_field['field_id'];
                            $insert_data["group_id"] = $group_id;
                            DB::connect()->insert("custom_fields_values", $insert_data);
                        }
                    }
                }
            }

            if (!empty(Registry::load('current_user')->id)) {
                include_once('fns/add/load.php');

                $group_member = array();
                $group_member['add'] = 'group_members';
                $group_member['group_id'] = $group_id;
                $group_member['user_id'] = Registry::load('current_user')->id;
                $group_member['return'] = true;
                add($group_member, ['force_request' => true, 'administrator' => true, 'owner' => true]);
            }

            $result = array();
            $result['success'] = true;

            if (!$api_request) {
                $result['todo'] = 'load_conversation';
                $result['identifier_type'] = 'group_id';
                $result['identifier'] = $group_id;
                $result['reload_aside'] = true;
            } else {
                $result['group_id'] = $group_id;
            }
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }
    }
}