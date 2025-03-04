<?php

if (isset($load['message_id'])) {

    $load["message_id"] = filter_var($load["message_id"], FILTER_SANITIZE_NUMBER_INT);
    $user_id = Registry::load('current_user')->id;
    $message_id = $load['message_id'];
    $super_privileges = false;
    $edit_message = false;

    if (role(['permissions' => ['groups' => 'super_privileges']])) {
        $super_privileges = true;
    }

    if (!empty($load['message_id'])) {

        $columns = $join = $where = null;
        $columns = [
            'group_messages.group_message_id', 'group_messages.user_id', 'group_messages.filtered_message',
            'group_messages.created_on', 'group_members.group_role_id', 'site_users.display_name', 'groups.name'
        ];

        $join["[>]site_users"] = ["group_messages.user_id" => "user_id"];
        $join["[>]groups"] = ["group_messages.group_id" => "group_id"];
        $join["[>]group_members"] = ["group_messages.group_id" => "group_id", "AND" => ["group_members.user_id" => $user_id]];

        $where["group_messages.group_message_id"] = $message_id;

        $group_message = DB::connect()->select('group_messages', $join, $columns, $where);


        if (isset($group_message[0])) {

            $group_message = $group_message[0];
            $edit_message_time_limit = role(['find' => 'edit_message_time_limit']);

            if ($super_privileges || isset($group_message['group_role_id']) && !empty($group_message['group_role_id'])) {
                if ($super_privileges || role(['permissions' => ['messages' => 'edit_messages'], 'group_role_id' => $group_message['group_role_id']])) {
                    $edit_message = true;
                } else if (role(['permissions' => ['messages' => 'edit_own_message'], 'group_role_id' => $group_message['group_role_id']])) {
                    if ((int)$user_id === (int)$group_message['user_id']) {
                        if (!empty($edit_message_time_limit)) {

                            $to_time = strtotime($group_message['created_on']);
                            $from_time = strtotime("now");
                            $time_difference = round(abs($to_time - $from_time) / 60, 2);

                            if ($time_difference < $edit_message_time_limit) {
                                $edit_message = true;
                            }
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
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "group_messages"
                ];

                $form['fields']->message_id = [
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $message_id
                ];

                if (isset($load['monitoring_chat'])) {
                    $form['fields']->monitoring_chat = [
                        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => true
                    ];
                }

                $form['fields']->group_name = [
                    "title" => Registry::load('strings')->group_name, "tag" => 'input', "type" => 'text',
                    "attributes" => ['disabled' => 'disabled'], "class" => 'field', "value" => $group_message['name'],
                ];


                $form['fields']->posted_by = [
                    "title" => Registry::load('strings')->posted_by, "tag" => 'input', "type" => 'text',
                    "attributes" => ['disabled' => 'disabled'], "class" => 'field', "value" => $group_message['display_name'],
                ];

                if (!empty($group_message['filtered_message'])) {
                    $regex = '#<span.+?class="emoji_icon([^"]*)".*?/?>.*?<\/span>#i';
                    $replace = '<img class="emoji_icon $1" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="/>';
                    $group_message['filtered_message'] = preg_replace($regex, $replace, $group_message['filtered_message']);
                    $group_message['filtered_message'] = rtrim($group_message['filtered_message'], PHP_EOL);
                }


                $form['fields']->message = [
                    "title" => Registry::load('strings')->message, "tag" => 'textarea',
                    "class" => 'field page_content content_editor tiny_toolbar',
                    "placeholder" => Registry::load('strings')->message,
                    "value" => $group_message['filtered_message']
                ];

                $form['fields']->message["attributes"] = ["rows" => 6];
            }
        }

    }
}
?>