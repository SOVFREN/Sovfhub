<?php

$page_content = [
    'title' => 'Allocating Images',
    'loading_text' => 'Allocating Group Images',
    'subtitle' => 'Please Wait',
    'redirect' => Registry::load('config')->site_url.'basic_process?process=allocate_images&sub_process=allocate_group_images'
];

$columns = ['groups.group_id'];

$where = [
    'groups.group_picture' => NULL,
    'LIMIT' => 50
];

$groups = DB::connect()->select('groups', $columns, $where);

if (!empty($groups)) {
    foreach ($groups as $group) {

        $update_data = array();
        $update_data['group_picture'] = get_image(['from' => 'groups/icons', 'search' => $group['group_id'], 'exclude_site_url' => true]);
        $update_data['group_cover_pic'] = get_image(['from' => 'groups/cover_pics', 'search' => $group['group_id'], 'exclude_site_url' => true]);
        $update_data['group_bg_image'] = get_image(['from' => 'groups/backgrounds', 'search' => $group['group_id'], 'exclude_site_url' => true, 'replace_with_default' => false]);

        if (strpos($update_data['group_picture'], 'default.png') === false) {
            if (file_exists($update_data['group_picture'])) {
                $fileCreationTimestamp = filemtime($update_data['group_picture']);
                $fileCreationMonthYear = date('mY', $fileCreationTimestamp);
                $folderPath = 'assets/files/groups/icons/' . $fileCreationMonthYear;

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                $newFilePath = $folderPath . '/' . basename($update_data['group_picture']);

                if (rename($update_data['group_picture'], $newFilePath)) {
                    $update_data['group_picture'] = $newFilePath;
                }
            }
        }

        if (strpos($update_data['group_cover_pic'], 'default.png') === false) {
            if (file_exists($update_data['group_cover_pic'])) {
                $fileCreationTimestamp = filemtime($update_data['group_cover_pic']);
                $fileCreationMonthYear = date('mY', $fileCreationTimestamp);
                $folderPath = 'assets/files/groups/cover_pics/' . $fileCreationMonthYear;

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                $newFilePath = $folderPath . '/' . basename($update_data['group_cover_pic']);

                if (rename($update_data['group_cover_pic'], $newFilePath)) {
                    $update_data['group_cover_pic'] = $newFilePath;
                }
            }
        }

        if (!empty($update_data['group_bg_image']) && strpos($update_data['group_bg_image'], 'default.png') === false) {
            if (file_exists($update_data['group_bg_image'])) {
                $fileCreationTimestamp = filemtime($update_data['group_bg_image']);
                $fileCreationMonthYear = date('mY', $fileCreationTimestamp);
                $folderPath = 'assets/files/groups/backgrounds/' . $fileCreationMonthYear;

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                $newFilePath = $folderPath . '/' . basename($update_data['group_bg_image']);

                if (rename($update_data['group_bg_image'], $newFilePath)) {
                    $update_data['group_bg_image'] = $newFilePath;
                }
            }
        }

        DB::connect()->update('groups', $update_data, ['groups.group_id' => $group['group_id']]);
    }
} else {
    $page_content = [
        'title' => 'Rebuilding Cache',
        'loading_text' => 'Rebuilding Cache',
        'subtitle' => 'Please Wait',
        'redirect' => Registry::load('config')->site_url.'basic_process?process=rebuild_cache'
    ];
}
?>