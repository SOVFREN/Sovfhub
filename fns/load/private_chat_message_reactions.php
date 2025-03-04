<?php

if (role(['permissions' => ['private_conversations' => 'view_reactions']])) {

    if (isset($data["message_id"])) {

        $data["message_id"] = filter_var($data["message_id"], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($data["message_id"])) {

            $user_id = Registry::load('current_user')->id;

            $column = $join = $where = null;
            $columns = [
                'private_conversations.private_conversation_id',
                'private_conversations.initiator_user_id', 'private_conversations.recipient_user_id',
            ];

            $join["[>]private_conversations"] = ["private_chat_messages.private_conversation_id" => "private_conversation_id"];
            $where = [
                "private_chat_messages.private_chat_message_id" => $data["message_id"],
            ];

            $where["LIMIT"] = 1;
            $private_chat_message = DB::connect()->select('private_chat_messages', $join, $columns, $where);

            if (isset($private_chat_message[0])) {
                $private_chat_message = $private_chat_message[0];

                if ((int)$private_chat_message['initiator_user_id'] !== (int)$user_id && (int)$private_chat_message['recipient_user_id'] !== (int)$user_id) {
                    return;
                }
            } else {
                return false;
            }

            $output = array();
            $output['loaded'] = new stdClass();
            $output['loaded']->title = Registry::load('strings')->reactions;
            $output['loaded']->loaded = 'private_chat_message_reactions';
            $output['loaded']->null_search = true;
            $output['loaded']->offset = array();

            $columns = $where = $join = null;
            $columns = [
                'private_chat_messages_reactions.user_id', 'site_users.display_name',
                'private_chat_messages_reactions.reaction_id', 'site_users.username',
                'private_chat_messages_reactions.private_chat_message_reaction_id',
            ];

            $join["[>]site_users"] = ["private_chat_messages_reactions.user_id" => "user_id"];

            $where["private_chat_messages_reactions.private_chat_message_id"] = $data["message_id"];

            if (!empty($data["offset"])) {
                $data["offset"] = array_map('intval', explode(',', $data["offset"]));
                $where["private_chat_messages_reactions.private_chat_message_reaction_id[!]"] = $data["offset"];
            }

            if (!empty($data["search"])) {
                $where["AND #search_query"] = ["OR" => [
                    "site_users.display_name[~]" => $data["search"],
                    "site_users.username" => $data["search"],
                    "site_users.email_address" => $data["search"],
                ]];
            }

            $where["LIMIT"] = Registry::load('settings')->records_per_call;

            $where["ORDER"] = ["private_chat_messages_reactions.private_chat_message_reaction_id" => "DESC"];

            $message_reactions = DB::connect()->select('private_chat_messages_reactions', $join, $columns, $where);

            $i = 1;

            if (!empty($data["offset"])) {
                $output['loaded']->offset = $data["offset"];
            }


            foreach ($message_reactions as $message_reaction) {

                $output['loaded']->offset[] = $message_reaction['private_chat_message_reaction_id'];

                $output['content'][$i] = new stdClass();
                $output['content'][$i]->title = $message_reaction['display_name'];
                $output['content'][$i]->class = "user";
                $output['content'][$i]->icon = 0;
                $output['content'][$i]->unread = 0;

                $reaction_id = $message_reaction['reaction_id'];
                $reaction_img = Registry::load('config')->site_url.'assets/files/reactions/react.png';

                if ((int)$reaction_id === 1) {
                    $reaction_img = Registry::load('config')->site_url.'assets/files/reactions/like.png';
                } else if ((int)$reaction_id === 2) {
                    $reaction_img = Registry::load('config')->site_url.'assets/files/reactions/love.png';
                } else if ((int)$reaction_id === 3) {
                    $reaction_img = Registry::load('config')->site_url.'assets/files/reactions/haha.png';
                } else if ((int)$reaction_id === 4) {
                    $reaction_img = Registry::load('config')->site_url.'assets/files/reactions/wow.png';
                } else if ((int)$reaction_id === 5) {
                    $reaction_img = Registry::load('config')->site_url.'assets/files/reactions/sad.png';
                } else if ((int)$reaction_id === 6) {
                    $reaction_img = Registry::load('config')->site_url.'assets/files/reactions/angry.png';
                }

                $output['content'][$i]->image = $reaction_img;


                $output['content'][$i]->subtitle = $message_reaction['username'];

                $option_index = 1;

                $output['options'][$i][$option_index] = new stdClass();
                $output['options'][$i][$option_index]->option = Registry::load('strings')->profile;
                $output['options'][$i][$option_index]->class = 'get_info';
                $output['options'][$i][$option_index]->attributes['data-user_identifier'] = $data['user_id'];
                $output['options'][$i][$option_index]->attributes['user_id'] = $message_reaction['user_id'];
                $option_index++;

                $i++;
            }
        }
    }
}
?>