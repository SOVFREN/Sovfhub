<?php

function cloud_storage_module($data) {
    $platform = Registry::load('settings')->cloud_storage;
    $function_file = '';
    $result = array();
    $result['success'] = false;
    $result['error_key'] = 'something_went_wrong';

    if (!empty($platform)) {
        $platform = preg_replace("/[^a-zA-Z0-9_]+/", "", $platform);
        $platform = str_replace('libraries', '', $platform);
    }

    if (!empty($platform)) {

        if ($platform === 'amazon_s3') {
            $platform = 's3_compatible';
        }

        $function_file = 'fns/cloud_storage/'.$platform.'.php';
        if (file_exists($function_file)) {
            include($function_file);
        }
    }

    return $result;

}