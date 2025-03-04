<?php

function video_chat_module($data) {
    $provider = Registry::load('settings')->video_chat;
    $function_file = '';
    $result = array();
    $result['success'] = false;
    $result['error_key'] = 'something_went_wrong';
    
    if ($provider !== 'disable') {

        if (!empty($provider)) {
            $provider = preg_replace("/[^a-zA-Z0-9_]+/", "", $provider);
            $provider = str_replace('libraries', '', $provider);
        }

        if (!empty($provider)) {
            $function_file = 'fns/video_chat/'.$provider.'.php';
            if (file_exists($function_file)) {
                include($function_file);
            }
        }
    }

    return $result;

}