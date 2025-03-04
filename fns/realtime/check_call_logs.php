<?php

use SleekDB\Store;

$video_call_log = new Store('private_video_call_logs', 'assets/nosql_database/');
$video_log_data = $video_call_log->findById(Registry::load('current_user')->id);


$caller_id = 0;

$data["current_call_id"] = filter_var($data["current_call_id"], FILTER_SANITIZE_NUMBER_INT);

if (is_array($video_log_data) && isset($video_log_data['incoming'])) {
    if (!isset($video_log_data['accepted'])) {
        $result['new_call_notification'] = $video_log_data;
        $caller_id = $video_log_data['caller_id'];
    }
}

if ((int)$data["current_call_id"] !== (int)$caller_id) {
    $escape = true;
}