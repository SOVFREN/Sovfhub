<?php

use SleekDB\Store;

if (isset($data["video_chat_status"]) && $data["video_chat_status"] === 'online') {
    $current_video_chat_online = true;
} else {
    $current_video_chat_online = false;
}

if (isset($data["audio_chat_status"]) && $data["audio_chat_status"] === 'online') {
    $current_audio_chat_online = true;
} else {
    $current_audio_chat_online = false;
}

$video_online_status = false;
$result['video_chat_status'] = array();
$video_log_data = array();
$find_by_id = null;

if (isset($data['group_id'])) {
    $data["group_id"] = filter_var($data["group_id"], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($data["group_id"])) {
        $video_call_log = new Store('group_video_call_logs', 'assets/nosql_database/');
        $video_log_data = $video_call_log->findById($data["group_id"]);
        $find_by_id = $data["group_id"];

        $result['video_chat_status']['group_id'] = $data['group_id'];
    }
} else if (isset($data['user_id'])) {
    $data["user_id"] = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($data["user_id"])) {

        $result['video_chat_status']['user_id'] = $data['user_id'];

        $video_call_log = new Store('private_video_call_logs', 'assets/nosql_database/');
        $video_log_data = $video_call_log->findById($data["user_id"]);
        $find_by_id = $data["user_id"];

        if (isset($data['current_video_caller_id']) && isset($data['current_video_caller_id'])) {
            $check_video_call_log = new Store('private_video_call_logs', 'assets/nosql_database/');
            $check_video_call_log = $check_video_call_log->findById($data["current_video_caller_id"]);

            if (!isset($check_video_call_log['caller_id'])) {
                $result['video_chat_status']['rejected'] = true;
            }

        }

    }
}

if (!empty($video_log_data)) {

    if (isset($data['group_id']) || isset($video_log_data['caller_id']) && (int)$video_log_data['caller_id'] === (int)Registry::load('current_user')->id) {

        if (isset($video_log_data['last_updated_on'])) {
            $lastUpdatedTimestamp = strtotime($video_log_data['last_updated_on']);
            $currentTimestamp = strtotime(Registry::load('current_user')->time_stamp);

            $timeDifference = $currentTimestamp - $lastUpdatedTimestamp;

            if ($timeDifference > 60) {
                unset($video_log_data['online']);
                $video_call_log->deleteById($find_by_id);

                if (isset($data['user_id'])) {
                    $video_call_log->deleteById(Registry::load('current_user')->id);
                }
            }
        }

        if (isset($video_log_data['online']) && $video_log_data['online']) {
            $video_online_status = true;
        }
    }

}

if ($video_online_status) {
    $result['video_chat_status']['online'] = true;
}

if (isset($video_log_data['audio_only'])) {
    $result['video_chat_status']['audio_only'] = true;

    if ($video_online_status !== $current_audio_chat_online) {
        $escape = true;
    }

} else {
    if ($video_online_status !== $current_video_chat_online) {
        $escape = true;
    }
}