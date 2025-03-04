<?php
use Medoo\Medoo;

if (role(['permissions' => ['super_privileges' => 'message_scheduler']])) {

    $columns = $where = $join = null;
    $columns = [
        'scheduled_messages.scheduled_message_id', 'scheduled_messages.send_message_on',
        'scheduled_messages.group_id', 'scheduled_messages.user_id', 'groups.name(group_name)'
    ];


    $join["[>]groups"] = ["scheduled_messages.group_id" => "group_id"];

    if (!empty($data["offset"])) {
        $data["offset"] = array_map('intval', explode(',', $data["offset"]));
        $where["scheduled_messages.scheduled_message_id[!]"] = $data["offset"];
    }

    if (!empty($data["search"])) {
        $where["AND #search_query"]["OR"] = ["scheduled_messages.message_content[~]" => $data["search"], "groups.name[~]" => $data["search"]];
    }


    $where["LIMIT"] = Registry::load('settings')->records_per_call;
    $where["ORDER"] = [
        "scheduled_messages.scheduled_message_id" => "DESC"
    ];

    $messages = DB::connect()->select('scheduled_messages', $join, $columns, $where);

    $i = 1;
    $output = array();
    $output['loaded'] = new stdClass();
    $output['loaded']->title = Registry::load('strings')->messages;
    $output['loaded']->loaded = 'scheduled_messages';
    $output['loaded']->offset = array();

    if (!empty($data["offset"])) {
        $output['loaded']->offset = $data["offset"];
    }


    $output['multiple_select'] = new stdClass();
    $output['multiple_select']->title = Registry::load('strings')->delete;
    $output['multiple_select']->attributes['class'] = 'ask_confirmation';
    $output['multiple_select']->attributes['data-remove'] = 'scheduled_messages';
    $output['multiple_select']->attributes['multi_select'] = 'scheduled_message_id';
    $output['multiple_select']->attributes['submit_button'] = Registry::load('strings')->yes;
    $output['multiple_select']->attributes['cancel_button'] = Registry::load('strings')->no;
    $output['multiple_select']->attributes['confirmation'] = Registry::load('strings')->confirm_action;

    $output['todo'] = new stdClass();
    $output['todo']->class = 'load_form';
    $output['todo']->title = Registry::load('strings')->schedule_message;
    $output['todo']->attributes['form'] = 'message_scheduler';
    $output['todo']->attributes['enlarge'] = true;


    foreach ($messages as $message) {
        $output['loaded']->offset[] = $message['scheduled_message_id'];

        $output['content'][$i] = new stdClass();
        $output['content'][$i]->image = Registry::load('config')->site_url."assets/files/defaults/scheduled_messages.png";
        $output['content'][$i]->title = 'MSG#'.$message['scheduled_message_id'].' ['.$message['group_name'].']';
        $output['content'][$i]->identifier = $message['scheduled_message_id'];
        $output['content'][$i]->class = "device_log square";
        $output['content'][$i]->icon = 0;
        $output['content'][$i]->unread = 0;

        $send_message_on = array();
        $send_message_on['date'] = $message['send_message_on'];
        $send_message_on['auto_format'] = true;
        $send_message_on['include_time'] = true;
        $send_message_on['timezone'] = Registry::load('current_user')->time_zone;
        $send_message_on = get_date($send_message_on);

        $output['content'][$i]->subtitle = $send_message_on['date'].' '.$send_message_on['time'];


        $output['options'][$i][2] = new stdClass();
        $output['options'][$i][2]->option = Registry::load('strings')->edit;
        $output['options'][$i][2]->class = 'load_form';
        $output['options'][$i][2]->attributes['form'] = 'message_scheduler';
        $output['options'][$i][2]->attributes['enlarge'] = true;
        $output['options'][$i][2]->attributes['data-scheduled_message_id'] = $message['scheduled_message_id'];


        $output['options'][$i][3] = new stdClass();
        $output['options'][$i][3]->option = Registry::load('strings')->delete;
        $output['options'][$i][3]->class = 'ask_confirmation';
        $output['options'][$i][3]->attributes['data-remove'] = 'scheduled_messages';
        $output['options'][$i][3]->attributes['data-scheduled_message_id'] = $message['scheduled_message_id'];
        $output['options'][$i][3]->attributes['confirmation'] = Registry::load('strings')->confirm_action;
        $output['options'][$i][3]->attributes['submit_button'] = Registry::load('strings')->yes;
        $output['options'][$i][3]->attributes['cancel_button'] = Registry::load('strings')->no;


        $i++;
    }
}
?>