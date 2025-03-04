<?php

$user_id = $file_name = 0;

if (isset($download['user_id'])) {

    if (role(['permissions' => ['storage' => 'super_privileges']])) {
        $user_id = filter_var($download["user_id"], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $user_id = Registry::load('current_user')->id;
    }

    if (isset($download['file_name'])) {
        $file_name = sanitize_filename($download['file_name']);
        $file_name = urldecode($file_name);
    }
}

$file = 'assets/files/storage/'.$user_id.'/files/'.$file_name;

$cloud_storage_valid = false;

if (!empty($user_id) && !empty($file_name) && Registry::load('settings')->cloud_storage !== 'disable') {
    if (cloud_storage_module(['file_exists' => $file])['success']) {
        $cloud_storage_valid = true;
    }
}

if (!empty($user_id) && !empty($file_name) && file_exists($file) || $cloud_storage_valid) {
    if (role(['permissions' => ['storage' => 'download_files']])) {

        if (!isset($download['validate'])) {

            $original_file_name = explode('-gr-', $file_name, 2);

            if (isset($original_file_name[1])) {
                $original_file_name = $original_file_name[1];
            } else {
                $original_file_name = $file_name;
            }

            if (Registry::load('settings')->cloud_storage !== 'disable') {
                cloud_storage_module(['download_file' => $file, 'download_as' => $original_file_name]);
            } else {
                $download_file = [
                    'download' => $file,
                    'download_as' => $original_file_name,
                    'real_path' => true
                ];

                files('download', $download_file);
            }
        } else {
            $output['download_link'] = Registry::load('config')->site_url.'download/file/user_id/'.$user_id.'/file_name/'.$file_name;
        }
    } else {
        $output['error'] = Registry::load('strings')->permission_denied;
    }
} else {
    $output['error'] = Registry::load('strings')->file_expired;
}

?>