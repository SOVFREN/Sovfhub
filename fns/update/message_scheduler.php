<?php

include 'fns/filters/load.php';

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$noerror = true;

if (role(['permissions' => ['super_privileges' => 'message_scheduler']])) {

    $scheduled_message_id = 0;

    if (isset($data["scheduled_message_id"])) {
        $scheduled_message_id = filter_var($data["scheduled_message_id"], FILTER_SANITIZE_NUMBER_INT);
        if (!empty($scheduled_message_id)) {

            $result['error_message'] = Registry::load('strings')->invalid_value;
            $result['error_key'] = 'invalid_value';

            $required_fields = ['sender', 'group_id', 'message', 'send_message_on'];


            if (isset($data['group_id'])) {
                $data["group_id"] = filter_var($data["group_id"], FILTER_SANITIZE_NUMBER_INT);
            }

            foreach ($required_fields as $required_field) {
                if (!isset($data[$required_field]) || empty(trim($data[$required_field]))) {
                    $result['error_variables'][] = [$required_field];
                    $noerror = false;
                }
            }


            $data['sender'] = sanitize_username($data['sender'], false);

            if (!empty($data['sender'])) {
                $sender = DB::connect()->select('site_users', ['site_users.user_id'], ["LIMIT" => 1, "site_users.username" => $data['sender']]);
            }

            if (empty($data['sender']) || !isset($sender[0])) {
                $result['error_message'] = Registry::load('strings')->account_not_found;
                $result['error_key'] = 'account_not_found';
                $noerror = false;
            } else {
                $sender_id = $sender[0]['user_id'];
            }


            if (isset($data['send_message_on']) && !empty($data['send_message_on'])) {

                $input_datetime = new DateTime($data['send_message_on'], new DateTimeZone(Registry::load('current_user')->time_zone));
                $output_timezone = new DateTimeZone('Asia/Kolkata');
                $input_datetime->setTimezone($output_timezone);
                $data['send_message_on'] = $input_datetime->format('Y-m-d H:i:s');

                if (empty($data['send_message_on'])) {
                    $noerror = false;
                }
            }

            if ($noerror) {

                include('fns/HTMLPurifier/load.php');
                $allowed_tags = 'p,span[class],b,em,i,u,strong,s,';
                $allowed_tags .= 'a[href],ol,ul,li,br';

                $config = HTMLPurifier_Config::createDefault();
                $config->set('HTML.Allowed', $allowed_tags);
                $config->set('Attr.AllowedClasses', array());
                $config->set('HTML.Nofollow', true);
                $config->set('HTML.TargetBlank', true);
                $config->set('AutoFormat.RemoveEmpty', true);

                $define = $config->getHTMLDefinition(true);
                $define->addAttribute('span', 'class', new CustomClassDef(array('emoji_icon'), array('emoji-')));

                $purifier = new HTMLPurifier($config);

                $message = $purifier->purify(trim($data['message']));

                if (!empty($message)) {

                    $repeat_message = 0;

                    if (isset($data["repeat_message"]) && $data["repeat_message"] == 'yes') {
                        $repeat_message = 1;
                    }

                    if (!isset($data["repetition_rate"])) {
                        $data["repetition_rate"] = 0;
                    }

                    if (!isset($data["repeat_interval"])) {
                        $data["repeat_interval"] = 0;
                    }

                    DB::connect()->update("scheduled_messages", [
                        "message_content" => $message,
                        "group_id" => $data["group_id"],
                        "user_id" => $sender_id,
                        "repeat_message" => $repeat_message,
                        "repetition_rate" => $data["repetition_rate"],
                        "repeat_interval" => $data["repeat_interval"],
                        "send_message_on" => $data["send_message_on"],
                        "updated_on" => Registry::load('current_user')->time_stamp,
                    ], ['scheduled_message_id' => $scheduled_message_id]);
                    $result = array();
                    $result['success'] = true;
                    $result['todo'] = 'reload';
                    $result['reload'] = 'scheduled_messages';
                } else {
                    $result['error_message'] = Registry::load('strings')->invalid_value;
                    $result['error_key'] = 'invalid_value';
                }
            }
        }
    }
}

?>