<?php

use SleekDB\Store;

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$channel_name = '';
$channel_admin = $one_to_one = false;
$current_user_id = Registry::load('current_user')->id;

$audio_only = false;


if (Registry::load('current_user')->logged_in) {
    if (Registry::load('settings')->video_chat !== 'disable') {


        if (isset($data["group_id"]) && !empty($data["group_id"])) {
            $data["group_id"] = filter_var($data["group_id"], FILTER_SANITIZE_NUMBER_INT);
        }

        if (isset($data["user_id"]) && !empty($data["user_id"])) {
            $data["user_id"] = filter_var($data["user_id"], FILTER_SANITIZE_NUMBER_INT);
        }


        if (isset($data["group_id"]) && !empty($data["group_id"])) {
            $super_privileges = false;

            if (role(['permissions' => ['groups' => 'super_privileges']])) {
                $channel_admin = $super_privileges = true;
            }

            $columns = $join = $where = null;
            $columns = [
                'groups.name(group_name)', 'group_roles.group_role_attribute', 'groups.suspended',
                'groups.slug', 'groups.secret_group', 'groups.password', 'groups.suspended', 'groups.updated_on',
                'group_members.group_role_id', 'group_members.banned_till', 'groups.who_all_can_send_messages', 'groups.enable_video_chat',
                'groups.enable_audio_chat'
            ];

            $join["[>]group_members"] = ["groups.group_id" => "group_id", "AND" => ["user_id" => Registry::load('current_user')->id]];
            $join["[>]group_roles"] = ["group_members.group_role_id" => "group_role_id"];
            $where["groups.group_id"] = $data["group_id"];
            $where["LIMIT"] = 1;
            $group_info = DB::connect()->select('groups', $join, $columns, $where);

            if (isset($group_info[0])) {
                $group_info = $group_info[0];
            } else {
                return;
            }

            if (isset($group_info['suspended']) && !empty($group_info['suspended'])) {
                return;
            }

            if (isset($data['audio_only'])) {
                if (role(['permissions' => ['groups' => 'audio_chat']])) {
                    if (isset($group_info['enable_audio_chat']) && !empty($group_info['enable_audio_chat'])) {
                        if ($super_privileges || isset($group_info['group_role_id']) && !empty($group_info['group_role_id'])) {
                            if ($super_privileges || role(['permissions' => ['group' => 'audio_chat'], 'group_role_id' => $group_info['group_role_id']])) {
                                $audio_only = true;
                            }
                        }
                    }
                }
            }

            if ($audio_only || role(['permissions' => ['groups' => 'video_chat']])) {
                if ($audio_only || isset($group_info['enable_video_chat']) && !empty($group_info['enable_video_chat'])) {
                    if ($audio_only || $super_privileges || isset($group_info['group_role_id']) && !empty($group_info['group_role_id'])) {
                        if ($audio_only || $super_privileges || role(['permissions' => ['group' => 'video_chat'], 'group_role_id' => $group_info['group_role_id']])) {

                            $channel_name = 'group_id_'.$data["group_id"];
                            $result['channel_name'] = $channel_name;

                            $video_call_log = new Store('group_video_call_logs', 'assets/nosql_database/');
                            $existing_video_log = $video_call_log->findById($data["group_id"]);

                            if (!empty($existing_video_log) && isset($existing_video_log['online'])) {
                                if (!$existing_video_log['online']) {
                                    if (isset($existing_video_log['last_updated_on'])) {
                                        $lastUpdatedTimestamp = strtotime($existing_video_log['last_updated_on']);
                                        $currentTimestamp = strtotime(Registry::load('current_user')->time_stamp);

                                        $timeDifference = $currentTimestamp - $lastUpdatedTimestamp;

                                        if ($timeDifference > 60) {
                                            $existing_video_log = null;
                                        }
                                    }
                                }
                            }


                            if (!empty($existing_video_log) && isset($existing_video_log['online'])) {
                                if ($existing_video_log['online']) {
                                    if (isset($existing_video_log['audio_only']) && !$audio_only) {
                                        $result['alert_message'] = Registry::load('strings')->line_busy_message;
                                        return;
                                    } else if (!isset($existing_video_log['audio_only']) && $audio_only) {
                                        $result['alert_message'] = Registry::load('strings')->line_busy_message;
                                        return;
                                    }
                                }
                            }


                            if (empty($existing_video_log) || !isset($existing_video_log['online'])) {
                                $system_message = [
                                    'message' => 'user_initiated_video_call'
                                ];

                                if ($audio_only) {
                                    $system_message = [
                                        'message' => 'user_initiated_audio_call'
                                    ];
                                }

                                $system_message = json_encode($system_message);
                                DB::connect()->insert("group_messages", [
                                    "system_message" => 1,
                                    "original_message" => 'system_message',
                                    "filtered_message" => $system_message,
                                    "group_id" => $data["group_id"],
                                    "user_id" => Registry::load('current_user')->id,
                                    "created_on" => Registry::load('current_user')->time_stamp,
                                    "updated_on" => Registry::load('current_user')->time_stamp,
                                ]);
                            }

                            $call_log = [
                                "_id" => $data["group_id"],
                                "online" => true,
                                'last_updated_on' => Registry::load('current_user')->time_stamp
                            ];

                            if ($audio_only) {
                                $call_log['audio_only'] = true;
                            }

                            $video_call_log->updateOrInsert($call_log, false);
                        }
                    }
                }
            }

        } else if (isset($data["user_id"]) && !empty($data["user_id"])) {
            if (role(['permissions' => ['private_conversations' => 'video_chat']]) || role(['permissions' => ['private_conversations' => 'audio_chat']])) {
                $one_to_one = true;
                $create_video_chat = true;

                if ((int)$data['user_id'] === (int)$current_user_id) {
                    return false;
                }

                if (isset($data['audio_only'])) {
                    if (role(['permissions' => ['private_conversations' => 'audio_chat']])) {
                        $audio_only = true;
                    }
                }

                $columns = $join = $where = null;
                $columns = [
                    'site_users.online_status', 'site_roles.site_role_attribute', 'blacklist.block(blocked)',
                    'site_users_settings.deactivated', 'site_users_settings.disable_private_messages', 'site_roles.site_role_id',
                    'site_users.email_address'
                ];

                $join["[>]site_roles"] = ["site_users.site_role_id" => "site_role_id"];
                $join["[>]site_users_settings"] = ["site_users.user_id" => "user_id"];
                $join["[>]site_users_blacklist(blacklist)"] = ["site_users.user_id" => "user_id", "AND" => ["blacklist.blacklisted_user_id" => $current_user_id]];

                $where = [
                    "site_users.user_id" => $data["user_id"],
                ];

                $where["LIMIT"] = 1;
                $user_info = DB::connect()->select('site_users', $join, $columns, $where);

                if (isset($user_info[0])) {
                    $user_info = $user_info[0];

                    if (isset($user_info['deactivated']) && !empty($user_info['deactivated']) && !$super_privileges) {
                        return;
                    }

                    if (isset($user_info['blocked']) && !empty($user_info['blocked']) && !$super_privileges) {
                        return;
                    }

                    if (isset($user_info['disable_private_messages']) && !empty($user_info['disable_private_messages']) && !$super_privileges) {
                        return;
                    }

                    $pm_only_specific_roles = role(['find' => 'pm_only_specific_roles']);

                    if ($pm_only_specific_roles === 'yes') {

                        $pm_restricted_roles = role(['find' => 'pm_restricted_roles']);
                        $user_site_role = (int)$user_info['site_role_id'];

                        if (empty($pm_restricted_roles) || !in_array($user_site_role, $pm_restricted_roles)) {
                            return;
                        }

                    }

                } else {
                    return;
                }

                if ($create_video_chat) {
                    $columns = $join = $where = null;
                    $columns = [
                        'private_conversations.private_conversation_id'
                    ];

                    $where["OR"]["AND #first_query"] = [
                        "private_conversations.initiator_user_id" => $data["user_id"],
                        "private_conversations.recipient_user_id" => $current_user_id,
                    ];
                    $where["OR"]["AND #second_query"] = [
                        "private_conversations.initiator_user_id" => $current_user_id,
                        "private_conversations.recipient_user_id" => $data["user_id"],
                    ];

                    $where["LIMIT"] = 1;
                    $conversation_id = DB::connect()->select('private_conversations', $columns, $where);

                    if (isset($conversation_id[0]['private_conversation_id'])) {
                        $conversation_id = $conversation_id[0]['private_conversation_id'];
                    } else {

                        if (Registry::load('settings')->friend_system === 'enable') {
                            if (!role(['permissions' => ['private_conversations' => 'message_non_friends']])) {
                                $columns = $join = $where = null;
                                $columns = ['friendship_id', 'from_user_id', 'to_user_id', 'relation_status'];

                                $where["OR"]["AND #first_query"] = [
                                    "friends.from_user_id" => $data["user_id"],
                                    "friends.to_user_id" => $current_user_id,
                                    "friends.relation_status" => 1
                                ];
                                $where["OR"]["AND #second_query"] = [
                                    "friends.from_user_id" => $current_user_id,
                                    "friends.to_user_id" => $data["user_id"],
                                    "friends.relation_status" => 1
                                ];

                                $where["LIMIT"] = 1;

                                $check_friend_list = DB::connect()->select('friends', $columns, $where);

                                if (!isset($check_friend_list[0])) {
                                    return;
                                }
                            }
                        }
                        DB::connect()->insert("private_conversations", [
                            "initiator_user_id" => $current_user_id,
                            "recipient_user_id" => $data["user_id"],
                            "created_on" => Registry::load('current_user')->time_stamp,
                            "updated_on" => Registry::load('current_user')->time_stamp,
                        ]);
                        $conversation_id = DB::connect()->id();
                    }

                    if (isset($conversation_id) && !empty($conversation_id)) {

                        $create_channel = $add_call_log = true;

                        $video_call_log = new Store('private_video_call_logs', 'assets/nosql_database/');
                        $recipient_video_log_data = $video_call_log->findById($data["user_id"]);

                        if (is_array($recipient_video_log_data) && isset($recipient_video_log_data['incoming'])) {
                            if ((int)$recipient_video_log_data['caller_id'] !== (int)Registry::load('current_user')->id) {
                                $create_channel = false;
                                $result['alert_message'] = Registry::load('strings')->user_busy_message;
                                return;
                            }
                        }

                        $initiator_video_log_data = $video_call_log->findById(Registry::load('current_user')->id);

                        if (is_array($initiator_video_log_data) && isset($initiator_video_log_data['outgoing'])) {
                            if ((int)$initiator_video_log_data['caller_id'] !== (int)$data["user_id"]) {
                                $create_channel = false;
                            }
                        } else if (is_array($initiator_video_log_data) && isset($initiator_video_log_data['incoming'])) {
                            if ((int)$initiator_video_log_data['caller_id'] === (int)$data["user_id"]) {
                                $add_call_log = false;
                            }
                        }

                        if ($create_channel) {

                            if ($add_call_log) {
                                $call_log = [
                                    "_id" => $data["user_id"],
                                    "incoming" => true,
                                    "caller_id" => Registry::load('current_user')->id,
                                    "caller_name" => Registry::load('current_user')->name,
                                    "caller_image" => get_img_url(['from' => 'site_users/profile_pics', 'image' => Registry::load('current_user')->profile_picture, 'gravatar' => Registry::load('current_user')->email_address]),
                                    "timestamp" => Registry::load('current_user')->time_stamp,
                                    'last_updated_on' => Registry::load('current_user')->time_stamp
                                ];

                                if ($audio_only) {
                                    $call_log['audio_only'] = true;
                                }

                                $video_call_log->updateOrInsert($call_log, false);

                                $call_log = [
                                    "_id" => Registry::load('current_user')->id,
                                    "outgoing" => true,
                                    "caller_id" => $data["user_id"],
                                    "online" => true,
                                    'last_updated_on' => Registry::load('current_user')->time_stamp
                                ];

                                if ($audio_only) {
                                    $call_log['audio_only'] = true;
                                }

                                $video_call_log->updateOrInsert($call_log, false);

                                $system_message = [
                                    'message' => 'user_initiated_video_call'
                                ];

                                if ($audio_only) {
                                    $system_message = [
                                        'message' => 'user_initiated_audio_call'
                                    ];
                                }

                                $system_message = json_encode($system_message);
                                DB::connect()->insert("private_chat_messages", [
                                    "system_message" => 1,
                                    "original_message" => 'system_message',
                                    "filtered_message" => $system_message,
                                    "private_conversation_id" => $conversation_id,
                                    "user_id" => Registry::load('current_user')->id,
                                    "created_on" => Registry::load('current_user')->time_stamp,
                                    "updated_on" => Registry::load('current_user')->time_stamp,
                                ]);

                                if (isset(Registry::load('settings')->send_push_notification->on_new_private_video_call)) {
                                    include_once('fns/push_notification/load.php');

                                    $web_push = [
                                        'user_id' => $data["user_id"],
                                        'title' => Registry::load('strings')->someone,
                                        'message' => Registry::load('strings')->user_initiated_video_call,
                                    ];

                                    if ($audio_only) {
                                        $web_push['message'] = Registry::load('strings')->user_initiated_audio_call;
                                    }

                                    if (isset(Registry::load('current_user')->name)) {
                                        $web_push['title'] = Registry::load('current_user')->name;
                                    }

                                    push_notification($web_push);
                                }

                            } else {
                                $video_call_log->updateById(Registry::load('current_user')->id, ['accepted' => true]);
                            }

                            $channel_name = 'private_chat_'.$conversation_id;
                            $result['channel_name'] = $channel_name;
                        }

                    }
                }
            }
        }

        if (!empty($channel_name)) {
            include('fns/video_chat/load.php');
            $result = video_chat_module(['generate_token' => ['channel_name' => $channel_name, 'channel_admin' => $channel_admin, 'one_to_one' => $one_to_one]]);



            if ($audio_only) {
                $result['audio_only'] = true;
            }
        }
    }
}