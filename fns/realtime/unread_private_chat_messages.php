<?php
use Medoo\Medoo;

$data["unread_private_chat_messages"] = filter_var($data["unread_private_chat_messages"], FILTER_SANITIZE_NUMBER_INT);

if (empty($data["unread_private_chat_messages"])) {
    $data["unread_private_chat_messages"] = 0;
}


$current_user_id = Registry::load('current_user')->id;

$columns = $join = $where = null;

$columns = [
    "private_conversations.private_conversation_id",
    "ignore_recipient.ignore(ignored_recipient)",
    "ignore_initiator.ignore(ignored_initiator)",
    "ignore_recipient.block(blocked_recipient)",
    "ignore_initiator.block(blocked_initiator)"
];
$columns['unread_messages'] = medoo::raw("COUNT(<private_chat_messages.private_chat_message_id>)");

$join["[>]site_users_blacklist(ignore_recipient)"] = ["private_conversations.recipient_user_id" => "blacklisted_user_id", "AND" => ["ignore_recipient.user_id" => Registry::load('current_user')->id]];
$join["[>]site_users_blacklist(ignore_initiator)"] = ["private_conversations.initiator_user_id" => "blacklisted_user_id", "AND" => ["ignore_initiator.user_id" => Registry::load('current_user')->id]];

$join['[>]private_chat_messages'] = [
    'private_conversations.private_conversation_id' => 'private_conversation_id',
    'AND' => [
        'private_chat_messages.user_id[!]' => $current_user_id,
        'private_chat_messages.read_status' => 0
    ]
];

$where["AND #first_query"]["OR #first_query"] = [
    "private_conversations.initiator_user_id" => $current_user_id,
    "private_conversations.recipient_user_id" => $current_user_id,
];

$where["GROUP"] = ["private_chat_messages.private_conversation_id"];


$conversations = DB::connect()->select('private_conversations', $join, $columns, $where);

$unread_private_chat_messages = 0;

foreach ($conversations as $conversation) {

    $ignored_user = $blocked_user = false;

    if (isset($conversation['ignored_recipient']) && !empty($conversation['ignored_recipient']) || isset($conversation['ignored_initiator']) && !empty($conversation['ignored_initiator'])) {
        $ignored_user = true;
    }

    if (isset($conversation['blocked_recipient']) && !empty($conversation['blocked_recipient']) || isset($conversation['blocked_initiator']) && !empty($conversation['blocked_initiator'])) {
        $blocked_user = true;
    }

    if (!empty($conversation['unread_messages']) && !$ignored_user && !$blocked_user) {
        $unread_private_chat_messages = $conversation['unread_messages']+$unread_private_chat_messages;
    }
}

if ((int)$unread_private_chat_messages !== (int)$data["unread_private_chat_messages"]) {
    $result['unread_private_chat_messages'] = $unread_private_chat_messages;

    if (isset(Registry::load('settings')->play_notification_sound->on_private_conversation_unread_count_change)) {
        if ($unread_private_chat_messages > $data["unread_private_chat_messages"]) {
            $result['play_sound_notification'] = true;
        }
    }

    $escape = true;
}