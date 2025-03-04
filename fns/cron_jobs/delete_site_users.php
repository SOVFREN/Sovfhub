<?php

include 'fns/filters/load.php';
include 'fns/files/load.php';
include_once 'fns/cloud_storage/load.php';

use SleekDB\Store;

$parameters = json_decode($cron_job['cron_job_parameters']);

if (!empty($parameters)) {

    $entries_per_call = 25;
    $delete_older_than = 0;
    $user_ids = array();
    $site_role_ids = array();
    $delete_files = array();

    if (isset($parameters->entries_per_call)) {
        $parameters->entries_per_call = filter_var($parameters->entries_per_call, FILTER_SANITIZE_NUMBER_INT);
        if (!empty($parameters->entries_per_call)) {
            $entries_per_call = $parameters->entries_per_call;
        }
    }

    if (isset($parameters->delete_older_than)) {
        $parameters->delete_older_than = filter_var($parameters->delete_older_than, FILTER_SANITIZE_NUMBER_INT);
        if (!empty($parameters->delete_older_than)) {
            $delete_older_than = $parameters->delete_older_than;
        }
    }


    if (isset($parameters->site_role_ids)) {
        $parameters->site_role_ids = array_filter($parameters->site_role_ids, 'ctype_digit');
        if (!empty($parameters->site_role_ids)) {
            $site_role_ids = $parameters->site_role_ids;
        }
    }

    if (!empty($site_role_ids)) {
        $columns = $where = $join = null;
        $columns = [
            'site_users.user_id',
            'site_users.site_role_id',
            'site_users.profile_picture',
            'site_users.profile_cover_pic',
            'site_users.profile_bg_image'
        ];

        if (!empty($delete_older_than)) {
            $delete_older_than = '-'.$delete_older_than.' minutes';

            $dateTimeObj = DateTime::createFromFormat('Y-m-d H:i:s', Registry::load('current_user')->time_stamp);
            $dateTimeObj->modify($delete_older_than);
            $delete_older_than = $dateTimeObj->format('Y-m-d H:i:s');

            $where["site_users.created_on[<]"] = $delete_older_than;
        }

        if (isset($parameters->delete_only_offline_users) && $parameters->delete_only_offline_users === "yes") {
            $where["site_users.online_status"] = 0;
        }

        $where["site_users.site_role_id"] = $site_role_ids;
        $where["ORDER"] = ['site_users.user_id' => 'ASC'];
        $where["LIMIT"] = $entries_per_call;

        $site_users = DB::connect()->select('site_users', $columns, $where);

        foreach ($site_users as $site_user) {
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
        $columns = ['group_messages.attachments',
            'group_messages.group_id'];

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
        }
    }

    DB::connect()->update("cron_jobs", ["last_run_time" => Registry::load('current_user')->time_stamp], ['cron_job_id' => $cron_job['cron_job_id']]);

    $output = array();
    $output['success'] = true;

}

?>