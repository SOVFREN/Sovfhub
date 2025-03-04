<?php
if (role(['permissions' => ['super_privileges' => 'message_scheduler']])) {
    $todo = 'add';
    $scheduled_message_id = 0;

    if (isset($load["scheduled_message_id"])) {
        $scheduled_message_id = filter_var($load["scheduled_message_id"], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($scheduled_message_id)) {
            $join = null;
            $join["[>]site_users"] = ["scheduled_messages.user_id" => "user_id"];
            $message = DB::connect()->select("scheduled_messages", $join,
                ["scheduled_messages.message_content", "scheduled_messages.group_id", "scheduled_messages.user_id",
                    "scheduled_messages.repeat_message", "scheduled_messages.repeat_interval", "scheduled_messages.repetition_rate",
                    "scheduled_messages.send_message_on", "site_users.username"],
                ['LIMIT' => 1, 'scheduled_message_id' => $scheduled_message_id]);

            if (isset($message[0])) {
                $todo = 'update';
                $message = $message[0];
            } else {
                $scheduled_message_id = 0;
            }
        }
    }

    $form = array();
    $form['loaded'] = new stdClass();
    $form['loaded']->title = Registry::load('strings')->message_scheduler;
    $form['loaded']->button = Registry::load('strings')->$todo;

    $form['fields'] = new stdClass();
    $group_list = array();

    $form['fields']->$todo = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "message_scheduler"
    ];

    if (!empty($scheduled_message_id)) {
        $form['fields']->scheduled_message_id = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $scheduled_message_id
        ];
    }

    $groups = DB::connect()->select('groups', ['name', 'group_id'], ['LIMIT' => 150]);

    foreach ($groups as $group) {
        $group_id = $group['group_id'];
        $group_list[$group_id] = $group['name'];
    }


    $form['fields']->group_id = [
        "title" => Registry::load('strings')->group_name, "tag" => 'select', "class" => 'field',
    ];

    if (!empty($group_list)) {
        $form['fields']->group_id['options'] = $group_list;
    }

    $sender_username = Registry::load('strings')->sender.' ['.Registry::load('strings')->username.']';
    $form['fields']->sender = [
        "title" => $sender_username, "tag" => 'input', "type" => 'text',
        "class" => 'field'
    ];

    if (empty($scheduled_message_id)) {

        $form['fields']->please_note = [
            "title" => Registry::load('strings')->please_note, "tag" => 'paragraph',
            "text" => Registry::load('strings')->schedule_cronjob_command_message_skip, "class" => 'field',
        ];

        $cron_job_url = Registry::load('config')->site_url;
        $cron_job_url .= 'cron_job/scheduled_messages/';
        $command = 'wget -q -O - '.$cron_job_url.' >/dev/null 2>&1';

        $form['fields']->command = [
            "title" => Registry::load('strings')->command, "tag" => 'textarea',
            "attributes" => ['class' => 'copy_to_clipboard'], "class" => 'field', "value" => $command,
        ];
    }

    $form['fields']->send_message_on = [
        "title" => Registry::load('strings')->send_message_on, "tag" => 'input', "type" => 'datetime-local',
        "class" => 'field'
    ];

    $form['fields']->repeat_message = [
        "title" => Registry::load('strings')->repeat_message, "tag" => 'select', "class" => 'field showfieldon'
    ];

    $form['fields']->repeat_message["attributes"] = ["fieldclass" => "repeat_messages", "checkvalue" => "yes"];

    $form['fields']->repeat_message['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];

    $form['fields']->repeat_interval = [
        "title" => Registry::load('strings')->repeat_interval, "tag" => 'input', "type" => "number",
        "class" => 'field repeat_messages d-none'
    ];

    $form['fields']->repetition_rate = [
        "title" => Registry::load('strings')->repetition_rate.' '.Registry::load('strings')->zero_equals_unlimited, "tag" => 'input', "type" => "number",
        "class" => 'field repeat_messages d-none'
    ];

    $form['fields']->message = [
        "title" => Registry::load('strings')->message, "tag" => 'textarea',
        "class" => 'field page_content content_editor tiny_toolbar',
    ];

    $form['fields']->message["attributes"] = ["rows" => 6];

    if (!empty($scheduled_message_id)) {

        $repeat_message = 'no';
        $input_datetime = new DateTime($message['send_message_on'], new DateTimeZone('Asia/Kolkata'));
        $output_timezone = new DateTimeZone(Registry::load('current_user')->time_zone);
        $input_datetime->setTimezone($output_timezone);
        $message['send_message_on'] = $input_datetime->format('Y-m-d H:i:s');

        if ((int)$message['repeat_message'] === 1) {
            $repeat_message = 'yes';
            $form['fields']->repeat_interval["class"] = 'field repeat_messages';
            $form['fields']->repetition_rate["class"] = 'field repeat_messages';
        }

        $form['fields']->repeat_message["value"] = $repeat_message;
        $form['fields']->repeat_interval['value'] = $message['repeat_interval'];
        $form['fields']->repetition_rate['value'] = $message['repetition_rate'];
        $form['fields']->group_id['value'] = $message['group_id'];
        $form['fields']->sender['value'] = $message['username'];
        $form['fields']->message['value'] = $message['message_content'];
        $form['fields']->send_message_on['value'] = $message['send_message_on'];
    }
}
?>