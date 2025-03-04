<?php

include 'fns/filters/load.php';
include 'fns/files/load.php';
include_once 'fns/cloud_storage/load.php';

$result = array();
$noerror = true;
$super_privileges = false;
$delete_files = array();
$check_group_ids = true;

if ($force_request || role(['permissions' => ['groups' => 'super_privileges']])) {
    $super_privileges = true;
}

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$group_ids = array();

if (isset($data['group_id'])) {
    if (!is_array($data['group_id'])) {
        $data["group_id"] = filter_var($data["group_id"], FILTER_SANITIZE_NUMBER_INT);
        $group_ids[] = $data["group_id"];
    } else {
        $group_ids = array_filter($data["group_id"], 'ctype_digit');
    }
}

if ($force_request) {
    if (isset($data['group'])) {
        $columns = $join = $where = null;

        $columns = ['groups.group_id', 'groups.group_picture', 'groups.group_cover_pic', 'groups.group_bg_image'];
        $where["OR"] = ["groups.group_id" => $data['group'], "groups.slug" => $data['group']];
        $where["LIMIT"] = 1;

        $find_group = DB::connect()->select('groups', $columns, $where);
        $group_ids = array();

        if (isset($find_group[0])) {
            $group_ids[] = $find_group[0]['group_id'];
            $check_group_ids = false;

            if (!empty($find_group[0]['group_bg_image']) && basename($find_group[0]['group_bg_image']) !== 'default.png') {
                $delete_files[] = $find_group[0]['group_bg_image'];

                if (file_exists($find_group[0]['group_bg_image'])) {
                    unlink($find_group[0]['group_bg_image']);
                }
            }
            if (!empty($find_group[0]['group_cover_pic']) && basename($find_group[0]['group_cover_pic']) !== 'default.png') {
                $delete_files[] = $find_group[0]['group_cover_pic'];

                if (file_exists($find_group[0]['group_cover_pic'])) {
                    unlink($find_group[0]['group_cover_pic']);
                }
            }
            if (!empty($find_group[0]['group_picture']) && basename($find_group[0]['group_picture']) !== 'default.png') {
                $delete_files[] = $find_group[0]['group_picture'];

                if (file_exists($find_group[0]['group_picture'])) {
                    unlink($find_group[0]['group_picture']);
                }
            }

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

if ($check_group_ids && !empty($group_ids)) {

    $columns = $where = $join = null;
    $columns = [
        'groups.group_id', 'group_members.group_role_id',
        'groups.group_picture', 'groups.group_cover_pic', 'groups.group_bg_image'
    ];

    $join["[>]group_members"] = ["groups.group_id" => "group_id", "AND" => ["user_id" => Registry::load('current_user')->id]];

    $where["groups.group_id"] = $group_ids;

    if (!$super_privileges) {
        $where["groups.suspended"] = 0;
    }

    $groups = DB::connect()->select('groups', $join, $columns, $where);


    $group_ids = array();
    foreach ($groups as $group) {

        $add_group_list = false;

        if ($super_privileges) {
            $add_group_list = true;
        } else if (isset($group['group_role_id']) && !empty($group['group_role_id'])) {
            if (role(['permissions' => ['group' => 'delete_group'], 'group_role_id' => $group['group_role_id']])) {
                $add_group_list = true;
            }
        }

        if ($add_group_list) {
            $group_ids[] = $group['group_id'];

            if (!empty($group['group_bg_image']) && basename($group['group_bg_image']) !== 'default.png') {
                $delete_files[] = $group['group_bg_image'];

                if (file_exists($group['group_bg_image'])) {
                    unlink($group['group_bg_image']);
                }
            }
            if (!empty($group['group_cover_pic']) && basename($group['group_cover_pic']) !== 'default.png') {
                $delete_files[] = $group['group_cover_pic'];

                if (file_exists($group['group_cover_pic'])) {
                    unlink($group['group_cover_pic']);
                }
            }
            if (!empty($group['group_picture']) && basename($group['group_picture']) !== 'default.png') {
                $delete_files[] = $group['group_picture'];

                if (file_exists($group['group_picture'])) {
                    unlink($group['group_picture']);
                }
            }
        }
    }
}

if (!empty($group_ids)) {
    DB::connect()->delete("groups", ["group_id" => $group_ids]);

    if (!DB::connect()->error) {
        foreach ($group_ids as $group_id) {

            $delete_audio_messages = [
                'delete' => 'assets/files/audio_messages/group_chat/'.$group_id,
                'real_path' => true,
            ];

            files('delete', $delete_audio_messages);

            if (Registry::load('settings')->cloud_storage !== 'disable') {
                $delete_folder = 'assets/files/audio_messages/group_chat/'.$group_id.'/';
                cloud_storage_module(['delete_folder' => $delete_folder]);
            }

            $group_header_file = 'assets/group_headers/group_'.$group_id.'.php';

            if (file_exists($group_header_file)) {
                unlink($group_header_file);
            }


        }

        if (!empty($delete_files)) {
            if (Registry::load('settings')->cloud_storage !== 'disable') {
                cloud_storage_module(['delete_files' => $delete_files]);
            }
        }

        $result = array();
        $result['success'] = true;
        $result['todo'] = 'refresh';
    } else {
        $result['error_message'] = Registry::load('strings')->went_wrong;
        $result['error_key'] = 'something_went_wrong';
    }
}