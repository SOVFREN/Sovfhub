<?php

if (Registry::load('current_user')->logged_in) {
    if (isset($data['realtime_log_id']) && !empty($data['realtime_log_id'])) {

        $data["realtime_log_id"] = filter_var($data["realtime_log_id"], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($data['realtime_log_id'])) {
            $columns = $join = $where = null;
            $columns = ['realtime_logs.realtime_log_id', 'realtime_logs.log_type', 'realtime_logs.related_parameters'];
            $where["realtime_logs.realtime_log_id"] = $data["realtime_log_id"];
            $where["LIMIT"] = 1;

            $realtime_log = DB::connect()->select('realtime_logs', $columns, $where);

            if (isset($realtime_log[0])) {
                $realtime_log = $realtime_log[0];

                if ($realtime_log['log_type'] === 'mention_everyone') {
                    $related_parameters = json_decode($realtime_log['related_parameters']);
                    if (isset($related_parameters->user_id) && (int)$related_parameters->user_id === (int)Registry::load('current_user')->id) {
                        $last_notified_group_member_id = 0;
                        if (isset($related_parameters->last_notified_group_member_id)) {
                            $last_notified_group_member_id = $related_parameters->last_notified_group_member_id;
                        }

                        $columns = $join = $where = null;
                        $columns = ['group_members.group_member_id', 'group_members.user_id'];
                        $where["group_members.group_id"] = $related_parameters->group_id;
                        $where["LIMIT"] = 150;

                        if (!empty($last_notified_group_member_id)) {
                            $where["group_members.group_member_id[>]"] = $last_notified_group_member_id;
                        }

                        $where['group_members.group_role_id[!]'] = Registry::load('group_role_attributes')->banned_users;

                        $group_members = DB::connect()->select('group_members', $columns, $where);
                        $total_members = count($group_members);

                        if ($total_members > 0) {
                            $total_members = (int)$total_members-1;
                            $related_parameters->last_notified_group_member_id = $group_members[$total_members]['group_member_id'];
                            $new_related_parameters = json_encode($related_parameters);

                            $where = ["realtime_logs.realtime_log_id" => $data["realtime_log_id"]];
                            DB::connect()->update('realtime_logs', ['related_parameters' => $new_related_parameters], $where);
                            $add_site_notification = $notify_user_ids = array();

                            foreach ($group_members as $group_member) {
                                if ((int)$group_member['user_id'] !== (int)$related_parameters->user_id) {
                                    $notify_user_ids[] = $group_member['user_id'];
                                    $add_site_notification[] = [
                                        "user_id" => $group_member['user_id'],
                                        "notification_type" => 'mentioned_group_chat',
                                        "related_group_id" => $related_parameters->group_id,
                                        "related_message_id" => $related_parameters->message_id,
                                        "related_user_id" => $related_parameters->user_id,
                                        "created_on" => Registry::load('current_user')->time_stamp,
                                        "updated_on" => Registry::load('current_user')->time_stamp,
                                    ];
                                }
                            }

                            if (!empty($add_site_notification)) {
                                DB::connect()->insert("site_notifications", $add_site_notification);

                                if (isset(Registry::load('settings')->send_push_notification->on_user_mention_group_chat) && !empty($notify_user_ids)) {
                                    include('fns/push_notification/load.php');

                                    $web_push = [
                                        'user_id' => $notify_user_ids,
                                        'title' => Registry::load('strings')->someone,
                                        'message' => Registry::load('strings')->web_push_mentioned_user_message,
                                    ];

                                    if (isset(Registry::load('current_user')->name)) {
                                        $web_push['title'] = Registry::load('current_user')->name;
                                    }
                                    if (isset($related_parameters->message) && !empty($related_parameters->message)) {
                                        $web_push_message = preg_replace('/<span\b[^>]*>(.*?)<\/span>/i', '', $related_parameters->message);
                                        $web_push_message = strip_tags($web_push_message);

                                        if (!empty($web_push_message)) {
                                            $web_push['message'] = $web_push_message;
                                        }
                                    }

                                    push_notification($web_push);
                                }
                            }

                            $result['continue_process'] = true;
                        } else {
                            $where = ["realtime_logs.realtime_log_id" => $data["realtime_log_id"]];
                            DB::connect()->delete('realtime_logs', $where);
                        }
                    }
                }
            }
        }
    }
}

?>