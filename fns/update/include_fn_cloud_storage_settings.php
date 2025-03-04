<?php


if (isset($data['cloud_storage']) && $data['cloud_storage'] !== 'disable') {

    include_once('fns/cloud_storage/load.php');

    Registry::load('settings')->cloud_storage = $data['cloud_storage'];

    $validate_cloud_storage = cloud_storage_module(['validate' => $data]);

    if (!$validate_cloud_storage['success']) {
        $noerror = false;
        $result = array();
        $result['success'] = false;
        $result['error_message'] = Registry::load('strings')->something_went_wrong;
        $result['error_key'] = 'something_went_wrong';

        if (isset($validate_cloud_storage['error_key'])) {

            if ($validate_cloud_storage['error_key'] === 'assets_folder_missing') {
                $result['error_message'] = Registry::load('strings')->assets_folder_missing;
            } else if ($validate_cloud_storage['error_key'] === 'invalid_bucket_name') {
                $result['error_message'] = Registry::load('strings')->invalid_bucket_name;
            } else if ($validate_cloud_storage['error_key'] === 'invalid_credentials') {
                $result['error_message'] = Registry::load('strings')->invalid_credentials;
            } else if ($validate_cloud_storage['error_key'] === 'found_cache_folder_cloud_storage') {
                $result['error_message'] = Registry::load('strings')->found_cache_folder_cloud_storage;
            }

            $result['error_key'] = $validate_cloud_storage['error_key'];
        }
    }
}
?>