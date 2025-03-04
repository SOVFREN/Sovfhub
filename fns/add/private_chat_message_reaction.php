<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$no_error = false;
$reaction_id = 0;
$super_privileges = false;

if (role(['permissions' => ['private_conversations' => 'react_messages']])) {
    $user_id = Registry::load('current_user')->id;
    $private_chat_message_id = 0;

    $reactions = [
        'like' => 1, 'love' => 2, 'haha' => 3,
        'wow' => 4, 'sad' => 5, 'angry' => 6
    ];


    if (isset($data['private_chat_message_id'])) {
        $private_chat_message_id = filter_var($data["private_chat_message_id"], FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($data['reaction'])) {
        $user_reaction = preg_replace("/[^a-zA-Z0-9_]+/", "", $data["reaction"]);

        if (!empty($user_reaction) && isset($reactions[$user_reaction])) {
            $reaction_id = $reactions[$user_reaction];
        }
    }

    if (!empty($user_id) && !empty($private_chat_message_id) && !empty($reaction_id)) {
        $no_error = true;
    }

    if ($no_error) {
        $columns = $join = $where = null;
        $columns = [
            'private_chat_messages.private_conversation_id', 'private_chat_messages_reactions.reaction_id',
            'private_chat_messages_reactions.private_chat_message_reaction_id', 'private_conversations.initiator_user_id ',
            'private_conversations.recipient_user_id'
        ];

        $join["[>]private_conversations"] = ["private_chat_messages.private_conversation_id" => "private_conversation_id"];
        $join["[>]private_chat_messages_reactions"] = [
            "private_chat_messages.private_chat_message_id" => "private_chat_message_id",
            "AND" => ["private_chat_messages_reactions.user_id" => $user_id]
        ];

        $where["private_chat_messages.private_chat_message_id"] = $private_chat_message_id;
        $where["LIMIT"] = 1;

        $private_chat_message = DB::connect()->select('private_chat_messages', $join, $columns, $where);

        if (isset($private_chat_message[0])) {

            $private_chat_message = $private_chat_message[0];
            $todo = 'add_reaction';

            if (isset($private_chat_message['reaction_id']) && !empty($private_chat_message['reaction_id'])) {
                $todo = 'update_reaction';
                if ((int)$private_chat_message['reaction_id'] === (int)$reaction_id) {
                    $todo = 'remove_reaction';
                }
            }

            if ((int)$private_chat_message['initiator_user_id'] !== (int)$user_id && (int)$private_chat_message['recipient_user_id'] !== (int)$user_id) {
                return;
            }

            if ($todo === 'add_reaction') {
                DB::connect()->insert("private_chat_messages_reactions", [
                    "user_id" => $user_id,
                    "private_chat_message_id" => $private_chat_message_id,
                    "reaction_id" => $reaction_id,
                    "updated_on" => Registry::load('current_user')->time_stamp,
                ]);
            } else if ($todo === 'update_reaction') {
                DB::connect()->update("private_chat_messages_reactions", [
                    "reaction_id" => $reaction_id,
                    "updated_on" => Registry::load('current_user')->time_stamp,
                ], [
                    "private_chat_message_reaction_id" => $private_chat_message['private_chat_message_reaction_id'],
                    "user_id" => $user_id
                ]);
            } else if ($todo === 'remove_reaction') {
                $user_reaction = null;
                DB::connect()->delete("private_chat_messages_reactions", [
                    "private_chat_message_reaction_id" => $private_chat_message['private_chat_message_reaction_id'],
                    "user_id" => $user_id
                ]);
            }

            $all_reactions = DB::connect()->select("private_chat_messages_reactions", ["reaction_id,private_chat_message_reaction_id"], ["private_chat_message_id" => $private_chat_message_id]);
            $total_reactions = array();
            $reactions = [
                1 => 'like', 2 => 'love', 3 => 'haha',
                4 => 'wow', 5 => 'sad', 6 => 'angry'
            ];

            foreach ($all_reactions as $reaction) {
                $reaction_id = $reaction['reaction_id'];

                if (isset($reactions[$reaction_id])) {
                    $reaction_index = $reactions[$reaction_id];

                    if (!isset($total_reactions[$reaction_index])) {
                        $total_reactions[$reaction_index] = 0;
                    }

                    $total_reactions[$reaction_index] = ($total_reactions[$reaction_index])+1;
                }
            }

            $update_total_reactions = json_encode($total_reactions);

            DB::connect()->update("private_chat_messages", ["total_reactions" => $update_total_reactions], ["private_chat_message_id" => $private_chat_message_id]);


            $user_ids = array();
            $user_ids[] = $private_chat_message['initiator_user_id'];
            $user_ids[] = $private_chat_message['recipient_user_id'];


            $realtime_log_data = array();
            $realtime_log_data["log_type"] = 'message_reaction';
            $realtime_log_data["related_parameters"] = [
                "user_ids" => $user_ids,
                "message_id" => $private_chat_message_id,
                "total_reactions" => $update_total_reactions,
            ];
            $realtime_log_data["related_parameters"] = json_encode($realtime_log_data["related_parameters"]);
            $realtime_log_data["created_on"] = Registry::load('current_user')->time_stamp;

            DB::connect()->insert("realtime_logs", $realtime_log_data);

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'update_message_reactions';
            $result['update_data']['user_ids'] = $user_ids;
            $result['update_data']['message_id'] = $private_chat_message_id;
            $result['update_data']['can_react'] = true;

            if (!empty($total_reactions)) {
                $result['update_data']['total_reactions'] = $total_reactions;
            }

            if (!empty($user_reaction)) {
                $result['update_data']['user_reaction'] = $user_reaction;
            }
        }
    }
}

?>