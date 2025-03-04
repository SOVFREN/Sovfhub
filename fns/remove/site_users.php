<?php

include_once 'fns/filters/load.php';
include_once 'fns/files/load.php';
include_once 'fns/cloud_storage/load.php';

use SleekDB\Store;

$result = array();
$noerror = true;

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->something_went_wrong;
$result['error_key'] = 'something_went_wrong';
$user_ids = array();
$delete_files = array();


if ($force_request || role(['permissions' => ['site_users' => 'delete_users']])) {

    if (isset($data['user_id'])) {
        if (!is_array($data['user_id'])) {
            $data["user_id"] = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);
            $user_ids[] = $data["user_id"];
        } else {
            $user_ids = array_filter($data["user_id"], 'ctype_digit');
        }
    }

    if ($force_request) {
        if (isset($data['user'])) {
            $columns = $join = $where = null;

            $columns = ['site_users.user_id', 'site_users.profile_picture', 'site_users.profile_cover_pic', 'site_users.profile_bg_image'];
            $where["OR"] = ["site_users.username" => $data['user'], "site_users.email_address" => $data['user']];
            $where["LIMIT"] = 1;

            $site_user = DB::connect()->select('site_users', $columns, $where);

            $user_ids = array();

            if (isset($site_user[0])) {
                $user_ids[] = $site_user[0]['user_id'];

                if (!empty($site_user[0]['profile_bg_image']) && basename($site_user[0]['profile_bg_image']) !== 'default.png') {
                    $delete_files[] = $site_user[0]['profile_bg_image'];

                    if (file_exists($site_user[0]['profile_bg_image'])) {
                        unlink($site_user[0]['profile_bg_image']);
                    }
                }
                if (!empty($site_user[0]['profile_cover_pic']) && basename($site_user[0]['profile_cover_pic']) !== 'default.png') {
                    $delete_files[] = $site_user[0]['profile_cover_pic'];

                    if (file_exists($site_user[0]['profile_cover_pic'])) {
                        unlink($site_user[0]['profile_cover_pic']);
                    }
                }
                if (!empty($site_user[0]['profile_picture']) && basename($site_user[0]['profile_picture']) !== 'default.png') {
                    $delete_files[] = $site_user[0]['profile_picture'];

                    if (file_exists($site_user[0]['profile_picture'])) {
                        unlink($site_user[0]['profile_picture']);
                    }
                }
            } else {
                $result = array();
                $result['success'] = false;
                $result['error_message'] = Registry::load('strings')->account_not_found;
                $result['error_key'] = 'account_not_found';
                $result['error_variables'] = [];
                return;
            }
        }
    } else {
        if (!empty($user_ids)) {
            $columns = $join = $where = null;

            $columns = [
                'site_users.user_id', 'site_users.site_role_id',
                'site_users.profile_picture', 'site_users.profile_cover_pic', 'site_users.profile_bg_image',
                'site_roles.site_role_attribute', 'site_roles.role_hierarchy'
            ];
            $join["[>]site_roles"] = ["site_users.site_role_id" => "site_role_id"];
            $where["site_users.user_id"] = $user_ids;

            $site_users = DB::connect()->select('site_users', $join, $columns, $where);
            $user_ids = array();

            foreach ($site_users as $site_user) {

                $skip_user_id = false;

                if (Registry::load('current_user')->site_role_attribute !== 'administrators') {
                    if ((int)$site_user['role_hierarchy'] >= (int)Registry::load('current_user')->role_hierarchy) {
                        $skip_user_id = true;
                        $result['error_message'] = Registry::load('strings')->permission_denied;
                        $result['error_key'] = 'permission_denied';
                    }
                }

                if (!$skip_user_id) {
                    $user_ids[] = $site_user['user_id'];

                    if (!empty($site_user['profile_bg_image']) && basename($site_user['profile_bg_image']) !== 'default.png') {
                        $delete_files[] = $site_user['profile_bg_image'];

                        if (file_exists($site_user['profile_bg_image'])) {
                            unlink($site_user['profile_bg_image']);
                        }
                    }
                    if (!empty($site_user['profile_cover_pic']) && basename($site_user['profile_cover_pic']) !== 'default.png') {
                        $delete_files[] = $site_user['profile_cover_pic'];

                        if (file_exists($site_user['profile_cover_pic'])) {
                            unlink($site_user['profile_cover_pic']);
                        }
                    }
                    if (!empty($site_user['profile_picture']) && basename($site_user['profile_picture']) !== 'default.png') {
                        $delete_files[] = $site_user['profile_picture'];

                        if (file_exists($site_user['profile_picture'])) {
                            unlink($site_user['profile_picture']);
                        }
                    }
                }
            }
        }
    }



    if (!empty($user_ids)) {

        $columns = $where = null;
        $columns = ['private_conversations.private_conversation_id'];

        $where["AND"]["OR #first_query"] = [
            "private_conversations.initiator_user_id" => $user_ids,
            "private_conversations.recipient_user_id" => $user_ids,
        ];
        $conversations = DB::connect()->select('private_conversations', $columns, $where);

        foreach ($conversations as $converstation) {

            $converstation_id = $converstation['private_conversation_id'];

            if (!empty($converstation_id)) {
                $delete_audio_messages = [
                    'delete' => 'assets/files/audio_messages/private_chat/'.$converstation_id,
                    'real_path' => true,
                ];
                files('delete', $delete_audio_messages);

                if (Registry::load('settings')->cloud_storage !== 'disable') {
                    $delete_folder = 'assets/files/audio_messages/private_chat/'.$converstation_id.'/';
                    cloud_storage_module(['delete_folder' => $delete_folder]);
                }
            }
        }

        DB::connect()->delete('private_conversations', $where);


        $columns = $where = null;
        $columns = ['group_messages.attachments', 'group_messages.group_id'];

        $where = [
            'attachment_type' => 'audio_message',
            'user_id' => $user_ids
        ];

        $group_audio_messages = DB::connect()->select('group_messages', $columns, $where);

        foreach ($group_audio_messages as $group_audio_message) {

            $audio_message = $group_audio_message['attachments'];
            $group_id = $group_audio_message['group_id'];

            $audio_message = json_decode($audio_message);

            if (!empty($audio_message) && isset($audio_message->audio_message)) {
                $audio_message = basename($audio_message->audio_message);
            }

            if (!empty($audio_message)) {
                $delete_audio_messages = [
                    'delete' => 'assets/files/audio_messages/group_chat/'.$group_id.'/'.$audio_message,
                    'real_path' => true,
                ];

                files('delete', $delete_audio_messages);

                $delete_files[] = 'assets/files/audio_messages/group_chat/'.$group_id.'/'.$audio_message;
            }
        }

        DB::connect()->delete("site_users", ["user_id" => $user_ids]);

        if (!DB::connect()->error) {

            foreach ($user_ids as $user_id) {


                $membership_logs = new Store('membership_logs', 'assets/nosql_database/');
                $membership_logs->deleteById($user_id);

                $delete_storage = [
                    'delete' => 'assets/files/storage/'.$user_id,
                    'real_path' => true,
                ];

                files('delete', $delete_storage);


                if (Registry::load('settings')->cloud_storage !== 'disable') {
                    $user_storage = 'assets/files/storage/'.$user_id.'/';
                    cloud_storage_module(['delete_folder' => $user_storage]);

                    $user_filePath = 'assets/cache/user_storage/'.$user_id.'.cache';

                    if (file_exists($user_filePath)) {
                        unlink($user_filePath);
                    }
                }
            }

            if (!empty($delete_files)) {
                if (Registry::load('settings')->cloud_storage !== 'disable') {
                    cloud_storage_module(['delete_files' => $delete_files]);
                }
            }

            $result = array();
            $result['success'] = true;
            if ((int)$user_id === (int)Registry::load('current_user')->id) {
                $result['todo'] = 'refresh';
            } else {
                $result['todo'] = 'reload';
                $result['reload'] = 'site_users';

                if (isset($data['info_box'])) {
                    $result['info_box']['user_id'] = Registry::load('current_user')->id;
                }
            }
        } else {
            $result['error_message'] = Registry::load('strings')->something_went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }
    }

}
?>