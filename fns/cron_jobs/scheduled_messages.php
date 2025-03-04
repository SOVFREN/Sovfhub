<?php

include('fns/url_highlight/load.php');

use VStelmakh\UrlHighlight\UrlHighlight;
use VStelmakh\UrlHighlight\Validator\Validator;

$customURLHighlighter = new CustomURLHighlighter();
$convert_email_addresses = true;
$url_validator = new Validator(true, [], [], $convert_email_addresses);
$urlHighlight = new UrlHighlight($url_validator, $customURLHighlighter);

$columns = $where = $join = null;
$columns = [
    "scheduled_messages.message_content", "scheduled_messages.group_id", "scheduled_messages.user_id",
    "scheduled_messages.send_message_on", "scheduled_messages.scheduled_message_id",
    "scheduled_messages.repeat_message", "scheduled_messages.repeat_interval", "scheduled_messages.repetition_rate",
    "scheduled_messages.iteration_count",
];

$where = [
    'LIMIT' => 25,
    'ORDER' => ['scheduled_messages.scheduled_message_id' => 'DESC'],
    'scheduled_messages.send_message_on[<]' => Registry::load('current_user')->time_stamp
];
$messages = DB::connect()->select("scheduled_messages", $columns, $where);

$insert_data = array();
$delete_message_ids = array();

foreach ($messages as $message) {

    $delete_message = true;
    if ((int)$message['repeat_message'] === 1) {
        $delete_message = false;
        $new_send_on_time = $message['send_message_on'];

        $new_send_on_time = new DateTime($new_send_on_time);
        $new_send_on_time->modify('+'.$message['repeat_interval'].' minutes');
        $new_send_on_time = $new_send_on_time->format('Y-m-d H:i:s');

        $iteration_count = (int)$message['iteration_count'] + 1;

        if (!empty($message['repetition_rate']) && $iteration_count >= (int)$message['repetition_rate']) {
            $delete_message = true;
        }
        
        DB::connect()->update("scheduled_messages",
            ['scheduled_messages.send_message_on' => $new_send_on_time,
                'scheduled_messages.iteration_count' => $iteration_count
            ],
            ['scheduled_messages.scheduled_message_id' => $message['scheduled_message_id']]);
    }

    if ($delete_message) {
        $delete_message_ids[] = $message['scheduled_message_id'];
    }

    $attachment_type = null;
    $attachments = '';

    if (!empty($message['message_content'])) {

        $links = $urlHighlight->getUrls($message['message_content']);

        if (isset($links[0])) {
            include('fns/url_metadata/load.php');
            $url_meta_data = url_metadata($links[0]);
            if ($url_meta_data['success']) {
                unset($url_meta_data['success']);
                $attachments = json_encode($url_meta_data);

                if (!empty($attachments)) {
                    $attachment_type = 'url_meta';
                }
            }
        }
    }

    $insert_data[] = [
        "original_message" => $message['message_content'],
        "filtered_message" => $message['message_content'],
        "group_id" => $message['group_id'],
        "user_id" => $message['user_id'],
        "parent_message_id" => null,
        "attachment_type" => $attachment_type,
        "attachments" => $attachments,
        "link_preview" => null,
        "created_on" => $message['send_message_on'],
        "updated_on" => Registry::load('current_user')->time_stamp,
    ];
}

if (!empty($insert_data)) {
    DB::connect()->insert("group_messages", $insert_data);

    if (!empty($delete_message_ids)) {
        DB::connect()->delete("scheduled_messages", ['scheduled_messages.scheduled_message_id' => $delete_message_ids]);
    }
}

$output = array();
$output['success'] = true;
?>