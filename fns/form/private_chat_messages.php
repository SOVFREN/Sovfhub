<?php

if (isset($load['message_id'])) {

    $load["message_id"] = filter_var($load["message_id"], FILTER_SANITIZE_NUMBER_INT);
    $user_id = Registry::load('current_user')->id;
    $message_id = $load['message_id'];
    $super_privileges = false;
    $edit_message = false;

    if (role(['permissions' => ['private_conversations' => 'super_privileges']])) {
        $super_privileges = true;
    }

    if (!empty($load['message_id'])) {

        $columns = $join = $where = null;
        $columns = [
            'private_chat_messages.private_chat_message_id', 'private_chat_messages.user_id', 'private_chat_messages.filtered_message',
            'private_chat_messages.created_on', 'site_users.display_name', 'private_conversations.recipient_user_id',
            'private_conversations.initiator_user_id'
        ];

        $join["[>]site_users"] = ["private_chat_messages.user_id" => "user_id"];
        $join["[>]private_conversations"] = ["private_chat_messages.private_conversation_id" => "private_conversation_id"];

        $where["private_chat_messages.private_chat_message_id"] = $message_id;

        $private_chat_message = DB::connect()->select('private_chat_messages', $join, $columns, $where);


        if (isset($private_chat_message[0])) {

            $private_chat_message = $private_chat_message[0];
            $edit_message_time_limit = role(['find' => 'edit_message_time_limit']);

            if ($super_privileges) {
                $edit_message = true;
            } else if (role(['permissions' => ['private_conversations' => 'edit_own_message']])) {
                if ((int)$user_id === (int)$private_chat_message['user_id']) {
                    if (!empty($edit_message_time_limit)) {

                        $to_time = strtotime($private_chat_message['created_on']);
                        $from_time = strtotime("now");
                        $time_difference = round(abs($to_time - $from_time) / 60, 2);

                        if ($time_difference < $edit_message_time_limit) {
                            $edit_message = true;
                        }
                    }
                }
            }


            if ($edit_message) {

                $form = array();
                $form['loaded'] = new stdClass();
                $form['loaded']->title = Registry::load('strings')->edit_message;
                $form['loaded']->button = Registry::load('strings')->update;


                $form['fields'] = new stdClass();

                $form['fields']->update = [
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "private_chat_messages"
                ];

                $form['fields']->message_id = [
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $message_id
                ];
                
                if (isset($load['monitoring_chat'])) {
                    $form['fields']->monitoring_chat = [
                        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => true
                    ];
                }

                $form['fields']->posted_by = [
                    "title" => Registry::load('strings')->posted_by, "tag" => 'input', "type" => 'text',
                    "attributes" => ['disabled' => 'disabled'], "class" => 'field', "value" => $private_chat_message['display_name'],
                ];

                if (!empty($private_chat_message['filtered_message'])) {
                    $regex = '#<span.+?class="emoji_icon([^"]*)".*?/?>.*?<\/span>#i';
                    $replace = '<img class="emoji_icon $1" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="/>';
                    $private_chat_message['filtered_message'] = preg_replace($regex, $replace, $private_chat_message['filtered_message']);
                    $private_chat_message['filtered_message'] = rtrim($private_chat_message['filtered_message'], PHP_EOL);
                }


                $form['fields']->message = [
                    "title" => Registry::load('strings')->message, "tag" => 'textarea',
                    "class" => 'field page_content content_editor tiny_toolbar',
                    "placeholder" => Registry::load('strings')->message,
                    "value" => $private_chat_message['filtered_message']
                ];

                $form['fields']->message["attributes"] = ["rows" => 6];
            }
        }

    }
}
?>