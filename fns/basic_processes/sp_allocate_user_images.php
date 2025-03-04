<?php

$page_content = [
    'title' => 'Allocating Images',
    'loading_text' => 'Allocating User Images',
    'subtitle' => 'Please Wait',
    'redirect' => Registry::load('config')->site_url.'basic_process?process=allocate_images&sub_process=allocate_user_images'
];

$columns = ['site_users.user_id'];

$where = [
    'site_users.profile_picture' => NULL,
    'LIMIT' => 50
];

$users = DB::connect()->select('site_users', $columns, $where);

if (!empty($users)) {
    foreach ($users as $user) {

        $update_data = array();
        $update_data['profile_picture'] = get_image(['from' => 'site_users/profile_pics', 'search' => $user['user_id'], 'exclude_site_url' => true]);
        $update_data['profile_cover_pic'] = get_image(['from' => 'site_users/cover_pics', 'search' => $user['user_id'], 'exclude_site_url' => true]);
        $update_data['profile_bg_image'] = get_image(['from' => 'site_users/backgrounds', 'search' => $user['user_id'], 'exclude_site_url' => true, 'replace_with_default' => false]);

        if (strpos($update_data['profile_picture'], 'default.png') === false) {
            if (file_exists($update_data['profile_picture'])) {
                $fileCreationTimestamp = filemtime($update_data['profile_picture']);
                $fileCreationMonthYear = date('mY', $fileCreationTimestamp);
                $folderPath = 'assets/files/site_users/profile_pics/' . $fileCreationMonthYear;

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                $newFilePath = $folderPath . '/' . basename($update_data['profile_picture']);

                if (rename($update_data['profile_picture'], $newFilePath)) {
                    $update_data['profile_picture'] = $newFilePath;
                }
            }
        }

        if (strpos($update_data['profile_cover_pic'], 'default.png') === false) {
            if (file_exists($update_data['profile_cover_pic'])) {
                $fileCreationTimestamp = filemtime($update_data['profile_cover_pic']);
                $fileCreationMonthYear = date('mY', $fileCreationTimestamp);
                $folderPath = 'assets/files/site_users/cover_pics/' . $fileCreationMonthYear;

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                $newFilePath = $folderPath . '/' . basename($update_data['profile_cover_pic']);

                if (rename($update_data['profile_cover_pic'], $newFilePath)) {
                    $update_data['profile_cover_pic'] = $newFilePath;
                }
            }
        }

        if (!empty($update_data['profile_bg_image']) && strpos($update_data['profile_bg_image'], 'default.png') === false) {
            if (file_exists($update_data['profile_bg_image'])) {
                $fileCreationTimestamp = filemtime($update_data['profile_bg_image']);
                $fileCreationMonthYear = date('mY', $fileCreationTimestamp);
                $folderPath = 'assets/files/site_users/backgrounds/' . $fileCreationMonthYear;

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                $newFilePath = $folderPath . '/' . basename($update_data['profile_bg_image']);

                if (rename($update_data['profile_bg_image'], $newFilePath)) {
                    $update_data['profile_bg_image'] = $newFilePath;
                }
            }
        }

        DB::connect()->update('site_users', $update_data, ['site_users.user_id' => $user['user_id']]);
    }
} else {
    $page_content = [
        'title' => 'Allocating Images',
        'loading_text' => 'Allocating Group Images',
        'subtitle' => 'Please Wait',
        'redirect' => Registry::load('config')->site_url.'basic_process?process=allocate_images&sub_process=allocate_group_images'
    ];
}
?>