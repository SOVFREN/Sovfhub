<?php

use SleekDB\Store;

$membership_logs = new Store('membership_logs', 'assets/nosql_database/');
$membership_log_data = $membership_logs->findById(Registry::load('current_user')->id);

if (!empty($membership_log_data)) {
    if (isset($membership_log_data['non_expiring']) && empty($membership_log_data['non_expiring'])) {

        $timestamp = strtotime($membership_log_data['expiring_on']);
        $current_timestamp = time();

        if ($timestamp < $current_timestamp) {

            $site_role_id_on_expire = $membership_log_data['site_role_id_on_expire'];

            DB::connect()->update('site_users', ['site_role_id' => $site_role_id_on_expire],
                ['site_users.user_id' => Registry::load('current_user')->id]);

            DB::connect()->insert("site_notifications", [
                "user_id" => Registry::load('current_user')->id,
                "notification_type" => 'membership_expired',
                "related_user_id" => Registry::load('current_user')->id,
                "created_on" => Registry::load('current_user')->time_stamp,
                "updated_on" => Registry::load('current_user')->time_stamp,
            ]);

            $membership_logs->deleteById(Registry::load('current_user')->id);

            $result = array();
            $result['reload_page'] = $escape = true;
        } else {
            if (!isset($membership_log_data['notified_user'])) {
                if ($timestamp - $current_timestamp < 21 * 3600) {
                    DB::connect()->insert("site_notifications", [
                        "user_id" => Registry::load('current_user')->id,
                        "notification_type" => 'membership_expiring',
                        "related_user_id" => Registry::load('current_user')->id,
                        "created_on" => Registry::load('current_user')->time_stamp,
                        "updated_on" => Registry::load('current_user')->time_stamp,
                    ]);
                    $membership_logs->updateById(Registry::load('current_user')->id, ["notified_user" => "yes"]);
                }
            }
        }
    }
}