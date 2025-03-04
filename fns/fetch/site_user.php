<?php

$user_id = null;

if (isset($fetch['user_id'])) {
    $fetch['user_id'] = filter_var($fetch['user_id'], FILTER_SANITIZE_NUMBER_INT);
    if (!empty($fetch['user_id'])) {
        $user_id = $fetch['user_id'];
    }
}

if (!empty($user_id)) {
    $columns = $where = null;
    $columns = [
        'site_users.user_id', 'site_users.display_name', 'site_users.username',
        'site_users.profile_picture'
    ];

    $where["site_users.user_id"] = $user_id;
    $where["LIMIT"] = 1;


    $user = DB::connect()->select('site_users', $columns, $where);

    if (isset($user[0])) {

        $user = $user[0];
        $output = array();
        $output['display_name'] = $user['display_name'];
        $output['image'] = get_img_url(['from' => 'site_users/profile_pics', 'image' => $user['profile_picture']]);
        $output['username'] = $user['username'];
    }
}