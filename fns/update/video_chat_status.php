<?php

use SleekDB\Store;

if (Registry::load('current_user')->logged_in) {
    if (Registry::load('settings')->video_chat !== 'disable') {

        if (isset($data['group_id'])) {
            $data["group_id"] = filter_var($data["group_id"], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($data["group_id"])) {

                $video_call_log = new Store('group_video_call_logs', 'assets/nosql_database/');

                if (isset($data["offline"])) {
                    $video_call_log->updateById($data["group_id"], ["online" => false]);
                } else {
                    $existing_video_log = $video_call_log->findById($data["group_id"]);

                    $call_log = [
                        "_id" => $data["group_id"],
                        "online" => true,
                        'last_updated_on' => Registry::load('current_user')->time_stamp
                    ];

                    if (isset($existing_video_log['audio_only'])) {
                         $call_log['audio_only'] = true;
                    }

                    $video_call_log->updateOrInsert($call_log, false);
                }
            }

        } else if (isset($data['user_id'])) {
            $data["user_id"] = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($data["user_id"])) {

                $video_call_log = new Store('private_video_call_logs', 'assets/nosql_database/');
                $video_log_data = $video_call_log->findById($data["user_id"]);

                if (isset($data["offline"])) {
                    $video_call_log->deleteById(Registry::load('current_user')->id);
                }

                if (isset($video_log_data['caller_id']) && (int)$video_log_data['caller_id'] === (int)Registry::load('current_user')->id) {

                    if (isset($data["offline"])) {
                        $video_call_log->deleteById($data["user_id"]);
                    } else {
                        $video_call_log->updateById($data["user_id"], ['last_updated_on' => Registry::load('current_user')->time_stamp]);
                        $video_call_log->updateById(Registry::load('current_user')->id, ['last_updated_on' => Registry::load('current_user')->time_stamp]);
                    }
                }
            }

        } else if (isset($data['call_log_delete'])) {
            $video_call_log = new Store('private_video_call_logs', 'assets/nosql_database/');
            $video_call_log->deleteById(Registry::load('current_user')->id);
        }
    }
}