<?php
use Medoo\Medoo;

$data["unread_group_messages"] = filter_var($data["unread_group_messages"], FILTER_SANITIZE_NUMBER_INT);

if (empty($data["unread_group_messages"])) {
    $data["unread_group_messages"] = 0;
}

$super_privileges = false;

if (role(['permissions' => ['groups' => 'super_privileges']])) {
    $super_privileges = true;
}


$column = $join = $where = null;

$columns = [
    'groups.group_id',
    'group_members.last_read_message_id',
    'unread_messages' => medoo::raw('COUNT(<group_messages.group_message_id>)')
];

$join['[>]group_members'] = [
    'groups.group_id' => 'group_id',
    'AND' => ['group_members.user_id' => Registry::load('current_user')->id]
];

$join['[>]group_roles'] = [
    'group_members.group_role_id' => 'group_role_id'
];

$join['[>]group_messages'] = [
    'groups.group_id' => 'group_id',
    'AND' => [
        'group_messages.group_message_id[>]' => medoo::raw('<group_members.last_read_message_id>'),
        'group_messages.user_id[!]' => Registry::load('current_user')->id
    ]
];

$join['[>]site_users_blacklist(blacklist)'] = [
    'group_messages.user_id' => 'user_id',
    'AND' => [
        'blacklist.user_id' => Registry::load('current_user')->id
    ]
];

$join['[>]site_users_blacklist(blocked)'] = [
    'group_messages.user_id' => 'user_id',
    'AND' => [
        'blocked.blacklisted_user_id' => Registry::load('current_user')->id
    ]
];

$where = [
    'group_members.last_read_message_id[!]' => null,
    'group_members.group_role_id[!]' => null,
    'group_members.group_role_id[!]' => Registry::load('group_role_attributes')->banned_users,
    'OR #ignore_query' => [
        'blacklist.ignore' => 0,
        'blacklist.ignore' => null,
    ],
    'OR #blacklist_query' => [
        'blacklist.block' => 0,
        'blacklist.block' => null,
    ],
    'GROUP' => ['groups.group_id', 'group_members.last_read_message_id']
];

if (!$super_privileges) {
    $where['OR #blocked_query'] = [
        'blocked.block' => 0,
        'blocked.block' => null,
    ];
}

$groups = DB::connect()->select('groups', $join, $columns, $where);



$unread_group_messages = 0;

foreach ($groups as $group) {

    if (!empty($group['unread_messages'])) {
        $unread_group_messages = $group['unread_messages']+$unread_group_messages;
    }
}

if ((int)$unread_group_messages !== (int)$data["unread_group_messages"]) {
    $result['unread_group_messages'] = $unread_group_messages;

    if (isset(Registry::load('settings')->play_notification_sound->on_group_unread_count_change)) {
        if ($unread_group_messages > $data["unread_group_messages"]) {
            $result['play_sound_notification'] = true;
        }
    }

    $escape = true;
}